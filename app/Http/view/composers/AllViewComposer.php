<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;
use App\Repositories\CompanySettingsRepository;

class AllViewComposer
{
    protected $loggedUser = [], $settings = [];

    public function __construct(CompanySettingsRepository $settingsRepo)
    {
        try {
            if(Auth::check()) {
                $this->loggedUser = Auth::user();
                $this->settings   = $settingsRepo->getCompanySettings([]);
            }
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
        $view->with(['loggedUser' => $this->loggedUser, 'settings' => $this->settings]);
    }
}
