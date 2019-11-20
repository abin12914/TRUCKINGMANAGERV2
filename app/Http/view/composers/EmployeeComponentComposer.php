<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\EmployeeRepository;
use Exception;

class EmployeeComponentComposer
{
    protected $employees = [];

    /**
     * Create a new employees partial composer.
     *
     * @param  EmployeeRepository  $employees
     * @return void
     */
    public function __construct(EmployeeRepository $employeeRepo)
    {
        try {
            $this->employees = $employeeRepo->getEmployees([], [],  [], ['by' => 'id', 'order' => 'asc', 'num' => null], [], [], true);
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
        $view->with('employeesCombo', $this->employees);
    }
}
