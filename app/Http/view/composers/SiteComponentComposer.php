<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\SiteRepository;
use Exception;

class SiteComponentComposer
{
    protected $siteRepo, $sites = [];

    /**
     * Create a new sites partial composer.
     *
     * @param  SiteRepository  $sites
     * @return void
     */
    public function __construct(SiteRepository $siteRepo)
    {
        $this->siteRepo = $siteRepo;
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
            $this->sites = $this->siteRepo->getSites(
                [], [],  [], ['by' => 'name', 'order' => 'asc', 'num' => null], [], [], true
            );
        } catch (Exception $e) {
        }

        $view->with('sitesCombo', $this->sites);
    }
}
