<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class AccountTypeComponentComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $accountRelationTypes = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        try {
            $this->accountRelationTypes = config('constants.accountRelationTypes');
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
        $view->with('accountRelationTypes', $this->accountRelationTypes);
    }
}