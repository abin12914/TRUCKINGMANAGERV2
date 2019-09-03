<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class AccountRelationRegComponentComposer
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
            $relations          = config('constants.accountRelations');
            $employeeRelation   = array_search('Employees', $relations); //employee -> [index = 1]
            //excluding the relationtype 'employee'[index = 1] for account register/update
            unset($relations[$employeeRelation]);

            $this->accountRelations = $relations;
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