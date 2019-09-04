<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\TruckTypeRepository;
use Exception;

class TruckTypeComponentComposer
{
    protected $truckTypes = [], $truckBodyTypes = [];

    /**
     * Create a new truckTypes partial composer.
     *
     * @param  TruckTypeRepository  $truckTypes
     * @return void
     */
    public function __construct(TruckTypeRepository $truckTypeRepo)
    {
        try {
            //getTruckTypes($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->truckTypes = $truckTypeRepo->getTruckTypes([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);

            $this->truckBodyTypes = config('constants.truckBodyTypes');
        } catch (Exception $e) {

        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'truckTypesCombo'   => $this->truckTypes,
            'truckBodyTypes'    => $this->truckBodyTypes
        ]);
    }
}