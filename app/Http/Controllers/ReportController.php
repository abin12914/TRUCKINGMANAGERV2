<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountStatementRequest;
use App\Http\Requests\CreditStatementRequest;
use App\Http\Requests\ProfitLossStatementRequest;
use App\Http\Requests\MilageStatementRequest;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TruckRepository;
use App\Repositories\FuelRefillRepository;
use Carbon\Carbon;
use Exception;

class ReportController extends Controller
{
    public $errorHead = null;

    public function __construct()
    {
        $this->errorHead = config('settings.controller_code.ReportController');
    }

    /**
     * Display a listing of the account statment.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountStatement(AccountStatementRequest $request, TransactionRepository $transactionRepo, AccountRepository $accountRepo)
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
            $account = $accountRepo->getAccounts($accountWhereParam, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], [], [], true);
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

            $transactions = $transactionRepo->getTransactions($whereParams, array_merge($debitParam, $creditParam), [], ['by' => 'transaction_date', 'order' => 'asc', 'num' => $noOfRecords], [], [], null, true);
            if($transactions->lastPage() == $transactions->currentPage()) {
                $subTotalDebit  = $transactionRepo->getTransactions($whereParams, $debitParam, [], [], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                $subTotalCredit = $transactionRepo->getTransactions($whereParams, $creditParam, [], [], ['key' => 'sum', 'value' => 'amount'], [], null, true);

                //old balance values
                if(!empty($fromDate)) {
                    $obDebit    = $transactionRepo->getTransactions($obWhereParams, $debitParam, [], [], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                    $obCredit   = $transactionRepo->getTransactions($obWhereParams, $creditParam, [], [], ['key' => 'sum', 'value' => 'amount'], [], null, true);
                }
            }
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);

            return redirect(route('dashboard'))->with("message","An unexpected error occured! Please try after sometime. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
    public function creditStatement(CreditStatementRequest $request, AccountRepository $accountRepo)
    {
        //caling base class fn for getting comapnysettings
        parent::companySettings();

        $totalDebit  = 0;
        $totalCredit = 0;
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
                'paramValue'    => array_search('Personal', config('constants.accountTypes')), //personal account=3
            ]
        ];


        try {
            if(!empty($request->get('relation_type'))) {
                $accounts = $accountRepo->getAccounts($accountWhereParam, [], [], ['by' => 'account_name', 'order' => 'asc', 'num' => null], [], $withParams, true);

                foreach ($accounts as $key => $account) {
                    $debitSum  = ($account->debitTransactionsSum->count() > 0 ? $account->debitTransactionsSum[0]->debit_sum : 0);
                    $creditSum = ($account->creditTransactionsSum->count() > 0 ? $account->creditTransactionsSum[0]->credit_sum : 0);
                    if($debitSum - $creditSum != 0) {
                        $account->creditAmount = $debitSum - $creditSum;
                        if($account->creditAmount > 0) {
                            $totalDebit += $account->creditAmount;
                        } else {
                            $totalCredit += $account->creditAmount * (-1);
                        }
                    } elseif($this->companySettings->show_zero_accounts != 1) {
                        $accounts->forget($key);
                    }
                }
            }
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            return redirect(route('dashboard'))->with("message","An unexpected error occured! Please try after sometime. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
        }

        //params passing for auto selection
        $params['to_date']['paramValue']       = $request->get('to_date');
        $params['relation_type']['paramValue'] = $request->get('relation_type');

        return view('reports.credit-statement',
            compact('accounts', 'params', 'totalDebit', 'totalCredit')
        );
    }

    /**
     * Display a profit-loss of a truck.
     *
     * @return \Illuminate\Http\Response
     */
    public function profitLossStatement(ProfitLossStatementRequest $request, TransactionRepository $transactionRepo, TruckRepository $truckRepo)
    {
        $transportationRentAmount = 0;
        $employeeWageAmount       = 0;
        $purchaseAmount           = 0;
        $saleAmount               = 0;
        $expenseAmount            = 0;

        if(!empty($request->get('from_date')) && !empty($request->get('to_date')) && !empty($request->get('truck_id'))) {
            //date format conversion
            $fromDate   = Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d');
            $toDate     = Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d');

            try {
                $truck = $truckRepo->getTruck($request->get('truck_id'), [], true);
                $transactions = $transactionRepo->getTransactionReport($fromDate, $toDate, $request->get('truck_id'));

                foreach ($transactions as $key => $transaction) {
                    //if rent transaction
                    if(!empty($transaction->transportation)) {
                        $transportationRentAmount += $transaction->amount;
                    }
                    //if employee wage transaction
                    if(!empty($transaction->employeeWage)) {
                        $employeeWageAmount += $transaction->amount;
                    }
                    //if purchase transaction
                    if($transaction->purchase) {
                        $purchaseAmount += $transaction->amount;
                    }
                    //if sale transaction
                    if($transaction->sale) {
                        $saleAmount += $transaction->amount;
                    }
                    //if expense transaction
                    if(!empty($transaction->expense)) {
                        $expenseAmount += $transaction->amount;
                    }
                }
            } catch (\Exception $e) {
                $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);

                return redirect(route('dashboard'))
                    ->with("message","An unexpected error occured! Please try after sometime. #". $this->errorHead. "/". $errorCode)
                    ->with("alert-class", "error");
            }
        }

