<?php

return [
    'custom' => [
        'CHARGE_TRANSACTION_TYPE' => 1,
        'REDUCE_CHARGE_TRANSACTION_TYPE' => 2,
        'CHARGE_MONEY_KEY' => 'افزایش شارژ',
        'REDUCE_CHARGE_MONEY_KEY' => 'کاهش شارژ',
    ],
    'name' => 'Finance',
    'Permission' => [
        'group'       => [
            'name'         => 'admin_Finance_access',
            'display_name' => 'امور مالی',
            'section'      => 'admin_section',
        ],
        'permissions' => [
            'تراکنش ها'  => [
                'view_admin_finance_transactions'   => 'مشاهده تراکنش',
                'add_admin_finance_transactions'    => 'ایجاد تراکنش',
                'delete_admin_finance_transactions'    => 'حذف تراکنش',
            ],
            'پرداخت ها'  => [
                'view_admin_finance_payments'   => 'مشاهده لاگ پرداخت',
                'delete_admin_finance_payments'    => 'حذف لاگ پرداخت',
            ],
        ],
    ],
    'Menu' => [
        'title'     => 'امور مالی',
        'gate'      => 'admin_finance_access',
        'icon'      => 'fa fa-money',
        'route'     => null,
        'has_child' => true,
        'children'  => [
            [
                'title'     => 'تراکنش ها',
                'gate'      => 'view_admin_finance_transactions',
                'icon'      => 'fa fa-money',
                'route'     => 'admin.finance.transactions.index',
                'has_child' => true,
                'children'  => [
                    [
                        'title'     => 'لیست',
                        'gate'      => 'view_admin_finance_transactions',
                        'icon'      => 'fa fa-list',
                        'route'     => 'admin.finance.transactions.index',
                        'has_child' => false
                    ],
                    [
                        'title'     => 'افزودن',
                        'gate'      => 'add_admin_finance_transactions',
                        'icon'      => 'fa fa-plus-circle',
                        'route'     => 'admin.finance.transactions.create',
                        'has_child' => false
                    ],
                ]
            ],
        ],
    ],
];
