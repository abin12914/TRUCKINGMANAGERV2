<?php

namespace App\Listeners;

use App\Events\CreatedCompanyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Account;
use App\Models\CompanySettings;
use App\Repositories\AccountRepository;
use App\Repositories\CompanySettingsRepository;

class CreatedCompanyEventListener implements ShouldQueue
{
    protected $accountRepo, $companySettingsRepo, $listnerCode;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AccountRepository $accountRepo, CompanySettingsRepository $companySettingsRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->companySettingsRepo = $companySettingsRepo;
        $this->listnerCode = config('settings.listener_code.CreatedCompanyEventListener');
    }

    /**
     * Handle the event.
     *
     * @param  CreatedCompanyEvent  $event
     * @return void
     */
    public function handle(CreatedCompanyEvent $event)
    {
        //company have no accounts -> create new basic accounts
        $accountConstants = config('constants.accountConstants');
        $baseSettings     = config('constants.baseSettings');
        $inputArray       = [];

        $whereParams = [
            'relation_type' => [
                'paramName'     => 'company_id',
                'paramOperator' => '=',
                'paramValue'    => $event->company->id,
            ]
        ];

        $settingsWhereParams = [
            'settings' => [
                'paramName'     => 'company_id',
                'paramOperator' => '=',
                'paramValue'    => $event->company->id,
            ]
        ];

        try {
            //check whether company already have any accounts
            $companyAccountsCount = $this->accountRepo->getAccounts($whereParams,[],[],['by' => 'id', 'order' => 'asc', 'num' => null], ['key' => 'count', 'value' => null], [],true);

            //check if accounts already exist
            if($companyAccountsCount > 0) {
                //base accounts already exist do nothing and return
                return;
            }

            //add base accounts to newly created company
            foreach ($accountConstants as $key => $value) {
                $value['company_id'] = $event->company->id;
                $inputArray[] = $value;
            }

            $account = Account::insert($inputArray);

            /////////////////////////////////////////////////

            //check whether company already have any settings
            $settings = $this->companySettingsRepo->getCompanySettings($settingsWhereParams);

            //check if settings already exist
            if($settings->count() > 0) {
                //settings already exist do nothing and return
                return;
            }

            //add company default settings to newly created company
            $baseSettings['company_id'] = $event->company->id;
            $companySettings = CompanySettings::insert($baseSettings);
        } catch (\Exception $e) {
            throw new TMException("CustomError", $this->listnerCode);
        }
    }
}
