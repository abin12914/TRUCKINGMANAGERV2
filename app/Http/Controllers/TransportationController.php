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
            'driver_id' => [
                'paramName'     => 'driver_id',
                'paramOperator' => '=', 
                'paramValue'    => $request->get('driver_id'),
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
            ]
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
        TransportaionRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        EmployeeWageRepository $employeeWageRepo,
        EmployeeRepository $employeeRepo,
        TruckRepository $truckRepo,
        SiteRepository $SiteRepo,
        $id=null
    ) {
        $errorCode = 0;
        $transportation   = null;

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
                    'paramValue'    => "Transportaion-Rent",
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
            $transportationRentAccountId = $baseAccounts->firstWhere('account_name', 'Transportaion-Rent')->id;
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
            $employees = $employeeRepo->getEmployees([], $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], $withParams=['account'], true);
            $driver         = $employees->firstWhere('id', $driverId);
            $secondDriver   = $employees->firstWhere('id', $secondDriverId);

            //if editing
            if(!empty($id)) {
                $transportation = $this->transportationRepo->getTransportaion($id, [], false);
            }

            //save transportation transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $request->get('contractor_account_id'), // debit the contractor
                'credit_account_id' => $transportationRentAccountId, // credit the transportation rent account
                'amount'            => $request->get('total_rent'),
                'particulars'       => ("Transportation Rent of ". $request->get('no_of_trip'). " trips. ". $description),
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($transportation) ? $transportation->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to transportation table
            $transportationResponse = $this->transportationRepo->saveTransportaion([
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
                'driver_id'         => $request->get('driver_id'),
                'second_driver_id'  => $request->get('second_driver_id'),
                'status'            => 1,
            ], $id);

            if(!$transportationResponse['flag']) {
                throw new TMException("CustomError", $transportationResponse['errorCode']);
            }

            //save employee wage transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'transaction_date'  => $transactionDate,
                'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                'credit_account_id' => $driverAccount, // credit the employee account
                'amount'            => $request->get('total_rent'),
                'particulars'       => "Transportation Rent of ". $request->get('no_of_trip'). ' trips.',
                'status'            => 1,
                'created_by'        => Auth::id(),
            ], (!empty($transportation) ? $transportation->transaction_id : null));

            if(!$transactionResponse['flag']) {
                throw new TMException("CustomError", $transactionResponse['errorCode']);
            }

            //save to employee wage table
            $employeeWageResponse = $this->employeeWageRepo->saveEmployeeWage([
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
                'driver_id'         => $request->get('driver_id'),
                'second_driver_id'  => $request->get('second_driver_id'),
                'status'            => 1,
            ], $id);

            if(!$employeeWageResponse['flag']) {
                throw new TMException("CustomError", $employeeWageResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'transportation' => $transportationResponse['transportation']
                ];
            }

            return redirect(route('transportations.index'))->with("message","Transportaion details saved successfully. Reference Number : ". $transactionResponse['transaction']->id)->with("alert-class", "success");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
