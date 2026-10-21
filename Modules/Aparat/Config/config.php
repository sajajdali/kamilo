<?php

return [
    'name' => 'Aparat',
    'Permission' => [
        'group' => [
            'name' => 'admin_aparat_access',
            'display_name' => 'بخش ویدیو‌ها',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'مدیریت' => [
                'view_admin_aparat' => 'مشاهده بخش قهرمانان',
                'add_admin_aparat' => 'افزودن قهرمانان',
                'edit_admin_aparat' => 'ویرایش قهرمانان',
                'delete_admin_aparat' => 'حذف قهرمانان',
            ],
        ],
    ],
    'Menu' => [
        'title' => 'ویدیو‌ها',
        'gate' => 'admin_aparat_access',
        'icon' => 'fa fa-video-camera',
        'route' => 'admin.aparat.index',
        'has_child' => true,
        'children' => [
            [
                'title' => 'لیست ویدیو‌ها',
                'gate' => 'view_admin_aparat',
                'icon' => 'fa fa-list',
                'route' => 'admin.aparat.index',
                'has_child' => false,
            ],
            [
                'title' => 'اضافه کردن ویدیو',
                'gate' => 'add_admin_aparat',
                'icon' => 'fa fa-plus-circle',
                'route' => 'admin.aparat.create',
                'has_child' => false,
            ]
        ]
    ]
];

