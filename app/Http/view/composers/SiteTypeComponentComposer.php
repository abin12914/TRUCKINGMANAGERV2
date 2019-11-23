<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class SiteTypeComponentComposer
{
    protected $siteTypes = [];

    public function __construct()
    {
        try {
            $this->siteTypes = config('constants.siteTypes');
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
        $view->with('siteTypes', $this->siteTypes);
    }
}
