<?php

return [

    'controller_code' =>  [
        'AccountController'             => '01',
        'EmployeeController'            => '02',
        'ExpenseController'             => '03',
        'HomeController'                => '04',
        'PurchaseController'            => '05',
        'ReportController'              => '06',
        'SaleController'                => '07',
        'SiteController'                => '08',
        'SupplyTransportationController'=> '09',
        'TransportationController'      => '10',
        'TruckController'               => '11',
        'UserController'                => '12',
        'VoucherController'             => '13'
    ],
    'repository_code' =>  [
        'AccountRepository'             => 1000,
        'CompanySettingsRepository'     => 1100,
        'EmployeeRepository'            => 1200,
        'EmployeeWageRepository'        => 1300,
        'ExpenseRepository'             => 1400,
        'FuelRefillRepository'          => 1500,
        'MaterialRepository'            => 1600,
        'PurchaseRepository'            => 1700,
        'Repository'                    => 1800,
        'SaleRepository'                => 1900,
        'ServiceRepository'             => 2000,
        'SiteRepository'                => 2100,
        'SupplyTransportationRepository'=> 2200,
        'TransactionRepository'         => 2300,
        'TransportationRepository'      => 2400,
        'TruckRepository'               => 2500,
        'TruckTypeRepository'           => 2600,
        'VoucherRepository'             => 2700,
    ],
    'no_of_record_per_page' => env('NO_OF_RECORD_PER_PAGE', 25),
    'print_head_flag'       => env('PRINT_HEAD_FLAG', true),
];
