<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class TruckRegStateCodeComposer
{
    protected $stateCodes = [];

    public function __construct()
    {

        $this->stateCodes = config('constants.stateCodes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('stateCodes', $this->stateCodes);
    }
}
