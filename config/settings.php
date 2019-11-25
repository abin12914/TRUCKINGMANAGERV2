<?php

return [

    'controller_code' =>  [
        'Controller'                    => 1,
        'AccountController'             => 2,
        'EmployeeController'            => 3,
        'ExpenseController'             => 4,
        'HomeController'                => 5,
        'PurchaseController'            => 6,
        'ReportController'              => 7,
        'SaleController'                => 8,
        'SiteController'                => 9,
        'SupplyTransportationController'=> 10,
        'TransportationController'      => 11,
        'TruckController'               => 12,
        'UserController'                => 13,
        'VoucherController'             => 14
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
    'composer_code' => [],
    'listener_code' =>  [
        'CreatedCompanyEventListener'         => 5000,
        'DeletingEmployeeWageEventListener'   => 5100,
        'DeletingExpenseEventListener'        => 5200,
        'DeletingPurchaseEventListener'       => 5300,
        'DeletingSaleEventListener'           => 5400,
        'DeletingTransportationEventListener' => 5500,
        'DeletingVoucherEventListener'        => 5600
    ],
    'no_of_record_per_page' => env('NO_OF_RECORD_PER_PAGE', 25),
    'print_head_flag'       => env('PRINT_HEAD_FLAG', true),
];
