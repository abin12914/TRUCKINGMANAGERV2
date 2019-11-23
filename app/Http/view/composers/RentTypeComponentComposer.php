<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class RentTypeComponentComposer
{
    protected $rentTypes = [];

    public function __construct()
    {
        try {
            $this->rentTypes = config('constants.rentTypes');
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
        $view->with('rentTypes', $this->rentTypes);
    }
}
