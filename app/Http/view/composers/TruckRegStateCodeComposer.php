<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class TruckRegStateCodeComposer
{
    protected $stateCodes = [];

    public function __construct()
    {
        try {
            $this->stateCodes = config('constants.stateCodes');
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
        $view->with('stateCodes', $this->stateCodes);
    }
}
