<?php

return [
    'name' => 'Options',
    'Permission' => [
        'group'       => [
            'name'         => 'admin_Options_access',
            'display_name' => 'تنظیمات سایت',
            'section'      => 'admin_section',
        ],
        'permissions' => [
            'مشاهده' => [
                'show_options_in_admin'   => 'مشاهده تنظیمات',
            ],
            'ویرایش' => [
                'update_options_in_admin'   => 'ویرایش تنظیمات',
            ],
        ],
    ],
    'Menu'       => [
        'title'     => 'تنظیمات',
        'gate'      => 'show_options_in_admin',
        'icon'      => 'fa fa-cog',
        'route'     => 'admin.options_show',
        'has_child' => false,
        'children' => false
    ],
];
