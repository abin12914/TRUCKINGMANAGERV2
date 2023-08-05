<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class SiteTypeComponentComposer
{
    protected $siteTypes = [];

    public function __construct()
    {
        $this->siteTypes = config('constants.siteTypes');
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('siteTypes', $this->siteTypes);
    }
}
