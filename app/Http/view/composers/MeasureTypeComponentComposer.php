<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class MeasureTypeComponentComposer
{
    protected $measureTypes = [];

    public function __construct()
    {
        $this->measureTypes = config('constants.measureTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('measureTypes', $this->measureTypes);
    }
}
