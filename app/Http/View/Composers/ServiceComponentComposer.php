<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\ServiceRepository;
use Exception;

class ServiceComponentComposer
{
    protected $serviceRepo, $services = [];

    /**
     * Create a new services partial composer.
     *
     * @param  ServiceRepository  $services
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
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
            $this->services = $this->serviceRepo->getServices(
                [], [],  [], ['by' => 'name', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (\Exception $e) {

        }
        $view->with('servicesCombo', $this->services);
    }
}
