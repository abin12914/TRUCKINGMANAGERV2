<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class RentTypeComponentComposer
{
    protected $rentTypes = [];

    public function __construct()
    {
        $this->rentTypes = config('constants.rentTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('rentTypes', $this->rentTypes);
    }
}
