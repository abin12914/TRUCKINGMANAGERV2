<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\AccountRepository;
use Exception;

class AccountComponentComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $accounts = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(AccountRepository $accountRepo)
    {
        $whereParams = [
            [
                'paramName'     => 'type',
                'paramOperator' => '!=',
                'paramValue'    => 2,
            ]
        ];

        try {
            $this->accounts = $accountRepo->getAccounts($whereParams, [], [], ['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => null, 'value' => null], [], true);
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
        $view->with('accountsCombo', $this->accounts);
    }
}
