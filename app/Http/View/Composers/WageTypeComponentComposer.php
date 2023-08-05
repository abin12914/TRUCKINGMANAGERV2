<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class WageTypeComponentComposer
{
    protected $wageTypes = [];

    public function __construct()
    {
        $this->wageTypes = config('constants.wageTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('wageTypes', $this->wageTypes);
    }
}
