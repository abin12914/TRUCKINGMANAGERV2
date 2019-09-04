<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class WageTypeComponentComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $wageTypes = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        try {
            $this->wageTypes = config('constants.wageTypes');
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
        $view->with('wageTypes', $this->wageTypes);
    }
}