<?php

return [
    'name' => 'Diet',
    'Permission' => [
        'group' => [
            'name' => 'admin_diet_access',
            'display_name' => 'بخش رژیم',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'بیماری‌ها' => [
                'view_admin_disease' => 'مشاهده بخش بیماری‌ها',
                'add_admin_disease' => 'افزودن بیماری',
                'edit_admin_disease' => 'ویرایش بیماری',
                'delete_admin_disease' => 'حذف بیماری',
            ],
            'الگو‌ها' => [
                'view_admin_meal_category' => 'مشاهده بخش الگو‌ها',
                'add_admin_meal_category' => 'افزودن الگو',
                'group_delete_admin_meal_category' => 'حذف گروهی الگو',
                'delete_admin_meal_category' => 'حذف الگو',
                'edit_admin_meal_category' => 'ویرایش الگو',
            ],
            'وعده‌ها' => [
                'view_admin_meal' => 'مشاهده بخش وعده‌ها',
                'add_admin_meal' => 'افزودن وعده',
                'delete_admin_meal' => 'حذف وعده',
                'group_delete_admin_meal' => '‌حذف گروهی وعده‌ها',
                'edit_admin_meal' => 'ویرایش وعده',
            ],
            'رژیم‌های درخواستی کاربران' => [
                'view_admin_user_meal' => 'مشاهده بخش درخواست رژیم‌ها',
                'delete_admin_user_meal' => 'حذف درخواست‌های رژیم',
                'view_admin_user_offline_diet' => 'مشاهده بخش رژیم‌های آفلاین',
                'edit_admin_user_offline_diet' => 'تجویز رژیم آفلاین',
            ],
            'بخش پکیج‌های رژیم‌ها' => [
                'view_admin_diet_package' => 'مشاهده بخش پکیج‌های رژیم‌ها',
                'add_admin_diet_package' => 'اضافه کردن پکیج رژیم',
                'delete_admin_diet_package' => 'حذف پکیج رژیم',
                'edit_admin_diet_package' => 'ویرایش پکیج رژیم',
            ],
        ],
    ],
    'Menu' => [
        'title' => 'رژیم',
        'gate' => 'admin_diet_access',
        'icon' => 'fa fa-cutlery',
        'route' => null,
        'has_child' => true,
        'children' => [
            [
                'title' => 'بیماری‌ها',
                'gate' => 'view_admin_disease',
                'icon' => 'fa fa-medkit',
                'route' => 'admin.disease.index',
                'has_child' => false,
            ],
            [
                'title' => 'پکیج‌های رژیم',
                'gate' => 'view_admin_diet_package',
                'icon' => 'fa fa-archive',
                'route' => 'admin.diet.package.index',
                'has_child' => false,
            ],
            [
                'title' => 'اطلاعات تغذیه‌ای',
                'gate' => 'admin_diet_access',
                'icon' => 'fa fa-info',
                'route' => null,
                'has_child' => true,
                'children' => [
                    [
                        'title' => 'الگو‌های رژیم',
                        'gate' => 'view_admin_meal_category',
                        'icon' => 'fa fa-leaf',
                        'route' => 'admin.meal_category.index',
                        'has_child' => false,
                    ],
                    [
                        'title' => 'وعده‌ها',
                        'gate' => 'view_admin_meal',
                        'icon' => 'fa fa-archive',
                        'route' => 'admin.meal.index',
                        'has_child' => false,
                    ],
                ]
            ],
            [
                'title' => 'رژیم‌های درخواستی',
                'gate' => 'admin_diet_access',
                'icon' => 'fa fa-shopping-cart',
                'route' => null,
                'has_child' => true,
                'children' => [
                    [
                        'title' => 'رژیم‌های درخواستی',
                        'gate' => 'view_admin_user_meal',
                        'icon' => 'fa fa-user',
                        'route' => 'admin.user_diet.index',
                        'has_child' => false,
                    ],
                    [
                        'title' => 'رژیم‌های آفلاین',
                        'gate' => 'view_admin_user_offline_diet',
                        'icon' => 'fa fa-upload',
                        'route' => 'admin.user_offline_diet.index',
                        'has_child' => false,
                    ],
                ]
            ]
        ]
    ]
];
