<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\TruckRepository;
use Exception;

class TruckComponentComposer
{
    protected $trucks = [];

    /**
     * Create a new trucks partial composer.
     *
     * @param  TruckRepository  $trucks
     * @return void
     */
    public function __construct(TruckRepository $truckRepo)
    {
        try {
            //getTrucks($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->trucks = $truckRepo->getTrucks([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);
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
        $view->with(['trucksCombo' => $this->trucks]);
    }
}