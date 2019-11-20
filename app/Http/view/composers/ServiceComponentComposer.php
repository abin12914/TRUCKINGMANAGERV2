<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\ServiceRepository;
use Exception;

class ServiceComponentComposer
{
    protected $services = [];

    /**
     * Create a new services partial composer.
     *
     * @param  ServiceRepository  $services
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepo)
    {
        try {
            $this->services = $serviceRepo->getServices([], [],  [], ['by' => 'name', 'order' => 'asc', 'num' => null], [], [], true);
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
        $view->with('servicesCombo', $this->services);
    }
}
