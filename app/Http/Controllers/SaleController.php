<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SaleRepository;

class SaleController extends Controller
{
    protected $saleRepo;
    public $errorHead = null;

    public function __construct(SaleRepository $saleRepo)
    {
        $this->saleRepo = $saleRepo;
        $this->errorHead   = config('settings.controller_code.SaleController');
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
        if(!empty($request->get('destination_id'))) {
            $relationalParams['destination_id'] = [
                'relation'      => 'transportation',
                'paramName'     => 'destination_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('destination_id'),
            ];
        }
        if(!empty($request->get('material_id'))) {
            $relationalParams['material_id'] = [
                'relation'      => 'transportation',
                'paramName'     => 'material_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('material_id'),
            ];
        }
        if(!empty($request->get('customer_account_id'))) {
            $relationalParams['customer_account_id'] = [
                'relation'      => 'transaction',
                'paramName'     => 'debit_account_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('customer_account_id'),
            ];
        }

        try {
            $sale = $this->saleRepo->getSales([], [], $relationalParams, ['by' => 'id', 'order' => 'desc', 'num' => 1], ['key' => null, 'value' => null], [], true);

            if(!empty($sale)) {
                return [
                    'flag'          => 'true',
                    'measure_type'  => $sale->measure_type,
                    'quantity'      => $sale->quantity,
                    'rate'          => $sale->rate
                ];
            }
        } catch (Exception $e) {

        }

        return [
            'flag' => 'false',
        ];
    }
}
