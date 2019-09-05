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
            //getSites($whereParams=[],$orWhereParams=[],$relationalParams=[],$orderBy=['by' => 'id', 'order' => 'asc', 'num' => null],$aggregates=['key' => null, 'value' => null],$withParams=[],$activeFlag=true)
            $this->sites = $siteRepo->getSites([], [],  [], $orderBy=['by' => 'id', 'order' => 'asc', 'num' => null], $aggregates=['key' => null, 'value' => null], $withParams=[], $activeFlag=true);
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
        $view->with(['sitesCombo' => $this->sites]);
    }
}