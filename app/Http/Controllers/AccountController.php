<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\AccountRegistrationRequest;
use App\Http\Requests\AccountFilterRequest;
use \Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountController extends Controller
{
    protected $accountRepo;
    public $errorHead = null;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->errorHead   = config('settings.controllerCode.AccountController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AccountFilterRequest $request)
    {
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');

        $whereParams = [
            'relation_type' => [
                'paramName'     => 'relation',
                'paramOperator' => '=',
                'paramValue'    => $request->get('relation_type'),
            ],
            'account_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('account_id'),
            ],
            'type' => [
                'paramName'     => 'type',
                'paramOperator' => '=',
                'paramValue'    => 3,
            ],
        ];

        $orWhereParams = [
            'account_name' => [
                'paramName'     => 'account_name',
                'paramOperator' => 'LIKE',
                'paramValue'    => $request->get('account_name'),
            ],
            'name' => [
                'paramName'     => 'name',
                'paramOperator' => 'LIKE',
                'paramValue'    => $request->get('account_name'),
            ]
        ];

        //getAccounts($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)

        return view('accounts.list', [
            'accounts'      => $this->accountRepo->getAccounts($whereParams, $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], ['key' => null, 'value' => null], [], true),
            /*'relationTypes' => config('constants.accountRelationTypes'),*/
            'params'        => $whereParams,
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
        return view('accounts.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        AccountRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $errorCode            = 0;
        $account              = null;
        $openingTransactionId = null;

        $openingBalanceAccountId = config('constants.accountConstants.AccountOpeningBalance.id');

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();
            //confirming opening balance existency.
            //getAccount($id, $withParams=[], $activeFlag=true)
            $openingBalanceAccount = $this->accountRepo->getAccount($openingBalanceAccountId, [], false);

            if(!empty($id)) {
                $account = $this->accountRepo->getAccount($id, [], false);

                if($account->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $account->id],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $account->id],
                    ];
                }

                //getTransactions($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$relation,$activeFlag=true)
                $openingTransactionId = $transactionRepo->getTransactions($searchTransaction, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], [], [], null, false)->id;
            }

            //save to account table
            $accountResponse   = $this->accountRepo->saveAccount([
                'account_name'      => $request->get('account_name'),
                'description'       => $request->get('description'),
                'type'              => array_search('Personal', (config('constants.accountTypes'))),
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

            if(!$accountResponse['flag']) {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
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
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
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
                    'account' => $accountResponse['account'],
                ];
            }
            return redirect(route('account.show', $accountResponse['account']->id))->with("message","Account details saved successfully. Reference Number : ". $accountResponse['account']->id)->with("alert-class", "success");
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
        
        return redirect()->back()->with("message","Failed to save the account details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $account    = [];

        try {
            $account = $this->accountRepo->getAccount($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing model not found exception when no model is fetched
            throw new ModelNotFoundException("Account", $errorCode);
        }

        return view('accounts.details', [
            'account'       => $account,
            'relationTypes' => config('constants.accountRelationTypes'),
            'accountTypes'  => config('constants.$accountTypes'),
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
        $errorCode  = 0;
        $account    = [];

        /*$employeeRelationType = array_search('Employees', config('constants.accountRelationTypes'));*/ //employee -> [index = 1]
        //excluding the relationtype 'employee'[index = 1] for account update
        /*unset($relationTypes[$employeeRelationType]);*/

        try {
            $account = $this->accountRepo->getAccount($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Account", $errorCode);
        }

        return view('accounts.edit', [
            'account'       => $account
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
        AccountRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('account.show', $updateResponse['account']->id))->with("message","Account details updated successfully. Updated Record Number : ". $updateResponse['account']->id)->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the account details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }
}
