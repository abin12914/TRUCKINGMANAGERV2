<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //current user to all views
        //View::composer('*', "App\Http\ViewComposers\AllViewComposer");
        //accounts to views
        //View::composer('components.selects.accounts', "App\Http\View\Composers\AccountComponentComposer");
        //account types to views
        View::composer('components.selects.account-relation', "App\Http\View\Composers\AccountRelationComponentComposer");
        View::composer('components.selects.account-relation-reg', "App\Http\View\Composers\AccountRelationRegComponentComposer");
        //employees to views
        //View::composer('components.selects.employees', "App\Http\View\Composers\EmployeeComponentComposer");
        //sites to views
        //View::composer('components.selects.sites', "App\Http\View\Composers\SiteComponentComposer");
        //services to views
        //View::composer('components.selects.services', "App\Http\View\Composers\ServiceComponentComposer");
    }
}
