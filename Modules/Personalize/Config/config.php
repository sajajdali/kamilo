<?php

return [
    'name' => 'Personalize',
    'Permission' => [
        'group'       => [
            'name'         => 'admin_personalize_access',
            'display_name' => 'شخصی سازی',
            'section'      => 'admin_section',
        ],
        'permissions' => [
            'منو ها' => [
                'personalize_edit_menus'   => 'ویرایش منو ها',
            ],
        ],
    ],
    'Menu'       => [
        'title'     => 'شخصی سازی',
        'gate'      => 'personalize_edit_menus',
        'icon'      => 'fa fa-cog',
        'route'     => '',
        'has_child' => TRUE,
        'children' => [
            [
                'title'     => 'منو ها',
                'gate'      => 'personalize_edit_menus',
                'icon'      => 'fa fa-cog',
                'route'     => 'personalize_index',
                'has_child' => false,
            ]
        ]
    ],
];
