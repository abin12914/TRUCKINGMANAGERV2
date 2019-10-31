<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
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
        $params['account_id']['paramValue'] = $accountId ?? $account->id; //setting default to cash if no account is selected

        return view('reports.account-statement',
            compact('account', 'transactions', 'subTotalDebit', 'subTotalCredit', 'obDebit', 'obCredit', 'params', 'noOfRecords')
        );
    }
}
