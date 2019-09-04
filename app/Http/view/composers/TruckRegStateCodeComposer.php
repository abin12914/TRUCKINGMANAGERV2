<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class TruckRegStateCodeComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $stateCodes = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
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