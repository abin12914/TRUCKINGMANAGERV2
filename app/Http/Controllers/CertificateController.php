<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $truckRepo;
    public $errorHead = null;

    public function __construct(TruckRepository $truckRepo)
    {
        $this->truckRepo = $truckRepo;
        $this->errorHead   = config('settings.controllerCode.CertificateController');
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
                'paramValue'    => 3,//personal account
            ],
        ];

        $orWhereParams = [
            'search_by_a_name' => [
                'paramName'     => 'account_name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('search_by_name'). "%"),
            ],
            'search_by_name' => [
                'paramName'     => 'name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('search_by_name'). "%"),
            ]
        ];

        //remove %% from sending back to result
        $params['search_by_name']['paramValue'] = $request->get('search_by_name');

        //getAccounts($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)
        return view('accounts.list', [
            'accounts'      => $this->accountRepo->getAccounts($whereParams, $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], ['key' => null, 'value' => null], [], true),
            'params'        => array_merge($whereParams,$params),
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
        return view('accounts.edit-add');
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

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $whereParams = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Account-Opening-Balance",
                ]
            ];
            //confirming opening balance existency.
            $openingBalanceAccountId = $this->accountRepo->getAccounts($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], [], true)->id;

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
                'type'              => 3, //personal account type
                'relation'          => $request->get('relation_type'),
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'status'            => 1
            ], $id);

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
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
                'amount'            => $openingBalance,
                'particulars'       => $particulars,
                'status'            => $openingBalance > 0 ? 1 : 0,
                'created_by'        => Auth::id(),
            ], $openingTransactionId);

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'account' => $accountResponse['account'],
                ];
            }
            return redirect(route('accounts.show', $accountResponse['account']->id))->with("message","Account details saved successfully. Reference Number : ". $accountResponse['account']->id)->with("alert-class", "success");
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
}
