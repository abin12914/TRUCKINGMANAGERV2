<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseRepository;

class PurchaseController extends Controller
{
    protected $purchaseRepo;
    public $errorHead = null;

    public function __construct(PurchaseRepository $purchaseRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
        $this->errorHead   = config('settings.controller_code.PurchaseController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * return last resource
     *
     */
    public function getLastTransaction(Request $request)
    {
        $relationalParams = [];

        if(!empty($request->get('truck_id'))) {
            $relationalParams['truck_id'] = [
                'relation'      => 'transportation',
                'paramName'     => 'truck_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('truck_id'),
            ];
        }
        if(!empty($request->get('source_id'))) {
            $relationalParams['source_id'] = [
                'relation'      => 'transportation',
                'paramName'     => 'source_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('source_id'),
            ];
        }
        if(!empty($request->get('destination_id'))) {
            $relationalParams['destination_id'] = [
                'relation'      => 'transportation',
                'paramName'     => 'destination_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('destination_id'),
            ];
        }

        if(!empty($request->get('supplier_account_id'))) {
            $relationalParams['supplier_account_id'] = [
                'relation'      => 'transaction',
                'paramName'     => 'credit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('supplier_account_id'),
            ];
        }

        try {
            $purchase = $this->purchaseRepo->getPurchases([], [], $relationalParams, $orderBy=['by' => 'id', 'order' => 'desc', 'num' => 1], $aggregates=['key' => null, 'value' => null], [], $activeFlag=true);

            if(!empty($purchase)) {
                return [
                    'flag'          => 'true',
                    'measure_type'  => $purchase->measure_type,
                    'quantity'      => $transportation->quantity,
                    'rate'          => $transportation->rate
                ];
            }
        } catch (Exception $e) {

        }

        return [
            'flag' => 'false',
        ];
    }
}
