<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseFilterRequest;
use App\Http\Requests\ExpenseRegistrationRequest;
use App\Http\Requests\CertificateUpdateRequest;
use App\Repositories\ExpenseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TruckRepository;
use App\Repositories\ServiceRepository;
use Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpenseController extends Controller
{
    protected $expenseRepo;
    public $errorHead = null;

    public function __construct(ExpenseRepository $expenseRepo)
    {
        $this->expenseRepo = $expenseRepo;
        $this->errorHead   = config('settings.controller_code.ExpenseController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpenseFilterRequest $request)
    {
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');
        //date format conversion
        $fromDate    = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : null;
        $toDate      = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : null;

        $whereParams = [
            'service_id' => [
                'paramName'     => 'service_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('service_id'),
            ],
            'truck_id' => [
                'paramName'     => 'truck_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_id'),
            ],
        ];

        $relationalParams = [
            'account_id' => [
                'relation'      => 'transaction',
                'paramName'     => 'credit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('account_id'),
            ],
            'from_date' => [
                'relation'      => 'transaction',
                'paramName'     => 'transaction_date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date' => [
                'relation'      => 'transaction',
                'paramName'     => 'transaction_date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
        ];

        $expenses = $this->expenseRepo->getExpenses($whereParams, [], $relationalParams, ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], [], [], true);
        $totalExpense = $this->expenseRepo->getExpenses($whereParams, [], $relationalParams, [], ['key' => 'sum', 'value' => 'amount'], [], true);

        //params passing for auto selection
        $relationalParams['from_date']['paramValue'] = $request->get('from_date');
        $relationalParams['to_date']['paramValue']   = $request->get('to_date');

        //getExpenses($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $withParams=[],$activeFlag=true)
        return view('expenses.list', [
            'expenses'     => $expenses,
            'totalExpense' => $totalExpense,
            'params'       => array_merge($whereParams, $relationalParams),
            'noOfRecords'  => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expenses.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        ExpenseRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id=null
    ) {
        $errorCode = 0;
        $expense   = null;

        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('transaction_date'))->format('Y-m-d');
        $totalBill          = $request->get('amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $whereParams = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Service-And-Expenses",
                ]
            ];
            //confirming expense account exist-ency.
            $expenseAccount = $accountRepo->getAccounts($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], [], true);
            if(!empty($id)) {
                $expense = $this->expenseRepo->getExpense($id, [], false);
            }

            //save expense transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $expenseAccount->id, // debit the expense account
                'credit_account_id' => $request->get('account_id'), // credit the supplier
                'amount'            => $totalBill,
                'particulars'       => $request->get('description')."[Purchase & Expense]",
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($expense) ? $expense->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to expense table
            $expenseResponse = $this->expenseRepo->saveExpense([
                'transaction_id'    => $transactionResponse['transaction']->id,
                'truck_id'          => $request->get('truck_id'),
                'service_id'        => $request->get('service_id'),
                'description'       => $request->get('description'),
                'amount'            => $totalBill,
                'status'            => 1,
            ], $id);

            if(!$expenseResponse['flag']) {
                throw new TMException("CustomError", $expenseResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'expense' => $expenseResponse['expense']
                ];
            }

            return redirect(route('expenses.index'))->with("message","Expense details saved successfully. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
        } catch (Exception $e) {
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
        return redirect()->back()->with("message","Failed to save the expense details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense    = [];

        try {
            $expense = $this->expenseRepo->getExpense($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Expense", $errorCode);
        }

        return view('expenses.details', [
            'expense' => $expense,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense    = [];

        try {
            $expense = $this->expenseRepo->getExpense($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Expense", $errorCode);
        }

        return view('expenses.edit-add', [
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        ExpenseRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $accountRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('expenses.index'))->with("message","Expenses details updated successfully. Updated Record Number : ". $updateResponse['expense']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the expenses details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $errorCode  = 0;

        //wrapping db transactions
        DB::beginTransaction();
        try {
            $deleteResponse = $this->expenseRepo->deleteExpense($id, false);

            if(!$deleteResponse['flag']) {
                throw new TMException("CustomError", $deleteResponse['errorCode']);
            }

            DB::commit();
            return redirect(route('expenses.index'))->with("message","Expense details deleted successfully.")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the expense details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Show the form for editing the certificate of a truck
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function certEdit(TruckRepository $truckRepo, $truckId)
    {
        $truck    = [];

        try {
            $truck = $truckRepo->getTruck($truckId, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 5);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Truck", $errorCode);
        }

        return view('expenses.certificates.renew', [
            'truck' => $truck,
        ]);
    }

    /**
     * update renewed certificate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function certUpdate(
        CertificateUpdateRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        TruckRepository $truckRepo,
        ServiceRepository $serviceRepo
    ) {
        $errorCode = 0;

        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('transaction_date'))->format('Y-m-d');
        $totalBill          = $request->get('amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $whereParams = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Service-And-Expenses",
                ]
            ];
            //confirming expense account exist-ency.
            $expenseAccount = $accountRepo->getAccounts($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], [], true);

            $serviceWhere = [
                'service_type'  => [
                    'paramName'     => 'name',
                    'paramOperator' => '=',
                    'paramValue'    => 'Certificate Renewal'
                ]
            ];
            $serviceType = $serviceRepo->getServices($serviceWhere, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], [], true);

            //save expense transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $expenseAccount->id, // debit the expense account
                'credit_account_id' => $request->get('account_id'), // credit the supplier
                'amount'            => $totalBill,
                'particulars'       => $request->get('description'). " : Certificate Renewal [Purchase & Expense]",
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], null);

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to expense table
            $expenseResponse = $this->expenseRepo->saveExpense([
                'transaction_id'    => $transactionResponse['transaction']->id,
                'truck_id'          => $request->get('truck_id'),
                'service_id'        => $serviceType->id,
                'description'       => 'Certificate Renewal',
                'amount'            => $totalBill,
                'status'            => 1,
            ], null);

            if(!$expenseResponse['flag']) {
                throw new TMException("CustomError", $expenseResponse['errorCode']);
            }

            //save truck to table
            $truckResponse   = $truckRepo->saveTruck([
                $request->get('certificate_type') => !empty($request->get("updated_date")) ? Carbon::createFromFormat('d-m-Y', $request->get("updated_date"))->format('Y-m-d') : null,
            ], $request->get('truck_id'));

            if(!$truckResponse['flag']) {
                throw new TMException("CustomError", $truckResponse['errorCode']);
            }

            DB::commit();

            return redirect(route('dashboard'))->with("message","Certificate data updated. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 6);
        }
        return redirect()->back()->with("message","Failed to save the expense details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
