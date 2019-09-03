<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class AccountRelationComponentComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $accountRelations = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        try {
            $this->accountRelations = config('constants.accountRelations');
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
        $view->with('accountRelations', $this->accountRelations);
    }
}