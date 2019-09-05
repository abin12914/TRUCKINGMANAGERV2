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
        View::composer('*', "App\Http\ViewComposers\AllViewComposer");
        //accounts to views
        View::composer('components.selects.accounts', "App\Http\View\Composers\AccountComponentComposer");
        //trucks to views
        View::composer('components.selects.trucks', "App\Http\View\Composers\TruckComponentComposer");
        //account types to views
        View::composer([
            'components.selects.account-relation',
            'accounts.list',
            'accounts.details'
        ], "App\Http\View\Composers\AccountRelationComponentComposer");
        //account relations to views
        View::composer('components.selects.account-relation-reg', "App\Http\View\Composers\AccountRelationRegComponentComposer");
        //employee type to views
        View::composer('components.selects.employee-type', "App\Http\View\Composers\EmployeeTypeComponentComposer");
        //wage type to views
        View::composer([
            'components.selects.wage-type',
            'employees.list',
            'employees.details'
        ], "App\Http\View\Composers\WageTypeComponentComposer");
        //employees to views
        View::composer('components.selects.employees', "App\Http\View\Composers\EmployeeComponentComposer");
        //truck type to views
        View::composer([
            'trucks.register',
            'trucks.list',
            'trucks.details',
            'trucks.edit',
        ], "App\Http\View\Composers\TruckTypeComponentComposer");
        //indian vehicle registation codes to views
        View::composer('trucks.register', "App\Http\View\Composers\TruckRegStateCodeComposer");
        //sites to views
        View::composer('components.selects.sites', "App\Http\View\Composers\SiteComponentComposer");
        //site type to views
        View::composer([
            'components.selects.sites',
            'sites.list',
            'sites.register'
        ], "App\Http\View\Composers\SiteTypeComponentComposer");
        //services to views
        View::composer('components.selects.services', "App\Http\View\Composers\ServiceComponentComposer");
    }
}
