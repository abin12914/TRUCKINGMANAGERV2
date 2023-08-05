<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Auth;
use App\Repositories\CompanySettingsRepository;

class AllViewComposer
{
    protected $settingsRepo, $loggedUser = [], $settings = [];

    public function __construct(CompanySettingsRepository $settingsRepo)
    {
        $this->settingsRepo = $settingsRepo;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        try {
            if(Auth::check()) {
                $this->loggedUser = Auth::user();
                $this->settings   = $this->settingsRepo->getCompanySettings([])->first();
            }
        } catch (\Exception $e) {
        }

        $view->with(['loggedUser' => $this->loggedUser, 'settings' => $this->settings]);
    }
}
