<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\AccountRepository;
use Exception;

class AccountComponentComposer
{
    protected $accounts = [];

    public function __construct(AccountRepository $accountRepo)
    {
        $whereParams = [
            [
                'paramName'     => 'type',
                'paramOperator' => '!=',
                'paramValue'    => array_search('Nominal', config('constants.accountTypes')), //nominal account=2,
            ]
        ];

        try {
            $this->accounts = $accountRepo->getAccounts(
                $whereParams, [], [], ['by' => 'account_name', 'order' => 'asc', 'num' => null], [], [], true
            );
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
