<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class EmployeeTypeComponentComposer
{
    protected $employeeTypes = [];

    public function __construct()
    {
        $this->employeeTypes = config('constants.employeeTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('employeeTypes', $this->employeeTypes);
    }
}
