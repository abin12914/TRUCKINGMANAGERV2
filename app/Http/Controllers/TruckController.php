<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TruckRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\TruckRegistrationRequest;
use Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TruckController extends Controller
{
    protected $truckRepo;
    public $errorHead = null;

    public function __construct(TruckRepository $truckRepo)
    {
        $this->truckRepo    = $truckRepo;
        $this->errorHead    = config('settings.controller_code.TruckController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereParams = [
            'truck_type_id' => [
                'paramName'     => 'truck_type_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_type_id'),
            ],
            'truck_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_id'),
            ],
            'ownership_status' => [
                'paramName'     => 'ownership_status',
                'paramOperator' => '=',
                'paramValue'    => $request->get('ownership_status'),
            ]
        ];

        return view('trucks.list', [
            'trucks' => $this->truckRepo->getTrucks($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 25], [], [], true),
            'params' => $whereParams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trucks.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TruckRegistrationRequest $request, $id=null)
    {
        $errorCode          = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $inputData = [
                'reg_number'        => $request->get('reg_number'),
                'description'       => $request->get('description'),
                'truck_type_id'     => $request->get('truck_type_id'),
                'volume'            => $request->get('volume'),
                'body_type'         => $request->get('body_type'),
                'insurance_upto'    => !empty($request->get("insurance_upto")) ? Carbon::createFromFormat('d-m-Y', $request->get("insurance_upto"))->format('Y-m-d') : null,
                'tax_upto'          => !empty($request->get("tax_upto")) ? Carbon::createFromFormat('d-m-Y', $request->get("tax_upto"))->format('Y-m-d') : null,
                'fitness_upto'      => !empty($request->get("fitness_upto")) ? Carbon::createFromFormat('d-m-Y', $request->get("fitness_upto"))->format('Y-m-d') : null,
                'permit_upto'       => !empty($request->get("permit_upto")) ? Carbon::createFromFormat('d-m-Y', $request->get("permit_upto"))->format('Y-m-d') : null,
                'pollution_upto'    => !empty($request->get("pollution_upto")) ? Carbon::createFromFormat('d-m-Y', $request->get("pollution_upto"))->format('Y-m-d') : null,
                'status'            => 1,
            ];

            //if not editing
            if(empty($id)){
                $inputData['ownership_status'] = ($request->get('ownership_status') ?? 0);
            }

            //save truck to table
            $truckResponse   = $this->truckRepo->saveTruck($inputData, $id);

            if(!$truckResponse['flag']) {
                throw new TMException("CustomError", $truckResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'truck' => $truckResponse['truck']
                ];
            }

            return redirect(route('trucks.index'))->with("message","Truck details saved successfully. Reference Number : ". $truckResponse['truck']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the truck details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $truck  = [];

        try {
            $truck = $this->truckRepo->getTruck($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Truck", $errorCode);
        }

        return view('trucks.details', [
            'truck' => $truck,
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
        $errorCode  = 0;
        $truck  = [];

        try {
            $truck = $this->truckRepo->getTruck($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Truck", $errorCode);
        }

        return view('trucks.edit-add', [
            'truck' => $truck,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TruckRegistrationRequest $request, $id)
    {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('trucks.index'))->with("message","Trucks details updated successfully. Updated Record Number : ". $updateResponse['truck']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the truck details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
            $deleteResponse = $this->truckRepo->deleteTruck($id, false);

            if(!$deleteResponse['flag']) {
                throw new TMException("CustomError", $deleteResponse['errorCode']);
            }

            DB::commit();
            return redirect(route('truck.index'))->with("message","Truck details deleted successfully.")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the truck details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the list of expired & critical certificates
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function certificates()
    {
        $whereParams = [
            'ownership_status' => [
                'paramName'     => 'ownership_status',
                'paramOperator' => '=',
                'paramValue'    => 1, //own vehicles only
            ]
        ];

        return view('trucks.certificates', [
            'trucks' => $this->truckRepo->getTrucks($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 25], [], [], true),
        ]);
    }
}
