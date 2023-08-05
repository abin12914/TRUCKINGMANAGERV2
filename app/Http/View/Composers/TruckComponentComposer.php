<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\TruckRepository;
use Exception;

class TruckComponentComposer
{
    protected $truckRepo, $trucks = [];

    /**
     * Create a new trucks partial composer.
     *
     * @param  TruckRepository  $trucks
     * @return void
     */
    public function __construct(TruckRepository $truckRepo)
    {
        $this->truckRepo = $truckRepo;
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
            $this->trucks = $this->truckRepo->getTrucks(
                [], [],  [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (\Exception $e) {
        }

        $view->with('trucksCombo', $this->trucks);
    }
}
