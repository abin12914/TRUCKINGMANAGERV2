<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VoucherRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\VoucherRegistrationRequest;
use App\Http\Requests\VoucherFilterRequest;
use Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoucherController extends Controller
{
    protected $voucherRepo;
    public $errorHead = null;

    public function __construct(VoucherRepository $voucherRepo)
    {
        $this->voucherRepo = $voucherRepo;
        $this->errorHead   = config('settings.controller_code.VoucherController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VoucherFilterRequest $request)
    {
        $errorCode          = 0;
        $whereParams        = [];
        $totalDebitAmount   = 0;
        $totalCreditAmount  = 0;

        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');

        //date format conversion
        $fromDate    = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : null;
        $toDate      = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : null;

        $whereParams = [
            'transaction_type_debit' =>  [
                'paramName'     => 'transaction_type',
                'paramOperator' => '=',
                'paramValue'    => $request->get('transaction_type'),
            ]
        ];

        $debitAccountParam = [
            'debit_account_id' => [
                'relation'      => 'transaction',
                'paramName'     => 'debit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('account_id'),
            ],
        ];

        $creditAccountParam = [
            'credit_account_id' => [
                'relation'      => 'transaction',
                'paramName'     => 'credit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('account_id'),
            ],
        ];

        $relationalParams = [
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

        $relationalOrParams = [
            'account_id' => [
                'relation' => 'transaction',
                'params'   => array_merge($creditAccountParam, $debitAccountParam)
            ]
        ];

        try {
            $vouchers = $this->voucherRepo->getVouchers($whereParams, [], $relationalParams, $relationalOrParams, ['by' => 'voucher_date', 'order' => 'asc', 'num' => $noOfRecordsPerPage], [], ['transaction'], true);

            if($vouchers->lastPage() == $vouchers->currentPage()) {
                $allVouchers = $this->voucherRepo->getVouchers($whereParams, [], $relationalParams, $relationalOrParams, ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true);
                if(!empty($allVouchers)) {
                    $totalDebitAmount   = $allVouchers->where('transaction_type', 1)->sum('amount');
                    $totalCreditAmount  = $allVouchers->where('transaction_type', 2)->sum('amount');
                }
            }
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);

            return redirect(route('dashboard'))->with("message","Failed to get the voucher list. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
        }

        //params passing for auto selection
        $whereParams['from_date']['paramValue']         = $request->get('from_date');
        $whereParams['to_date']['paramValue']           = $request->get('to_date');
        $whereParams['account_id']['paramValue']        = $request->get('account_id');
        $whereParams['transaction_type']['paramValue']  = $request->get('transaction_type');

        return view('vouchers.list', [
            'vouchers'          => $vouchers,
            'totalDebitAmount'  => $totalDebitAmount,
            'totalCreditAmount' => $totalCreditAmount,
            'params'            => $whereParams,
            'noOfRecords'       => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vouchers.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        VoucherRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id=null
    ) {
        $errorCode = 0;
        $voucher   = null;

        $transactionDate = Carbon::createFromFormat('d-m-Y', $request->get('transaction_date'))->format('Y-m-d');
        $transactionType = $request->get('transaction_type');
        $debitAccountId  = $request->get('debit_account_id');
        $creditAccountId = $request->get('credit_account_id');
        $description     = $request->get('description');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if($transactionType == 1) {//cash voucher - reciept
                $orWhereParams = [
                    'account_name' => [
                        'paramName'     => 'account_name',
                        'paramOperator' => '=',
                        'paramValue'    => "Cash",
                    ],
                    'id' => [
                        'paramName'     => 'id',
                        'paramOperator' => '=',
                        'paramValue'    => $creditAccountId,
                    ]
                ];
            } elseif($transactionType == 2) { //cash voucher - payment
                $orWhereParams = [
                    'account_name' => [
                        'paramName'     => 'account_name',
                        'paramOperator' => '=',
                        'paramValue'    => "Cash",
                    ],
                    'id' => [
                        'paramName'     => 'id',
                        'paramOperator' => '=',
                        'paramValue'    => $debitAccountId,
                    ]
                ];
            } else {//secondary voucher
                $orWhereParams = [
                    'debit_account_id' => [
                        'paramName'     => 'id',
                        'paramOperator' => '=',
                        'paramValue'    => $debitAccountId,
                    ],
                    'credit_account_id' => [
                        'paramName'     => 'id',
                        'paramOperator' => '=',
                        'paramValue'    => $creditAccountId,
                    ]
                ];
            }

            //confirming account exist-ency.
            $baseAccounts   = $accountRepo->getAccounts([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [],true);
            if($baseAccounts->count() != 2) {
                throw new TMException("CustomError", 500);
            }
            if($transactionType == 1) {
                //Receipt : Debit cash account - Credit giver account
                $debitAccount   = $baseAccounts->firstWhere('account_name', '=', 'Cash');
                $creditAccount  = $baseAccounts->firstWhere('id', '=', $creditAccountId);
                $particulars    = $description. "[Cash received from ". $creditAccount->account_name. "]";
            } elseif($transactionType == 2) {
                //Payment : Debit receiver account - Credit cash account
                $creditAccount  = $baseAccounts->firstWhere('account_name', '=', 'Cash');
                $debitAccount   = $baseAccounts->firstWhere('id', '=', $debitAccountId);
                $particulars    = $description. "[Cash paid to ". $debitAccount->account_name. "]";
            } else {
                //Credit Voucher : Debit receiver account - Credit payer account
                $debitAccount  = $baseAccounts->firstWhere('id', '=', $debitAccountId);
                $creditAccount = $baseAccounts->firstWhere('id', '=', $creditAccountId);
                $particulars   = $description. "[From : ". $creditAccount->account_name. " To : ". $debitAccount->account_name. "]";
            }

            if(!empty($id)) {
                $voucher = $this->voucherRepo->getVoucher($id, [], false);
            }

            //save voucher transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $debitAccount->id,
                'credit_account_id' => $creditAccount->id,
                'amount'            => $request->get('amount'),
                'particulars'       => $particulars,
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($voucher) ? $voucher->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to voucher table
            $voucherResponse = $this->voucherRepo->saveVoucher([
                'voucher_date'      => $transactionDate,
                'transaction_id'    => $transactionResponse['transaction']->id,
                'transaction_type'  => $transactionType,
                'amount'            => $request->get('amount'),
                'status'            => 1,
            ], $id);

            if(!$voucherResponse['flag']) {
                throw new TMException("CustomError", $voucherResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'voucher' => $voucherResponse['voucher']
                ];
            }

            return redirect(route('vouchers.index'))->with("message","Voucher details saved successfully. #". $transactionResponse['transaction']->id)->with("alert-class", "success");
        } catch (\Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);
        }
        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        return redirect()->back()->with("message","Failed to save the voucher details. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $voucher    = [];

        try {
            $voucher = $this->voucherRepo->getVoucher($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.details', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voucher    = [];

        try {
            $voucher = $this->voucherRepo->getVoucher($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.edit-add', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        VoucherRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $accountRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('vouchers.index'))->with("message","Vouchers details updated successfully. #". $updateResponse['voucher']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the vouchers details. #". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
            $deleteResponse = $this->voucherRepo->deleteVoucher($id, false);

            if(!$deleteResponse['flag']) {
                throw new TMException("CustomError", $deleteResponse['errorCode']);
            }

            DB::commit();
            return redirect(route('vouchers.index'))->with("message","Voucher details deleted successfully.")->with("alert-class", "success");
        } catch (\Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 5);
        }

        return redirect()->back()->with("message","Failed to delete the voucher details. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
