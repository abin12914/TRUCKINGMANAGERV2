<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
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
            'employee_type' => [
                'paramName'     => 'employee_type',
                'paramOperator' => '=',
                'paramValue'    => $request->get('employee_type'),
            ],
            'employee_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('employee_id'),
            ]
        ];
        
        return view('employees.list', [
                'employees'         => $this->employeeRepo->getEmployees($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], $aggregates=['key' => null, 'value' => null], [], true),
                'params'      => $whereParams,
                'noOfRecords' => $noOfRecordsPerPage,
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
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $errorCode              = 0;
        $employee               = null;
        $employeeAccount        = null;
        $openingTransactionId   = null;

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $whereParams = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Account-Opening-Balance",
                ]
            ];
            //confirming opening balance existency.
            $openingBalanceAccountId = $accountRepo->getAccounts($whereParams,$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => 1], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)->id;

            if(!empty($id)) {
                $employee = $this->employeeRepo->getEmployee($id, ['account'], false);

                if($employee->account->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $employee->account_id],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $employee->account_id],
                    ];
                }

                $openingTransactionId = $transactionRepo->getTransactions($searchTransaction, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], [], [], null, false )->id;
            }

            //save to account table
            $accountResponse = $accountRepo->saveAccount([
                'account_name'      => $request->get('account_name'),
                'description'       => $request->get('description'),
                'type'              => array_search('Personal', (config('constants.accountTypes'))),
                'relation'          => array_search('Employees', config('constants.accountRelations')), //employee //key=1
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'status'            => 1
            ], (!empty($employee) ? $employee->account_id : null));

            if(!$accountResponse['flag']) {
                throw new TMException("CustomError", $accountResponse['errorCode']);
            }

            //opening balance transaction details
            if($financialStatus == 1) { //incoming [account holder gives cash to company] [Creditor]
                $debitAccountId     = $openingBalanceAccountId; //cash flow into the opening balance account
                $creditAccountId    = $accountResponse['account']->id; //newly created account id [flow out from new account]
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){ //outgoing [company gives cash to account holder] [Debitor]
                $debitAccountId     = $accountResponse['account']->id; //newly created account id [flow into new account]
                $creditAccountId    = $openingBalanceAccountId; //flow out from the opening balance account
                $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
            } else {
                $debitAccountId     = $openingBalanceAccountId;
                $creditAccountId    = $accountResponse['account']->id; //newly created account id
                $particulars        = "Opening balance of ". $name . " - None";
                $openingBalance     = 0;
            }

            //save to transaction table
            $transactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
                'amount'            => $openingBalance,
                'particulars'       => $particulars,
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], $openingTransactionId);

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            $employeeResponse = $this->employeeRepo->saveEmployee([
                'account_id'    => $accountResponse['account']->id, //newly created account id
                'employee_type' => $request->get('employee_type'),
                'wage_type'     => $request->get('wage_type'),
                'wage_value'    => $request->get('wage_value'),
                'status'        => 1,
            ], $id);

            if(!$employeeResponse['flag']) {
                throw new TMException("CustomError", $employeeResponse['errorCode']);
            }

            DB::commit();
            if(!empty($id)) {
                return [
                    'flag'     => true,
                    'employee' => $employeeResponse['employee']
                ];
            }

            return redirect(route('employees.show', $employeeResponse['employee']->id))->with("message","Employee details saved successfully. Reference Number : ". $employeeResponse['employee']->id)->with("alert-class", "success");
        } catch (Exception $e) {dd($e);
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);
        }
        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
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
        $employee   = [];

        try {
            $employee = $this->employeeRepo->getEmployee($id, [], false);
        } catch (Exception $e) {
       $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);
        //throwing methodnotfound exception when no model is fetched
        throw new ModelNotFoundException("Employee", $errorCode);
    }
        return view('employees.details', ['employee'  => $employee]);
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
        $employee   = [];

        try {
            $employee = $this->employeeRepo->getEmployee($id);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Employee", $errorCode);
        }

        return view('employees.edit', [
            'employee'  => $employee,
            'wageTypes' => config('constants.employeeWageTypes'),
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
        AccountRepository $accountRepo,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $accountRepo, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('employees.show', $updateResponse['employee']->id))->with("message","Employee details updated successfully. Updated Record Number : ". $updateResponse['employee']->id)->with("alert-class", "success");
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
