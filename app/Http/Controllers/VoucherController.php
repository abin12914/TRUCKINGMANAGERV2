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
        $totalDebitAmount   = 0;
        $totalCreditAmount  = 0;

        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');
        $transactionType    = $request->get('transaction_type');
        //date format conversion
        $fromDate    = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : null;
        $toDate      = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : null;

        $debitVoucherTypeWhere = [
            'transaction_type'  =>  [
                'paramName'     => 'transaction_type',
                'paramOperator' => '=',
                'paramValue'    => 1,
            ]
        ];

        $creditVoucherTypeWhere = [
            'transaction_type'  =>  [
                'paramName'     => 'transaction_type',
                'paramOperator' => '=',
                'paramValue'    => 2,
            ]
        ];

        $whereParams = [
            'transaction_type'  =>  [
                'paramName'     => 'transaction_type',
                'paramOperator' => '=',
                'paramValue'    => $transactionType,
            ]
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
            'account_id'    =>  [
                'relation' => 'transaction',
                'params'   => [
                    'debit_account_id' => [
                        'paramName'     => 'debit_account_id',
                        'paramOperator' => '=',
                        'paramValue'    => $request->get('account_id'),
                    ],
                    'credit_account_id' => [
                        'paramName'     => 'credit_account_id',
                        'paramOperator' => '=',
                        'paramValue'    => $request->get('account_id'),
                    ],
                ]
            ]
        ];

        //getVouchers($whereParams=[],$orWhereParams=[],$relationalParams=[],$relationalOrParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
        $vouchers = $this->voucherRepo->getVouchers($whereParams, [], $relationalParams, $relationalOrParams, ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], [], [], true);

        $totalVoucher = $this->voucherRepo->getVouchers($whereParams, [], $relationalParams, $relationalOrParams, [], ['key' => 'sum', 'value' => 'amount'], [], true);

        //params passing for auto selection
        $relationalParams['from_date']['paramValue'] = $request->get('from_date');
        $relationalParams['to_date']['paramValue']   = $request->get('to_date');

        //getVouchers($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $withParams=[],$activeFlag=true)
        return view('vouchers.list', [
            'vouchers'     => $vouchers,
            'totalVoucher' => $totalVoucher,
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
        return view('vouchers.register');
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

        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('transaction_date'))->format('Y-m-d');
        $totalBill          = $request->get('amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $whereParams = [
                'account_name' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Service-And-Vouchers",
                ]
            ];
            //confirming voucher account exist-ency.
            $voucherAccountId = $accountRepo->getAccounts($whereParams,$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => 1], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)->id;
            if(!empty($id)) {
                $voucher = $this->voucherRepo->getVoucher($id, [], false);
            }

            //save voucher transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $voucherAccountId, // debit the voucher account
                'credit_account_id' => $request->get('account_id'), // credit the supplier
                'amount'            => $totalBill,
                'particulars'       => $request->get('description')."[Purchase & Voucher]",
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($voucher) ? $voucher->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to voucher table
            $voucherResponse = $this->voucherRepo->saveVoucher([
                'transaction_id'    => $transactionResponse['transaction']->id,
                'truck_id'          => $request->get('truck_id'),
                'service_id'        => $request->get('service_id'),
                'description'       => $request->get('description'),
                'amount'            => $totalBill,
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

            return redirect(route('vouchers.index'))->with("message","Voucher details saved successfully. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the voucher details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.details', [
            'voucher' => $voucher,
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
        $voucher    = [];

        try {
            $voucher = $this->voucherRepo->getVoucher($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.edit', [
            'voucher' => $voucher,
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
        VoucherRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $accountRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('vouchers.index'))->with("message","Vouchers details updated successfully. Updated Record Number : ". $updateResponse['voucher']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the vouchers details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the voucher details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
