<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SiteFilterRequest;
use App\Http\Requests\SiteRegistrationRequest;
use App\Repositories\SiteRepository;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Auth;
use DB;
use Exception;
use App\Exceptions\TMException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SiteController extends Controller
{
    protected $siteRepo;
    public $errorHead = null;

    public function __construct(SiteRepository $siteRepo)
    {
        $this->siteRepo     = $siteRepo;
        $this->errorHead    = config('settings.controller_code.SiteController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SiteFilterRequest $request)
    {
        $noOfRecordsPerPage = $request->get('no_of_records') ?? config('settings.no_of_record_per_page');

        $whereParams = [
            'site_type' => [
                'paramName'     => 'site_type',
                'paramOperator' => '=',
                'paramValue'    => $request->get('site_type'),
            ],
            'site_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('site_id'),
            ]
        ];

        return view('sites.list', [
            'sites'       => $this->siteRepo->getSites($whereParams, [], [], ['by' => 'name', 'order' => 'asc', 'num' => $noOfRecordsPerPage], [], [], true),
            'params'      => $whereParams,
            'noOfRecords' => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        SiteRegistrationRequest $request,
        $id=null
    ) {
        $errorCode          = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();

            //save site to table
            $siteResponse   = $this->siteRepo->saveSite([
                'name'       => $request->get('name'),
                'place'      => $request->get('place'),
                'address'    => $request->get('address'),
                'site_type'  => $request->get('site_type'),
                'status'     => 1,
            ], $id);

            if(!$siteResponse['flag']) {
                throw new TMException("CustomError", $siteResponse['errorCode']);
            }

            DB::commit();

            if(!empty($id)) {
                return [
                    'flag' => true,
                    'site' => $siteResponse['site']
                ];
            }

            return redirect(route('sites.index'))->with("message","Site details saved successfully. Reference Number : ". $siteResponse['site']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message","Failed to save the site details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $site  = [];

        try {
            $site = $this->siteRepo->getSite($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Site", $errorCode);
        }

        return view('sites.details', [
            'site' => $site,
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
        $site  = [];

        try {
            $site = $this->siteRepo->getSite($id, [], false);
        } catch (\Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Site", $errorCode);
        }

        return view('sites.edit-add', [
            'site'      => $site,
            'siteTypes' => config('constants.siteTypes')
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
        SiteRegistrationRequest $request,
        $id
    ) {
        $updateResponse = $this->store($request, $id);

        if($updateResponse['flag']) {
            return redirect(route('sites.index'))->with("message","Sites details updated successfully. Updated Record Number : ". $updateResponse['site']->id)->with("alert-class", "success");
        }

        return redirect()->back()->with("message","Failed to update the site details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
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
            $deleteResponse = $this->siteRepo->deleteSite($id, false);

            if(!$deleteResponse['flag']) {
                throw new TMException("CustomError", $deleteResponse['errorCode']);
            }

            DB::commit();
            return redirect(route('sites.index'))->with("message","Site details deleted successfully.")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 4);
        }

        return redirect()->back()->with("message","Failed to delete the site details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
