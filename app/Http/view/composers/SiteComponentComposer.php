<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Repositories\SiteRepository;
use Exception;

class SiteComponentComposer
{
    protected $sites = [];

    /**
     * Create a new sites partial composer.
     *
     * @param  SiteRepository  $sites
     * @return void
     */
    public function __construct(SiteRepository $siteRepo)
    {
        try {
            $this->sites = $siteRepo->getSites([], [],  [], ['by' => 'name', 'order' => 'asc', 'num' => null], [], [], true);
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
        $view->with('sitesCombo', $this->sites);
    }
}
