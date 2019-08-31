<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Http\Requests\EmployeeFilterRequest;
use \Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends Controller
{
    protected $employeeRepo;
    public $errorHead = null;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
        $this->errorHead   = config('settings.controllerCode.EmployeeController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeFilterRequest $request)
    {
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');

        $whereParams = [
            'relation_type' => [
                'paramName'     => 'relation',
                'paramOperator' => '=',
                'paramValue'    => $request->get('relation_type'),
            ],
            'employee_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('employee_id'),
            ],
            'type' => [
                'paramName'     => 'type',
                'paramOperator' => '=',
                'paramValue'    => 3,
            ],
        ];

        $orWhereParams = [
            'employee_name' => [
                'paramName'     => 'employee_name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('name'). "%"),
            ],
            'name' => [
                'paramName'     => 'name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('name'). "%"),
            ]
        ];

        //getEmployees($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)
        return view('employees.list', [
            'employees'      => $this->employeeRepo->getEmployees($whereParams, $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], ['key' => null, 'value' => null], [], true),
            'relationTypes' => config('constants.employeeRelationTypes'),
            'params'        => array_merge($whereParams,$orWhereParams),
            'noOfRecords'   => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        EmployeeRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $errorCode            = 0;
        $employee              = null;
        $openingTransactionId = null;

        $openingBalanceEmployeeId = config('constants.employeeConstants.EmployeeOpeningBalance.id');

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();
            //confirming opening balance existency.
            //getEmployee($id, $withParams=[], $activeFlag=true)
            $openingBalanceEmployee = $this->employeeRepo->getEmployee($openingBalanceEmployeeId, [], false);

            if(!empty($id)) {
                $employee = $this->employeeRepo->getEmployee($id, [], false);

                if($employee->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_employee_id', 'paramOperator' => '=', 'paramValue' => $employee->id],
                        ['paramName' => 'credit_employee_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceEmployeeId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_employee_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceEmployeeId],
                        ['paramName' => 'credit_employee_id', 'paramOperator' => '=', 'paramValue' => $employee->id],
                    ];
                }

                //getTransactions($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$relation,$activeFlag=true)
                $openingTransactionId = $transactionRepo->getTransactions($searchTransaction, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], [], [], null, false)->id;
            }

            //save to employee table
            $employeeResponse   = $this->employeeRepo->saveEmployee([
                'employee_name'      => $request->get('employee_name'),
                'description'       => $request->get('description'),
                'type'              => array_search('Personal', (config('constants.employeeTypes'))),
                'relation'          => $request->get('relation_type'),
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'status'            => 1,
                'created_by'        => $user->id,
                'company_id'        => $user->company_id,
            ], $id);

            if(!$employeeResponse['flag']) {
                throw new AppCustomException("CustomError", $employeeResponse['errorCode']);
            }

            //opening balance transaction details
            if($financialStatus == 1) { //incoming [employee holder gives cash to company] [Creditor]
                $debitEmployeeId     = $openingBalanceEmployeeId; //cash flow into the opening balance employee
                $creditEmployeeId    = $employeeResponse['employee']->id; //newly created employee id [flow out from new employee]
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){ //outgoing [company gives cash to employee holder] [Debitor]
                $debitEmployeeId     = $employeeResponse['employee']->id; //newly created employee id [flow into new employee]
                $creditEmployeeId    = $openingBalanceEmployeeId; //flow out from the opening balance employee
                $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
            } else {
                $debitEmployeeId     = $openingBalanceEmployeeId;
                $creditEmployeeId    = $employeeResponse['employee']->id; //newly created employee id
                $particulars        = "Opening balance of ". $name . " - None";
                $openingBalance     = 0;
            }

            //save to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_employee_id'  => $debitEmployeeId,
                'credit_employee_id' => $creditEmployeeId,
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'status'            => 1,
                'company_id'        => $user->company_id,
            ], $openingTransactionId);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            DB::commit();
            
            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'employee' => $employeeResponse['employee'],
                ];
            }
            return redirect(route('employee.show', $employeeResponse['employee']->id))->with("message","Employee details saved successfully. Reference Number : ". $employeeResponse['employee']->id)->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);
        }
        if(!empty($id)) {
            return [
                'flag'      => false,
                'errorCode' => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the employee details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $employee    = [];

        try {
            $employee = $this->employeeRepo->getEmployee($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing model not found exception when no model is fetched
            throw new ModelNotFoundException("Employee", $errorCode);
        }

        return view('employees.details', [
            'employee'       => $employee,
            'relationTypes' => config('constants.employeeRelationTypes'),
            'employeeTypes'  => config('constants.$employeeTypes'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $errorCode  = 0;
        $employee    = [];

        $relationTypes        = config('constants.employeeRelationTypes');
        $employeeRelationType = array_search('Employees', config('constants.employeeRelationTypes')); //employee -> [index = 1]
        //excluding the relationtype 'employee'[index = 1] for employee update
        unset($relationTypes[$employeeRelationType]);

        try {
            $employee = $this->employeeRepo->getEmployee($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Employee", $errorCode);
        }

        return view('employees.edit', [
            'employee'       => $employee,
            'relationTypes' => $relationTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(
        EmployeeRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('employee.show', $updateResponse['employee']->id))->with("message","Employee details updated successfully. Updated Record Number : ". $updateResponse['employee']->id)->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the employee details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }
}
