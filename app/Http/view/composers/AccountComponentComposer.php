<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\AccountRepository;
use Exception;

class AccountComponentComposer
{
    protected $accountRepo, $accounts = [];

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo = $accountRepo;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $whereParams = [
            [
                'paramName'     => 'type',
                'paramOperator' => '!=',
                'paramValue'    => array_search('Nominal', config('constants.accountTypes')), //nominal account=2,
            ]
        ];

        try {
            $this->accounts = $this->accountRepo->getAccounts(
                $whereParams, [], [], ['by' => 'account_name', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (Exception $e) {
        }

        $view->with('accountsCombo', $this->accounts);
    }
}
