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
        //employees to views
        View::composer('components.selects.employees', "App\Http\View\Composers\EmployeeComponentComposer");
        //sites to views
        View::composer('components.selects.sites', "App\Http\View\Composers\SiteComponentComposer");
        //services to views
        View::composer('components.selects.services', "App\Http\View\Composers\ServiceComponentComposer");
        //materials to views
        View::composer('components.selects.materials', "App\Http\View\Composers\MaterialComponentComposer");
        //indian vehicle registation codes to views
        View::composer('trucks.register', "App\Http\View\Composers\TruckRegStateCodeComposer");
        //employee type to views
        View::composer('components.selects.employee-type', "App\Http\View\Composers\EmployeeTypeComponentComposer");




        //account relation types to views
        View::composer([
            'components.selects.account-relation',
            'accounts.list',
            'accounts.details'
        ], "App\Http\View\Composers\AccountRelationComponentComposer");
        //wage type to views
        View::composer([
            'components.selects.wage-type',
            'employees.list',
            'employees.details'
        ], "App\Http\View\Composers\WageTypeComponentComposer");
        //truck type to views
        View::composer([
            'trucks.register',
            'trucks.edit',
            'trucks.list',
            'trucks.details',
        ], "App\Http\View\Composers\TruckTypeComponentComposer");
        //site type to views
        View::composer([
            'sites.edit-add',
            'sites.list',
        ], "App\Http\View\Composers\SiteTypeComponentComposer");
        //rent types to view
        View::composer([
            'components.selects.rent-type',
            'transportations.details',
            'supply.details'
        ], "App\Http\View\Composers\RentTypeComponentComposer");
        //measure types to views
        View::composer([
            'components.selects.measure-type',
            'supply.details'
        ], "App\Http\View\Composers\MeasureTypeComponentComposer");
    }
}
