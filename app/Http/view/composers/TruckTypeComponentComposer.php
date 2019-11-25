<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\TruckTypeRepository;
use Exception;

class TruckTypeComponentComposer
{
    protected $truckTypeRepo, $truckTypes = [], $truckBodyTypes = [];

    /**
     * Create a new truckTypes partial composer.
     *
     * @param  TruckTypeRepository  $truckTypes
     * @return void
     */
    public function __construct(TruckTypeRepository $truckTypeRepo)
    {
        $this->truckTypeRepo  = $truckTypeRepo;
        $this->truckBodyTypes = config('constants.truckBodyTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            $this->truckTypes = $this->truckTypeRepo->getTruckTypes(
                [], [],  [], ['by' => 'generic_quantity', 'order' => 'asc', 'num' => null], [], [], true
            );

        } catch (Exception $e) {

        }

        $view->with([
            'truckTypesCombo' => $this->truckTypes,
            'truckBodyTypes'  => $this->truckBodyTypes
        ]);
    }
}
