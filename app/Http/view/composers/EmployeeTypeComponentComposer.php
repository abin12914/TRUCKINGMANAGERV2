<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class EmployeeTypeComponentComposer
{
    protected $employeeTypes = [];

    public function __construct()
    {
        try {
            $this->employeeTypes = config('constants.employeeTypes');
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
        $view->with('employeeTypes', $this->employeeTypes);
    }
}
