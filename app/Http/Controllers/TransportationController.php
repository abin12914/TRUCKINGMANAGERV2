<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransportationRepository;
use App\Repositories\EmployeeWageRepository;
use App\Http\Requests\TransportationRegistrationRequest;
use App\Http\Requests\TransportationFilterRequest;
use App\Http\Requests\TransportationAjaxRequests;
use \Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransportationController extends Controller
{
    protected $transportationRepo;
    public $errorHead = null;

    public function __construct(TransportationRepository $transportationRepo)
    {
        $this->transportationRepo = $transportationRepo;
        $this->errorHead   = config('settings.controller_code.TransportationController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransportationFilterRequest $request)
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

        $transportations = $this->transportationRepo->getTransportations($whereParams=[], $orWhereParams=[], $relationalParams=[], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);

        //params passing for auto selection
        $relationalParams['from_date']['paramValue'] = $request->get('from_date');
        $relationalParams['to_date']['paramValue']   = $request->get('to_date');
        $params = array_merge($whereParams, $relationalParams);

        return view('transportations.list', [
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
        return view('transportations.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        TransportationRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        EmployeeWageRepository $employeeWageRepo,
        EmployeeRepository $employeeRepo,
        $id=null
    ) {
        $errorCode          = 0;
        $transportation     = null;
        $driver             = null;
        $secondDriver       = null;
        $driverWage         = null;
        $secondDriverWage   = null;

        $driverWageType         = 1; //array_search('Per Trip [%]', config('constants.wageTypes'));
        $secondDriverWageType   = 5; //array_search('Per Trip [%] [Assistant Driver]', config('constants.wageTypes'));

        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('transaction_date'))->format('Y-m-d');
        $driverId           = $request->get('driver_id');
        $secondDriverId     = $request->get('second_driver_id');
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
                ]
            ];
            //confirming transportation rent account && employee wage account exist-ency.
            $baseAccounts = $accountRepo->getAccounts([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], [], true);
            if($baseAccounts->count() < 2)
            {
                throw new TMException("CustomError", 1);
            }
            $transportationRentAccountId = $baseAccounts->firstWhere('account_name', 'Transportation-Rent')->id;
            $employeeWageAccountId       = $baseAccounts->firstWhere('account_name', 'Employee-Wage')->id;

            $orWhereParams = [
                'driver_id' => [
                    'paramName'     => 'id',
                    'paramOperator' => '=',
                    'paramValue'    => $driverId,
                ],
                'second_driver_id' => [
                    'paramName'     => 'id',
                    'paramOperator' => '=',
                    'paramValue'    => $secondDriverId,
                ]
            ];
            $employees = $employeeRepo->getEmployees([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], $withParams=[], true);
            $driver         = $employees->firstWhere('id', $driverId);
            $secondDriver   = $employees->firstWhere('id', $secondDriverId);

            //if editing
            if(!empty($id)) {
                $transportation = $this->transportationRepo->getTransportation($id, ['employeeWages'], false);

                $driverWage         = $transportation->employeeWages->firstWhere('wage_type', $driverWageType);
                $secondDriverWage   = $transportation->employeeWages->firstWhere('wage_type', $secondDriverWageType);
            }

            //save transportation transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
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
            $transportationResponse = $this->transportationRepo->saveTransportation([
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

            //save driver wage transaction to table
            $wageTransactionResponse = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                'credit_account_id' => $driver->account_id, // credit the driver account
                'amount'            => $request->get('total_wage_amount'),
                'particulars'       => "Wage of ". $request->get('no_of_trip'). ' trips.',
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($driverWage) ? $driverWage->transaction_id : null));

            if(!$wageTransactionResponse['flag']) {
                throw new TMException("CustomError", $wageTransactionResponse['errorCode']);
            }

            //save to driver wage table
            $driverWageResponse = $this->employeeWageRepo->saveEmployeeWage([
                'transaction_id'    => $wageTransactionResponse['transaction']->id,
                'employee_id'       => $driverId,
                'wage_type'         => $driverWageType,
                'to_date'           => $transactionDate,
                'transportation_id' => $transportationResponse['transportation']->id,
                'trip_wage_amount'  => $request->get('trip_wage_amount'),
                'no_of_trip'        => $request->get('no_of_trip'),
                'total_wage_amount' => $request->get('total_wage_amount'),
                'status'            => 1,
            ], (!empty($driverWage) ? $driverWage->id : null));

            if(!$driverWageResponse['flag']) {
                throw new TMException("CustomError", $driverWageResponse['errorCode']);
            }

            //second driver wage
            if(!empty($secondDriverId)) {
                //save second driver wage transaction to table
                $secondWageTransactionResponse = $transactionRepo->saveTransaction([
                    'transaction_date'  => $transactionDate,
                    'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                    'credit_account_id' => $secondDriver->account_id, // credit the driver account
                    'amount'            => $request->get('second_driver_total_wage'),
                    'particulars'       => "Wage of ". $request->get('no_of_trip'). ' trips.',
                    'status'            => 1,
                    'created_by'        => Auth::id(),
                ], (!empty($secondDriverWage) ? $secondDriverWage->transaction_id : null));

                if(!$secondWageTransactionResponse['flag']) {
                    throw new TMException("CustomError", $secondWageTransactionResponse['errorCode']);
                }

                //save to driver wage table
                $secondDriverWageResponse = $this->employeeWageRepo->saveEmployeeWage([
                    'transaction_id'    => $secondWageTransactionResponse['transaction']->id,
                    'employee_id'       => $secondDriverId,
                    'wage_type'         => $secondDriverWageType,
                    'to_date'           => $transactionDate,
                    'transportation_id' => $transportationResponse['transportation']->id,
                    'trip_wage_amount'  => $request->get('second_driver_wage'),
                    'no_of_trip'        => $request->get('no_of_trip'),
                    'total_wage_amount' => $request->get('second_driver_total_wage'),
                    'status'            => 1,
                ], (!empty($secondDriverWage) ? $secondDriverWage->id : null));

                if(!$secondDriverWageResponse['flag']) {
                    throw new TMException("CustomError", $secondDriverWageResponse['errorCode']);
                }
            } elseif(!empty($secondDriverWage)) {
                //old record has second driver but new record doesn't have
                //soft delete second driver wage
                $secondDriverWage->delete();
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'transportation' => $transportationResponse['transportation']
                ];
            }

            return redirect(route('transportations.index'))->with("message","Transportation details saved successfully. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the transportation details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode      = 0;
        $transportation = [];

        try {
            $transportation = $this->transportationRepo->getTransportation($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Transportation", $errorCode);
        }

        return view('transportations.details', [
            'transportations' => $transportations,
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
        $errorCode      = 0;
        $transportation = [];

        try {
            $transportation = $this->transportationRepo->getTransportation($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Transportation", $errorCode);
        }

        return view('transportations.edit', [
            'transportations' => $transportations,
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
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('transportations.index'))->with("message","Transportations details updated successfully. Updated Record Number : ". $updateResponse['transportation']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the transportation details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
            $deleteResponse = $this->transportationRepo->deleteTransportation($id, false);

            if(!$deleteResponse['flag']) {
                throw new TMException("CustomError", $deleteResponse['errorCode']);
            }

            DB::commit();
            return redirect(route('transportations.index'))->with("message","Transportation details deleted successfully.")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the transportation details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getLastTransaction(Request $request)
    {
        $whereParams = [];
        $relationalParams = [];

        if(!empty($request->get('truck_id'))) {
            $whereParams['truck_id'] = [
                'paramName'     => 'truck_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_id'),
            ];
        }
        if(!empty($request->get('source_id'))) {
            $whereParams['source_id'] = [
                'paramName'     => 'source_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('source_id'),
            ];
        }
        if(!empty($request->get('destination_id'))) {
            $whereParams['destination_id'] = [
                'paramName'     => 'destination_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('destination_id'),
            ];
        }

        if(!empty($request->get('contractor_account_id'))) {
            $relationalParams['contractor_account_id'] = [
                'relation'      => 'transaction',
                'paramName'     => 'debit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('contractor_account_id'),
            ];
        }

        try {
            $transportation = $this->transportationRepo->getTransportations($whereParams=[], $orWhereParams=[], $relationalParams=[], $orderBy=['by' => 'id', 'order' => 'desc', 'num' => 1], $aggregates=['key' => null, 'value' => null], $withParams=['transaction'], $activeFlag=true);

            if(!empty($transportation)) {
                return [
                    'flag'           => 'true',
                    'transportation' => $transportation->toJson()
                ];
            }
        } catch (Exception $e) {

        }

        return [
            'flag' => 'false',
        ];
    }
}
