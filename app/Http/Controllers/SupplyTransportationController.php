<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransportationFilterRequest;
use App\Http\Requests\SupplyRegistrationRequest;
use App\Repositories\SupplyTransportationRepository;
use App\Repositories\TransportationRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeWageRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use \Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplyTransportationController extends Controller
{
    public $errorHead = null, $driverWageType;

    public function __construct()
    {
        $this->errorHead      = config('settings.controller_code.SupplyTransportationController');
        $this->driverWageType = array_search('Per Trip [%]', config('constants.wageTypes')); //driver bata wage type
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransportationFilterRequest $request, SupplyTransportationRepository $transportationRepo)
    {
        //determine the route and display view accordingly
        $viewFileName = $request->routeIs('supply.customer.copy') ? 'supply.customer-copy' : 'supply.list';

        $errorCode          = 0;
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');
        //date format conversion
        $fromDate = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate   = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";

        $whereParams = [
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

        try {
            $transportations = $transportationRepo->getSupplyTransportations(
                $whereParams, [], $relationalParams, ['by' => 'transportation_date', 'order' => 'asc', 'num' => $noOfRecordsPerPage], [], ['truck', 'transaction.debitAccount', 'source', 'destination', 'material', 'sale'], true
            );
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);

            return redirect(route('dashboard'))->with("message","Failed to get the supply list. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
        }


        //params passing for auto selection
        $relationalParams['from_date']['paramValue'] = $request->get('from_date');
        $relationalParams['to_date']['paramValue']   = $request->get('to_date');
        $params = array_merge($whereParams, $relationalParams);

        return view($viewFileName, [
            'transportations'   => $transportations,
            'params'            => $params,
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
        return view('supply.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        SupplyRegistrationRequest $request,
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
        $drivers            = null;
        $driverWages        = null;
        $purchase           = null;
        $sale               = null;

        //values for description
        $truckRegNumber  = $request->get("truck_reg_number");
        $sourceName      = strtok($request->get("source_name"), ',');
        $destinationName = strtok($request->get("destination_name"), ',');
        $materialName    = strtok($request->get("material_name"), '/');
        $noOfTrip        = $request->get('no_of_trip');
        $tripDetails     = $truckRegNumber. " : ". $sourceName. " - ". $destinationName. " [". $noOfTrip. " Trip(s)]";
        $purchaseDetail  = $truckRegNumber. " : ". $sourceName. " - ". $materialName. " [". $noOfTrip. " Trip(s)]";
        $saleDetail      = $truckRegNumber. " : ". $destinationName. " - ". $materialName. " [". $noOfTrip. " Trip(s)]";

        $transportationDate = Carbon::createFromFormat('d-m-Y', $request->get('transportation_date'))->format('Y-m-d');
        $purchaseDate       = Carbon::createFromFormat('d-m-Y', $request->get('purchase_date'))->format('Y-m-d');
        $saleDate           = Carbon::createFromFormat('d-m-Y', $request->get('sale_date'))->format('Y-m-d');
        $driverIds          = $request->get('driver_id');

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

        foreach ($driverIds as $key => $driverId) {
            $driverOrWhereParams['driver_id_'. $key] = [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $driverId,
            ];
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming transportation rent account, employee wage, purchse and sale account exist-ency.
            $baseAccounts = $accountRepo->getAccounts([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true);
            if($baseAccounts->count() < 4)
            {
                throw new TMException("CustomError", 500);
            }
            $transportationRentAccountId = $baseAccounts->firstWhere('account_name', 'Transportation-Rent')->id;
            $employeeWageAccountId       = $baseAccounts->firstWhere('account_name', 'Employee-Wage')->id;
            $purchaseAccountId           = $baseAccounts->firstWhere('account_name', 'Purchases')->id;
            $saleAccountId               = $baseAccounts->firstWhere('account_name', 'Sales')->id;

            $drivers = $employeeRepo->getEmployees([], $driverOrWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true);

            if(count($driverIds) != $drivers->count()) {
                throw new TMException("CustomError", 500);
            }

            //if editing
            if(!empty($id)) {
                $transportation = $transportationRepo->getTransportation($id, ['employeeWages', 'purchase', 'sale'], false);
                $driverWages    = $transportation->employeeWages->where('wage_type', $this->driverWageType);
                $purchase       = $transportation->purchase;
                $sale           = $transportation->sale;
                foreach ($driverWages as $key => $driverWage) {
                    //delete wage details of employee who removed from driver's list
                    if(!in_array($driverWage->employee_id, $driverIds)) {
                        $employeeWageRepo->deleteEmployeeWage($driverWage->id);
                    }
                }
            }

            //transportation

            //save transportation transaction to table
            $transactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $transportationDate,
                'debit_account_id'  => $request->get('contractor_account_id'), // debit the contractor
                'credit_account_id' => $transportationRentAccountId, // credit the transportation rent account
                'amount'            => $request->get('total_rent'),
                'particulars'       => ("Transportation Rent of ". $tripDetails),
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($transportation) ? $transportation->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to transportation table
            $transportationResponse = $transportationRepo->saveTransportation([
                'transportation_date'   => $transportationDate,
                'transaction_id'        => $transactionResponse['transaction']->id,
                'truck_id'              => $request->get('truck_id'),
                'source_id'             => $request->get('source_id'),
                'destination_id'        => $request->get('destination_id'),
                'material_id'           => $request->get('material_id'),
                'rent_type'             => $request->get('rent_type'),
                'measurement'           => $request->get('rent_measurement'),
                'rent_rate'             => $request->get('rent_rate'),
                'trip_rent'             => $request->get('trip_rent'),
                'no_of_trip'            => $noOfTrip,
                'total_rent'            => $request->get('total_rent'),
                'status'                => 1,
            ], $id);

            if(!$transportationResponse['flag']) {
                throw new TMException("CustomError", $transportationResponse['errorCode']);
            }

            //driver wage
            foreach ($driverIds as $key => $driverId) {
                //save driver wage transaction to table
                $wageTransactionResponse = $transactionRepo->saveTransaction([
                    'transaction_date'  => $transportationDate,
                    'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                    'credit_account_id' => $drivers->firstWhere('id', $driverId)->account_id, // credit the driver account
                    'amount'            => $request->get('driver_total_wage')[$key],
                    'particulars'       => "Wage generated for ". $tripDetails,
                    'status'            => 1,
                    'created_by'        => Auth::id(),
                ], ((!empty($driverWages) && !empty($driverWages->firstWhere('employee_id', $driverId))) ? $driverWages->firstWhere('employee_id', $driverId)->transaction_id : null));

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
                    'wage_amount'       => $request->get('driver_wage')[$key],
                    'no_of_trip'        => $noOfTrip,
                    'total_wage_amount' => $request->get('driver_total_wage')[$key],
                    'status'            => 1,
                ], ((!empty($driverWages) && !empty($driverWages->firstWhere('employee_id', $driverId))) ? $driverWages->firstWhere('employee_id', $driverId)->id : null));

                if(!$driverWageResponse['flag']) {
                    throw new TMException("CustomError", $driverWageResponse['errorCode']);
                }
            }

            //purchase

            //save purchase to transaction to table
            $purchaseTransactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $purchaseDate,
                'debit_account_id'  => $purchaseAccountId, // debit the purchase account
                'credit_account_id' => $request->get('supplier_account_id'), // credit the driver account
                'amount'            => $request->get('purchase_total_bill'),
                'particulars'       => "Purchase : ". $purchaseDetail,
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($purchase) ? $purchase->transaction_id : null));

            if(!$purchaseTransactionResponse['flag']) {
                throw new TMException("CustomError", $purchaseTransactionResponse['errorCode']);
            }

            //save to purchase table
            $purchaseResponse = $purchaseRepo->savePurchase([
                'purchase_date'     => $purchaseDate,
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
                'debit_account_id'  => $request->get('customer_account_id'), // debit the driver account
                'credit_account_id' => $saleAccountId, // credit the sale account
                'amount'            => $request->get('sale_total_bill'),
                'particulars'       => "Sale : ". $saleDetail,
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($sale) ? $sale->transaction_id : null));

            if(!$saleTransactionResponse['flag']) {
                throw new TMException("CustomError", $saleTransactionResponse['errorCode']);
            }

            //save to sale table
            $saleResponse = $saleRepo->saveSale([
                'sale_date'         => $saleDate,
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

            return redirect(route('supply.index'))->with("message","Supply details saved successfully. #". $transactionResponse['transaction']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the supply details. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
            $transportation = $transportationRepo->getSupplyTransportation(
                $id, ['truck', 'transaction.debitAccount', 'source', 'destination', 'material', 'employeeWages.employee.account', 'purchase.transaction.creditAccount', 'sale.transaction.debitAccount'], false
            );
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);

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
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Transportation", $errorCode);
        }

        return view('supply.edit-add', compact('transportation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        SupplyRegistrationRequest $request,
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
            return redirect(route('supply.index'))->with("message","Supply details updated successfully. #". $updateResponse['transportation']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the supply details. #". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
            $employeeWages  = $transportation->employeeWages;
            $purchase       = $transportation->purchase;
            $sale           = $transportation->sale;

            foreach ($employeeWages as $key => $employeeWage) {
                $deleteEmployeeWage = $employeeWageRepo->deleteEmployeeWage($employeeWage->id, false);
                if(!$deleteEmployeeWage['flag']) {
                    throw new TMException("CustomError", $deleteEmployeeWage['errorCode']);
                }
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
        } catch (\Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 5);
        }

        return redirect()->back()->with("message","Failed to delete the supply details. #". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
