<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Exception;

class SiteTypeComponentComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $siteTypes = [];

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
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