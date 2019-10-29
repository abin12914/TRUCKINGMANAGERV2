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
            $this->trucks = $truckRepo->getTrucks([], [],  [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], [], true);
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
        $view->with('trucksCombo', $this->trucks);
    }
}
