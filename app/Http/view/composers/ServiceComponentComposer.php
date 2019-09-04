<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\ServiceRepository;
use Exception;

class ServiceComponentComposer
{
    protected $services = [], $truckBodyTypes = [];

    /**
     * Create a new services partial composer.
     *
     * @param  ServiceRepository  $services
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepo)
    {
        try {
            //getServices($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->services = $serviceRepo->getServices([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);

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
            'servicesCombo' => $this->services
        ]);
    }
}