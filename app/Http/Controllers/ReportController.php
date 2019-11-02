<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TruckRepository;
use Carbon\Carbon;
use Exception;

class ReportController extends Controller
{
    public $errorHead = null;

    public function __construct()
    {
        $this->errorHead = config('settings.controllerCode.ReportController');
    }

    /**
     * Display a listing of the account statment.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountStatement(Request $request, TransactionRepository $transactionRepo, AccountRepository $accountRepo)
    {
        $accountWhereParam = [];

        $subTotalDebit  = 0;
        $subTotalCredit = 0;
        $obDebit  = 0;
        $obCredit = 0;

        $noOfRecords = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');

        //date format conversion
        $fromDate   = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : null;
        $toDate     = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : null;
        $accountId  = $request->get('account_id');

        $whereParams = [
            'from_date' => [
                'paramName'     => 'transaction_date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date' => [
                'paramName'     => 'transaction_date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
        ];

        $obWhereParams = [
            'ob_up_to_date' => [
                'paramName'     => 'transaction_date',
                'paramOperator' => '<',
                'paramValue'    => $fromDate,
            ]
        ];

        if(!empty($accountId)) {
            $accountWhereParam = [
                'account_id' => [
                    'paramName'     => 'id',
                    'paramOperator' => '=',
                    'paramValue'    => $accountId,
                ]
            ];
        } else {
            $accountWhereParam = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Cash",
                ],
            ];
        }

        try {
            $account = $accountRepo->getAccounts($accountWhereParam, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], [], true);
            $accountId = $accountId ?? $account->id; //setting default to cash if no account is selected

            $debitParam = [
                'account_id_debit' => [
                    'paramName'     => 'debit_account_id',
                    'paramOperator' => '=',
                    'paramValue'    => $accountId,
                ]
            ];

            $creditParam = [
                'account_id_credit' => [
                    'paramName'     => 'credit_account_id',
                    'paramOperator' => '=',
                    'paramValue'    => $accountId,
                ]
            ];

            $transactions = $transactionRepo->getTransactions($whereParams, array_merge($debitParam, $creditParam), [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecords], [], [], null, true);
            if($transactions->lastPage() || $transactions->lastPage() == 1) {
                $subTotalDebit  = $transactionRepo->getTransactions($whereParams, $debitParam, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                $subTotalCredit = $transactionRepo->getTransactions($whereParams, $creditParam, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => 'sum', 'value' => 'amount'], [], null, true);

                //old balance values
                if(!empty($fromDate)) {
                    $obDebit    = $transactionRepo->getTransactions($obWhereParams, $debitParam, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                    $obCredit   = $transactionRepo->getTransactions($obWhereParams, $creditParam, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                }
            }
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);

            return redirect()->back()->with("message","An unexpected error occured! Please try after sometime. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
        }

        //params passing for auto selection
        $params['from_date']['paramValue']  = $request->get('from_date');
        $params['to_date']['paramValue']    = $request->get('to_date');
        $params['account_id']['paramValue'] = $accountId;

        return view('reports.account-statement',
            compact('account', 'transactions', 'subTotalDebit', 'subTotalCredit', 'obDebit', 'obCredit', 'params', 'noOfRecords')
        );
    }

    /**
     * Display a listing of the accounts  and their credit statment.
     *
     * @return \Illuminate\Http\Response
     */
    public function creditStatement(Request $request, AccountRepository $accountRepo)
    {
        $accountWhereParam  = [];
        $withParams         = ['debitTransactionsSum', 'creditTransactionsSum'];

        if(!empty($toDate)) {
            //date format conversion
            $toDate = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : Carbon::now()->format('Y-m-d');

            $withParams = [
                'debitTransactionsSum' => function ($query) use($toDate) {
                    $query->where('transaction_date', '<=', $toDate);
                },
                'creditTransactionsSum' => function ($query) use($toDate) {
                    $query->where('transaction_date', '<=', $toDate);
                }
            ];
        }

        $accountWhereParam = [
            'relation_type' => [
                'paramName'     => 'relation',
                'paramOperator' => '=',
                'paramValue'    => $request->get('relation_type'),
            ],
            'type' => [
                'paramName'     => 'type',
                'paramOperator' => '=',
                'paramValue'    => 3, //personal accounts
            ]
        ];

        try {
            if(!empty($request->get('relation_type'))) {
                $accounts = $accountRepo->getAccounts($accountWhereParam, [], [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], $withParams, true);

                foreach ($accounts as $key => $account) {
                    $debitSum  = ($account->debitTransactionsSum->count() > 0 ? $account->debitTransactionsSum[0]->debit_sum : 0);
                    $creditSum = ($account->creditTransactionsSum->count() > 0 ? $account->creditTransactionsSum[0]->credit_sum : 0);
                    $account->creditAmount = $debitSum - $creditSum;
                }
            }
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            return redirect()->back()->with("message","An unexpected error occured! Please try after sometime. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
        }

        //params passing for auto selection
        $params['to_date']['paramValue']       = $request->get('to_date');
        $params['relation_type']['paramValue'] = $request->get('relation_type');

        return view('reports.credit-statement',
            compact('accounts', 'params')
        );
    }

    /**
     * Display a profit-loss of a truck.
     *
     * @return \Illuminate\Http\Response
     */
    public function profitLossStatement(Request $request, TruckRepository $truckRepo)
    {
        $whereParam = [];
        $withParams = ['transportations.transaction', 'employeeWages.transaction', 'expenses.transaction', 'purchases.transaction', 'sales.transaction'];

        //date format conversion
        $fromDate   = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : null;
        $toDate     = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : null;
        $truckId    = $request->get('truck_id');

        $dateParams = [
            'from_date' => [
                'paramName'     => 'transaction_date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date' => [
                'paramName'     => 'transaction_date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
        ];

        $whereParam = [
            'truck_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $truckId,
            ]
        ];

        try {
            $truck = $truckRepo->getTrucks($whereParam, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], $withParams, true);
            dd($truck);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);

            return redirect()->back()
                ->with("message","An unexpected error occured! Please try after sometime. Error Code : ". $this->errorHead. "/". $errorCode)
                ->with("alert-class", "error");
        }

        //params passing for auto selection
        $params['from_date']['paramValue']  = $request->get('from_date');
        $params['to_date']['paramValue']    = $request->get('to_date');
        $params['truck_id']['paramValue']   = $truckId;

        return view('reports.profit-loss-statement', compact('trucks'));
    }
}
