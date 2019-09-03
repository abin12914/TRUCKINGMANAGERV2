<?php
//use extra caution while edit cause it may affect multiple parts of the project
//never think of changing values in production, ever!!
return [
    'userRoles' => [
        'superadmin' => 0,
        'admin'      => 1,
        'user'       => 2,
    ],

    'accountRelations' => [
        1 => 'Employees',
        2 => 'Supplier',
        3 => 'Customer',
        4 => 'Contractor',
        5 => 'General/Other',
    ],

    'accountTypes' => [
        1 => 'Real',
        2 => 'Nominal',
        3 => 'Personal',
    ],

    'employeeWageTypes' => [
        1 => 'Per Month',
        2 => 'Per Day',
    ],

    'siteTypes' => [
        1 => 'Quarry',
        2 => 'Earth Movement Area',
        3 => 'Residential Area',
        4 => 'Construction Area'
    ],

    'transactionRelations' => [
        1 => [
            'relationName'  => 'employeeWage',
            'displayName'   => 'Employee Wage'
        ],
        2 => [
            'relationName'  => 'excavatorReading',
            'displayName'   => 'Excavator Reading'
        ],
        3 => [
            'relationName'  => 'excavatorRent',
            'displayName'   => 'Excavator Rent'
        ],
        4 => [
            'relationName'  => 'expense',
            'displayName'   => 'Expense'
        ],
        5 => [
            'relationName'  => 'voucher',
            'displayName'   => 'Voucher'
        ]
    ],

    'accountConstants' => [
        'Cash' => [
            'account_name'      => 'Cash',
            'description'       => 'Cash account',
            'type'              => 1, //real account
            'relation'          => 0, //real
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Cash account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],

        'Sales' => [
            'account_name'      => 'Sales',
            'description'       => 'Sales Account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Sales account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],

        'Purchases' => [
            'account_name'      => 'Purchases',
            'description'       => 'Purchases account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Purchases account',
            'phone'             => '0000000000',
            'status'            => 1,  
            'company_id'        => 0,
        ],

        'TripRent' => [
            'account_name'      => 'Trip-Rent',
            'description'       => 'Trip rent account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Trip rent  account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],

        'EmployeeWage' => [
            'account_name'      => 'Employee-Wage',
            'description'       => 'Employee wage account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee wage account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],

        'ServiceAndExpenses' => [
            'account_name'      => 'Service-And-Expenses',
            'description'       => 'Service and expenses account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Service and expenses account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],

        'AccountOpeningBalance' => [
            'account_name'      => 'Account-Opening-Balance',
            'description'       => 'Account opening balance account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Account opening balance account',
            'phone'             => '0000000000',
            'status'            => 1,
            'company_id'        => 0,
        ],
        'DummyAccount' => [
            'account_name'      => 'Dummy-Account',
            'description'       => 'Dummy account',
            'type'              => 0,
            'relation'          => 0,
            'financial_status'  => 0,
            'opening_balance'   => 0,
            'name'              => 'Dummy account',
            'phone'             => '0000000000',
            'status'            => 0,
            'company_id'        => 0,
        ]
    ],
];