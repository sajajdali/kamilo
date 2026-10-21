<?php
return [
    'name' => 'Heroes',
    'Permission' => [
        'group' => [
            'name' => 'admin_heroes_access',
            'display_name' => 'بخش قهرمانان',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'مدیریت' => [
                'view_admin_heroes' => 'مشاهده بخش قهرمانان',
                'edit_admin_heroes' => 'ویرایش قهرمانان',
                'delete_admin_heroes' => 'حذف قهرمانان',
            ],
        ],
    ],
    'Menu' => [
        'title' => 'قهرمانان',
        'gate' => 'admin_heroes_access',
        'icon' => 'fa fa-trophy',
        'route' => 'admin.heroes.index',
        'has_child' => true,
        'children' => [
            [
                'title' => 'لیست قهرمانان',
                'gate' => 'view_admin_heroes',
                'icon'  => 'fa fa-list',
                'route' => 'admin.heroes.index',
                'has_child' => false,
            ],
            [
                'title' => 'اضافه کردن قهرمان',
                'gate' => 'add_admin_heroes',
                'icon'  => 'fa fa-plus-circle',
                'route' => 'admin.heroes.create',
                'has_child' => false,
            ]
        ]
    ]
];
