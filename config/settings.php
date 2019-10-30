<?php

return [

    'controller_code' =>  [
        'AccountController'       => '01',
        'BranchController'        => '02',
        'EmployeeController'      => '03',
        'EmployeeWageController'  => '04',
        'ExpenseController'       => '05',
        'HomeController'          => '06',
        'ProductController'       => '07',
        'PurchaseController'      => '08',
        'ReportController'        => '09',
        'SaleController'          => '10',
        'VoucherController'       => '11',
    ],
    'repository_code' =>  [
        'AccountRepository'         => 100,
        'BranchRepository'          => 200,
        'EmployeeRepository'        => 300,
        'EmployeeWageRepository'    => 400,
        'ExpenseRepository'         => 500,
        'ProductRepository'         => 600,
        'PurchaseRepository'        => 700,
        'SaleRepository'            => 800,
        'ServiceRepository'         => 900,
        'TransactionRepository'     => 1000,
        'TransportationRepository'  => 1100,
        'UserRepository'            => 1200,
        'VoucherRepository'         => 1300,
    ],
    'composer_code' =>  [
        'BranchComponentComposer'   => 5000,
        'AccountComponentComposer'  => 5100,
        'EmployeeComponentComposer' => 5200,
        'ProductComponentComposer'  => 5300,
        'ServiceComponentComposer'  => 5400,
    ],
    'no_of_record_per_page' => env('NO_OF_RECORD_PER_PAGE', 25),
    'print_head_flag'       => env('PRINT_HEAD_FLAG', true),
];
