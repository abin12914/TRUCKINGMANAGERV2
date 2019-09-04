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
            //getEmployees($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->employees = $employeeRepo->getEmployees([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);
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
        $view->with(['employeesCombo' => $this->employees]);
    }
}