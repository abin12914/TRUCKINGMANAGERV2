<?php

namespace App\Listeners;

use App\Events\CreatedCompanyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Account;
use App\Repositories\AccountRepository;

class CreatedCompanyEventListener implements ShouldQueue
{
    protected $accountRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo = $accountRepo;
    }

    /**
     * Handle the event.
     *
     * @param  CreatedCompanyEvent  $event
     * @return void
     */
    public function handle(CreatedCompanyEvent $event)
    {
        //check whether company already have any accounts
        $whereParams = [
            'relation_type' => [
                'paramName'     => 'company_id',
                'paramOperator' => '=',
                'paramValue'    => $event->company->id,
            ]
        ];

        $companyAccountsCount = $this->accountRepo->getAccounts($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => 'count', 'value' => null], $withParams=[],$activeFlag=true);

        if($companyAccountsCount < 1)
        {
            return false;
        }

        //company have no accounts -> create new basic accounts
        $accountConstants   = config('constants.accountConstants');
        $inputArray         = [];

        foreach ($accountConstants as $key => $value) {
            $value['company_id'] = $event->company->id;
            $inputArray[] = $value;

        }

        $account = Account::insert($inputArray);
    }
}