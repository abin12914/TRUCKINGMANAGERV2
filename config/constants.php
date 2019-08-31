<?php
//use extra caution while edit cause it may affect multiple parts of the project
//never think of changing values in production, ever!!
return [
    'userRoles' => [
        'superadmin' => 0,
        'admin'      => 1,
        'user'       => 2,
    ],

    'accountRelationTypes' => [
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
            'id'                => 1,
            'account_name'      => 'Cash', //account id : 1
            'description'       => 'Cash account',
            'type'              => 1, //real account
            'relation'          => 0, //real
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Cash account',
            'phone'             => '0000000001',
            'status'            => 1,
        ],

        'EmployeeWage' => [
            'id'                => 2,
            'account_name'      => 'Employee Wage', //account id : 2
            'description'       => 'Employee wage account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee wage account',
            'phone'             => '0000000002',
            'status'            => 1,
        ],

        'ExcavatorRent' => [
            'id'                => 3,
            'account_name'      => 'ExcavatorRent;', //account id : 3
            'description'       => 'Excavator rent account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Excavator rent account',
            'phone'             => '0000000003',
            'status'            => 1,  
        ],

        'ServiceAndExpense' => [
            'id'                => 4,
            'account_name'      => 'Service And Expenses', //account id : 4
            'description'       => 'Service and expense account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Service and expense account',
            'phone'             => '0000000004',
            'status'            => 1,
        ],

        'AccountOpeningBalance' => [
            'id'                => 5,
            'account_name'      => 'Account Opening Balance', //account id : 5
            'description'       => 'Account opening balance account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Account opening balance account',
            'phone'             => '0000000005',
            'status'            => 1,
        ],

        'Temp1' => [
            'id'                => 6,
            'account_name'      => 'Temp1', //account id : 6
            'description'       => 'Temporary account 1',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Temporary account 1',
            'phone'             => '0000000006',
            'status'            => 0,
        ],

        'Temp2' => [
            'id'                => 7,
            'account_name'      => 'Temp2', //account id : 7
            'description'       => 'Temporary account 2',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Temporary account 7',
            'phone'             => '0000000007',
            'status'            => 0,
        ],
    ],
];