<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class MeasureTypeComponentComposer
{
    protected $measureTypes = [];

    public function __construct()
    {
        try {
            $this->measureTypes = config('constants.measureTypes');
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
        $view->with('measureTypes', $this->measureTypes);
    }
}
