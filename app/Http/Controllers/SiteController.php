<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SiteRepository;
use App\Http\Requests\SiteRegistrationRequest;
use App\Http\Requests\SiteFilterRequest;
use \Carbon\Carbon;
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
        $this->siteRepo = $siteRepo;
        $this->errorHead   = config('settings.controllerCode.SiteController');
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
            'relation_type' => [
                'paramName'     => 'relation',
                'paramOperator' => '=',
                'paramValue'    => $request->get('relation_type'),
            ],
            'site_id' => [
                'paramName'     => 'id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('site_id'),
            ],
            'type' => [
                'paramName'     => 'type',
                'paramOperator' => '=',
                'paramValue'    => 3,
            ],
        ];

        $orWhereParams = [
            'site_name' => [
                'paramName'     => 'site_name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('name'). "%"),
            ],
            'name' => [
                'paramName'     => 'name',
                'paramOperator' => 'LIKE',
                'paramValue'    => ("%". $request->get('name'). "%"),
            ]
        ];

        //getSites($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[],$activeFlag=true)
        return view('sites.list', [
            'sites'      => $this->siteRepo->getSites($whereParams, $orWhereParams, [], ['by' => 'id', 'order' => 'asc', 'num' => $noOfRecordsPerPage], ['key' => null, 'value' => null], [], true),
            'relationTypes' => config('constants.siteRelationTypes'),
            'params'        => array_merge($whereParams,$orWhereParams),
            'noOfRecords'   => $noOfRecordsPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        SiteRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $errorCode            = 0;
        $site              = null;
        $openingTransactionId = null;

        $openingBalanceSiteId = config('constants.siteConstants.SiteOpeningBalance.id');

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::user();
            //confirming opening balance existency.
            //getSite($id, $withParams=[], $activeFlag=true)
            $openingBalanceSite = $this->siteRepo->getSite($openingBalanceSiteId, [], false);

            if(!empty($id)) {
                $site = $this->siteRepo->getSite($id, [], false);

                if($site->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_site_id', 'paramOperator' => '=', 'paramValue' => $site->id],
                        ['paramName' => 'credit_site_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceSiteId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_site_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceSiteId],
                        ['paramName' => 'credit_site_id', 'paramOperator' => '=', 'paramValue' => $site->id],
                    ];
                }

                //getTransactions($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$relation,$activeFlag=true)
                $openingTransactionId = $transactionRepo->getTransactions($searchTransaction, [], [], ['by' => 'id', 'order' => 'asc', 'num' => 1], [], [], null, false)->id;
            }

            //save to site table
            $siteResponse   = $this->siteRepo->saveSite([
                'site_name'      => $request->get('site_name'),
                'description'       => $request->get('description'),
                'type'              => array_search('Personal', (config('constants.siteTypes'))),
                'relation'          => $request->get('relation_type'),
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'status'            => 1,
                'created_by'        => $user->id,
                'company_id'        => $user->company_id,
            ], $id);

            if(!$siteResponse['flag']) {
                throw new AppCustomException("CustomError", $siteResponse['errorCode']);
            }

            //opening balance transaction details
            if($financialStatus == 1) { //incoming [site holder gives cash to company] [Creditor]
                $debitSiteId     = $openingBalanceSiteId; //cash flow into the opening balance site
                $creditSiteId    = $siteResponse['site']->id; //newly created site id [flow out from new site]
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){ //outgoing [company gives cash to site holder] [Debitor]
                $debitSiteId     = $siteResponse['site']->id; //newly created site id [flow into new site]
                $creditSiteId    = $openingBalanceSiteId; //flow out from the opening balance site
                $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
            } else {
                $debitSiteId     = $openingBalanceSiteId;
                $creditSiteId    = $siteResponse['site']->id; //newly created site id
                $particulars        = "Opening balance of ". $name . " - None";
                $openingBalance     = 0;
            }

            //save to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_site_id'  => $debitSiteId,
                'credit_site_id' => $creditSiteId,
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'status'            => 1,
                'company_id'        => $user->company_id,
            ], $openingTransactionId);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            DB::commit();
            
            if(!empty($id)) {
                return [
                    'flag'    => true,
                    'site' => $siteResponse['site'],
                ];
            }
            return redirect(route('sites.show', $siteResponse['site']->id))->with("message","Site details saved successfully. Reference Number : ". $siteResponse['site']->id)->with("alert-class", "success");
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
        $site    = [];

        try {
            $site = $this->siteRepo->getSite($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 2);

            //throwing model not found exception when no model is fetched
            throw new ModelNotFoundException("Site", $errorCode);
        }

        return view('sites.details', [
            'site'       => $site,
            'relationTypes' => config('constants.siteRelationTypes'),
            'siteTypes'  => config('constants.$siteTypes'),
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
        $site    = [];

        $relationTypes        = config('constants.siteRelationTypes');
        $siteRelationType = array_search('Sites', config('constants.siteRelationTypes')); //site -> [index = 1]
        //excluding the relationtype 'site'[index = 1] for site update
        unset($relationTypes[$siteRelationType]);

        try {
            $site = $this->siteRepo->getSite($id, [], false);
        } catch (Exception $e) {
            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 3);
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Site", $errorCode);
        }

        return view('sites.edit', [
            'site'       => $site,
            'relationTypes' => $relationTypes,
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
        TransactionRepository $transactionRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('site.show', $updateResponse['site']->id))->with("message","Site details updated successfully. Updated Record Number : ". $updateResponse['site']->id)->with("alert-class", "success");
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
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }
}
