<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SupplyTransportationRepository;
use App\Repositories\TransportationRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeWageRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use App\Http\Requests\TransportationRegistrationRequest;
use App\Http\Requests\TransportationFilterRequest;
use App\Http\Requests\TransportationAjaxRequests;
use \Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplyTransportationController extends Controller
{
    public $errorHead = null, $driverWageType = 1; //array_search('Per Trip [%]', config('constants.wageTypes'));;

    public function __construct()
    {
        $this->errorHead   = config('settings.controller_code.SupplyTransportationController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransportationFilterRequest $request, SupplyTransportationRepository $transportationRepo)
    {
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');
        //date format conversion
        $fromDate   = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate     = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";

        $whereParams = [
            'from_date' => [
                'paramName'     => 'date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date' => [
                'paramName'     => 'date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
            'truck_id' => [
                'paramName'     => 'truck_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_id'),
            ],
            'source_id' => [
                'paramName'     => 'source_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('source_id'),
            ],
            'destination_id' => [
                'paramName'     => 'destination_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('destination_id'),
            ],
            'material_id' => [
                'paramName'     => 'material_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('material_id'),
            ],
        ];

        $relationalParams = [
            'contractor_account_id' => [
                'relation'      => 'transaction',
                'paramName'     => 'debit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('contractor_account_id'),
            ],
            'driver_id' => [
                'relation'      => 'employeeWages',
                'paramName'     => 'employee_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('driver_id'),
            ],
        ];

        $transportations = $transportationRepo->getSupplyTransportations(
            $whereParams, [], $relationalParams, $orderBy=['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], $aggregates=['key' => null, 'value' => null], $withParams=['truck', 'transaction.debitAccount', 'source', 'destination', 'material', 'employeeWages.employee.account'], $activeFlag=true
        );

        //params passing for auto selection
        $whereParams['from_date']['paramValue'] = $request->get('from_date');
        $whereParams['to_date']['paramValue']   = $request->get('to_date');
        $params = array_merge($whereParams, $relationalParams);

        return view('supply.list', [
            'transportations'       => $transportations,
            'params'                => $params,
            'noOfRecordsPerPage'    => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supply.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        TransportationRegistrationRequest $request,
        TransportationRepository $transportationRepo,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        EmployeeWageRepository $employeeWageRepo,
        EmployeeRepository $employeeRepo,
        PurchaseRepository $purchaseRepo,
        SaleRepository $saleRepo,
        $id=null
    ) {
        $errorCode          = 0;
        $transportation     = null;
        $driver             = null;
        $driverWage         = null;
        $purchase           = null;
        $sale               = null;

        $transportationDate = Carbon::createFromFormat('d-m-Y', $request->get('transportation_date'))->format('Y-m-d');
        $purchaseDate       = Carbon::createFromFormat('d-m-Y', $request->get('purchase_date'))->format('Y-m-d');
        $saleDate           = Carbon::createFromFormat('d-m-Y', $request->get('sale_date'))->format('Y-m-d');
        $driverId           = $request->get('driver_id');
        $description        = $request->get('description');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $orWhereParams = [
                'transportation_rent' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Transportation-Rent",
                ],
                'employee_wage' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Employee-Wage",
                ],
                'purchase' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Purchases",
                ],
                'sale' => [
                    'paramName'     => 'account_name',
                    'paramOperator' => '=',
                    'paramValue'    => "Sales",
                ],
            ];
            //confirming transportation rent account, employee wage, purchse and sale account exist-ency.
            $baseAccounts = $accountRepo->getAccounts([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], [], true);
            if($baseAccounts->count() < 4)
            {
                throw new TMException("CustomError", 1);
            }
            $transportationRentAccountId = $baseAccounts->firstWhere('account_name', 'Transportation-Rent')->id;
            $employeeWageAccountId       = $baseAccounts->firstWhere('account_name', 'Employee-Wage')->id;
            $purchaseAccountId           = $baseAccounts->firstWhere('account_name', 'Purchases')->id;
            $saleAccountId               = $baseAccounts->firstWhere('account_name', 'Sales')->id;

            $whereParams = [
                'driver_id' => [
                    'paramName'     => 'id',
                    'paramOperator' => '=',
                    'paramValue'    => $driverId,
                ]
            ];
            $driver = $employeeRepo->getEmployees($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], ['key' => null, 'value' => null], $withParams=[], true);

            //if editing
            if(!empty($id)) {
                $transportation = $transportationRepo->getTransportation($id, ['employeeWages', 'purchase', 'sale'], false);
                $driverWage     = $transportation->employeeWages->firstWhere('wage_type', $this->driverWageType);
                $purchase       = $transportation->purchase;
                $sale           = $transportation->sale;
            }

            //transportation

            //save transportation transaction to table
            $transactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $transportationDate,
                'debit_account_id'  => $request->get('contractor_account_id'), // debit the contractor
                'credit_account_id' => $transportationRentAccountId, // credit the transportation rent account
                'amount'            => $request->get('total_rent'),
                'particulars'       => ("Transportation Rent of ". $request->get('no_of_trip'). " trip. ". $description),
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($transportation) ? $transportation->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to transportation table
            $transportationResponse = $transportationRepo->saveTransportation([
                'transaction_id'    => $transactionResponse['transaction']->id,
                'truck_id'          => $request->get('truck_id'),
                'source_id'         => $request->get('source_id'),
                'destination_id'    => $request->get('destination_id'),
                'material_id'       => $request->get('material_id'),
                'rent_type'         => $request->get('rent_type'),
                'measurement'       => $request->get('rent_measurement'),
                'rent_rate'         => $request->get('rent_rate'),
                'trip_rent'         => $request->get('trip_rent'),
                'no_of_trip'        => $request->get('no_of_trip'),
                'total_rent'        => $request->get('total_rent'),
                'status'            => 1,
            ], $id);

            if(!$transportationResponse['flag']) {
                throw new TMException("CustomError", $transportationResponse['errorCode']);
            }

            //driver wage

            //save driver wage transaction to table
            $wageTransactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $transportationDate,
                'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                'credit_account_id' => $driver->account_id, // credit the driver account
                'amount'            => $request->get('driver_total_wage'),
                'particulars'       => "Wage of ". $request->get('no_of_trip'). ' trips.',
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($driverWage) ? $driverWage->transaction_id : null));

            if(!$wageTransactionResponse['flag']) {
                throw new TMException("CustomError", $wageTransactionResponse['errorCode']);
            }

            //save to driver wage table
            $driverWageResponse = $employeeWageRepo->saveEmployeeWage([
                'transaction_id'    => $wageTransactionResponse['transaction']->id,
                'employee_id'       => $driverId,
                'wage_type'         => $this->driverWageType,
                'to_date'           => $transportationDate,
                'transportation_id' => $transportationResponse['transportation']->id,
                'wage_amount'       => $request->get('driver_wage'),
                'no_of_trip'        => $request->get('no_of_trip'),
                'total_wage_amount' => $request->get('driver_total_wage'),
                'status'            => 1,
            ], (!empty($driverWage) ? $driverWage->id : null));

            if(!$driverWageResponse['flag']) {
                throw new TMException("CustomError", $driverWageResponse['errorCode']);
            }

            //purchase

            //save purchase to transaction to table
            $purchaseTransactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $purchaseDate,
                'debit_account_id'  => $purchaseAccountId, // debit the purchase account
                'credit_account_id' => $request->get('supplier_account_id'), // credit the driver account
                'amount'            => $request->get('purchase_total_bill'),
                'particulars'       => "Purchase : ". $request->get('no_of_trip'). ' trips.',
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($purchase) ? $purchase->transaction_id : null));

            if(!$purchaseTransactionResponse['flag']) {
                throw new TMException("CustomError", $purchaseTransactionResponse['errorCode']);
            }

            //save to purchase table
            $purchaseResponse = $purchaseRepo->savePurchase([
                'transaction_id'    => $purchaseTransactionResponse['transaction']->id,
                'transportation_id' => $transportationResponse['transportation']->id,
                'measure_type'      => $request->get('purchase_measure_type'),
                'quantity'          => $request->get('purchase_quantity'),
                'rate'              => $request->get('purchase_rate'),
                'discount'          => $request->get('purchase_discount'),
                'purchase_trip_bill'=> $request->get('purchase_trip_bill'),
                'no_of_trip'        => $request->get('no_of_trip'),
                'total_amount'      => $request->get('purchase_total_bill'),
                'status'            => 1,
            ], (!empty($purchase) ? $purchase->id : null));

            if(!$purchaseResponse['flag']) {
                throw new TMException("CustomError", $purchaseResponse['errorCode']);
            }

            //sale

            //save sale to transaction to table
            $saleTransactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $saleDate,
                'debit_account_id'  => $request->get('supplier_account_id'), // debit the driver account
                'credit_account_id' => $saleAccountId, // credit the sale account
                'amount'            => $request->get('sale_total_bill'),
                'particulars'       => "Sale : ". $request->get('no_of_trip'). ' trips.',
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($sale) ? $sale->transaction_id : null));

            if(!$saleTransactionResponse['flag']) {
                throw new TMException("CustomError", $saleTransactionResponse['errorCode']);
            }

            //save to sale table
            $saleResponse = $saleRepo->saveSale([
                'transaction_id'    => $saleTransactionResponse['transaction']->id,
                'transportation_id' => $transportationResponse['transportation']->id,
                'measure_type'      => $request->get('sale_measure_type'),
                'quantity'          => $request->get('sale_quantity'),
                'rate'              => $request->get('sale_rate'),
                'discount'          => $request->get('sale_discount'),
                'sale_trip_bill'    => $request->get('sale_trip_bill'),
                'no_of_trip'        => $request->get('no_of_trip'),
                'total_amount'      => $request->get('sale_total_bill'),
                'status'            => 1,
            ], (!empty($sale) ? $sale->id : null));

            if(!$saleResponse['flag']) {
                throw new TMException("CustomError", $saleResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'transportation' => $transportationResponse['transportation']
                ];
            }

            return redirect(route('supply.index'))->with("message","Supply details saved successfully. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the supply details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, SupplyTransportationRepository $transportationRepo)
    {
        $errorCode      = 0;
        $transportation = [];

        try {
            $transportation = $transportationRepo->getSupplyTransportation($id, ['truck', 'transaction.debitAccount', 'source', 'destination', 'material', 'employeeWages.employee.account'], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Transportation", $errorCode);
        }

        return view('supply.details', [
            'supplyTransportation' => $transportation,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, TransportationRepository $transportationRepo)
    {
        $errorCode      = 0;
        $transportation = [];

        try {
            $transportation = $transportationRepo->getTransportation($id, ['employeeWages', 'purchase.transaction', 'sale.transaction'], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Transportation", $errorCode);
        }

        return view('supply.edit', [
            'transportation' => $transportation,
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
        TransportationRegistrationRequest $request,
        TransportationRepository $transportationRepo,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        EmployeeWageRepository $employeeWageRepo,
        EmployeeRepository $employeeRepo,
        PurchaseRepository $purchaseRepo,
        SaleRepository $saleRepo,
        $id=null
    ){
        $updateResponse = $this->store($request, $transportationRepo, $transactionRepo, $accountRepo, $employeeWageRepo, $employeeRepo, $purchaseRepo, $saleRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('supply.index'))->with("message","Supply details updated successfully. Updated Record Number : ". $updateResponse['transportation']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the supply details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        TransportationRepository $transportationRepo,
        PurchaseRepository $purchaseRepo,
        SaleRepository $saleRepo,
        EmployeeWageRepository $employeeWageRepo,
        $id
    ){
        $errorCode  = 0;

        //wrapping db transactions
        DB::beginTransaction();
        try {
            $transportation = $transportationRepo->getTransportation($id, ['employeeWages', 'purchase', 'sale'], false);
            $employeeWage   = $transportation->employeeWages()->first();
            $purchase       = $transportation->purchase;
            $sale           = $transportation->sale;

            $deleteEmployeeWage = $employeeWageRepo->deleteEmployeeWage($employeeWage->id, false);

            if(!$deleteEmployeeWage['flag']) {
                throw new TMException("CustomError", $deleteEmployeeWage['errorCode']);
            }

            $deleteSale = $saleRepo->deleteSale($sale->id, false);

            if(!$deleteSale['flag']) {
                throw new TMException("CustomError", $deleteSale['errorCode']);
            }

            $deletePurchase = $purchaseRepo->deletePurchase($purchase->id, false);

            if(!$deletePurchase['flag']) {
                throw new TMException("CustomError", $deletePurchase['errorCode']);
            }

            $deleteTransportation = $transportationRepo->deleteTransportation($id, false);

            if(!$deleteTransportation['flag']) {
                throw new TMException("CustomError", $deleteTransportation['errorCode']);
            }

            DB::commit();
            return redirect(route('supply.index'))->with("message","Supply details deleted successfully.")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the supply details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
