<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\EmployeeRepository;
use Exception;

class EmployeeComponentComposer
{
    protected $employeeRepo, $employees = [];

    /**
     * Create a new employees partial composer.
     *
     * @param  EmployeeRepository  $employees
     * @return void
     */
    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
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
            $this->employees = $this->employeeRepo->getEmployees(
                [], [],  [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (Exception $e) {
        }

        $view->with('employeesCombo', $this->employees);
    }
}