        //params passing for auto selection
        $params['from_date']['paramValue']  = $request->get('from_date');
        $params['to_date']['paramValue']    = $request->get('to_date');
        $params['truck_id']['paramValue']   = $request->get('truck_id');

        return view('reports.profit-loss-statement',
            compact('truck', 'params', 'transportationRentAmount', 'employeeWageAmount', 'purchaseAmount', 'saleAmount', 'expenseAmount')
        );
    }

    /**
     * Display a milage of a truck.
     *
     * @return \Illuminate\Http\Response
     */
    public function milageStatement(MilageStatementRequest $request, FuelRefillRepository $fuelRefillRepo, TruckRepository $truckRepo)
    {
        $truck = [];
        $fuelRefillRecords = [];
        $previousReading = null;

        if(!empty($request->get('from_date')) && !empty($request->get('to_date')) && !empty($request->get('truck_id'))) {
            //date format conversion
            $fromDate   = Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d');
            $toDate     = Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d');

            $whereParams = [
                'from_date' => [
                    'paramName'     => 'refill_date',
                    'paramOperator' => '>=',
                    'paramValue'    => $fromDate,
                ],
                'to_date' => [
                    'paramName'     => 'refill_date',
                    'paramOperator' => '<=',
                    'paramValue'    => $toDate,
                ],
                'truck_id' => [
                    'paramName'     => 'truck_id',
                    'paramOperator' => '=',
                    'paramValue'    => $request->get('truck_id'),
                ],
            ];

            try {
                //$truck = $truckRepo->getTruck($request->get('truck_id'), [], true);
                $fuelRefillRecords = $fuelRefillRepo->getFuelRefills($whereParams, [], [], ['by' => 'odometer_reading', 'order' => 'asc', 'num' => 31], ['key' => null, 'value' => null], [], true);

                foreach ($fuelRefillRecords as $key => $fuelRefill) {
                    if(!empty($fuelRefill->odometer_reading)) {
                        if(!empty($previousReading)) {
                            $kmTravelled = $fuelRefill->odometer_reading - $previousReading;
                            $fuelRefill->milage = round($kmTravelled / $fuelRefill->fuel_quantity, 2);
                        }
                        $previousReading = $fuelRefill->odometer_reading;
                    }
                }
            } catch (\Exception $e) {
                $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);

                return redirect(route('dashboard'))
                    ->with("message","An unexpected error occured! Please try after sometime. #". $this->errorHead. "/". $errorCode)
                    ->with("alert-class", "error");
            }
        }

        //params passing for auto selection
        $params['from_date']['paramValue']  = $request->get('from_date');
        $params['to_date']['paramValue']    = $request->get('to_date');
        $params['truck_id']['paramValue']   = $request->get('truck_id');

        return view('reports.milage-statement',
            compact('truck', 'params', 'fuelRefillRecords')
        );
    }
}
