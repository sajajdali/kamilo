<?php

return [
    'name' => 'support',
    'Permission' => [
        'group' => [
            'name' => 'admin_support_access',
            'display_name' => 'پشتیبانی',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'بخش ها' => [
                'view_admin_support_part' => 'مشاهده بخش ها',
                'add_admin_support_part' => 'افزودن بخش جدید',
                'edit_admin_support_part' => 'ویرایش بخش',
                'delete_admin_support_part' => 'حذف بخش',
                'change_status_admin_support_part' => 'تغییر وضعیت بخش',
            ],
            'تیکت ها' => [
                'view_admin_support_tickets' => 'مشاهده تیکت ها',
                'add_admin_support_tickets' => 'افزودن تیکت جدید',
                'edit_admin_support_tickets' => 'ویرایش تیکت',
                'delete_admin_support_tickets' => 'حذف تیکت',
                'change_status_admin_support_tickets' => 'تغییر وضعیت تیکت',
            ],
            'سوالات پرتکرار' => [
                'view_admin_support_questions' => 'مشاهده سوالات',
                'add_admin_support_questions' => 'افزودن سوال جدید',
                'edit_admin_support_questions' => 'ویرایش سوال',
                'delete_admin_support_questions' => 'حذف سوال',
            ],
            'پیام‌ها' => [
                'view_admin_support_message' => 'مشاهده بخش پیام‌ها',
                'add_admin_support_message' => 'ارسال پیام به کاربر',
                'delete_admin_support_message' => 'حذف پیام ارسال شده',
            ],
            'Radio' => [
                'admin_support_tickets_access' => [
                    'admin_all_support_tickets_access' => 'دسترسی به تمام تیکت ها',
                    'admin_limit_support_tickets_access' => 'دسترسی به تیکت های خود کاربر',
                ]
            ]
        ]
    ],
    'Menu' => [
        'title' => 'پشتیبانی',
        'gate' => 'admin_support_access',
        'icon' => 'fa fa-medkit',
        'route' => null,
        'has_child' => true,
        'children' => [
            [
                'title' => 'بخش ها',
                'gate' => 'view_admin_support_part',
                'icon' => 'fa fa-list',
                'route' => 'admin.support.parts.index',
                'has_child' => false
            ],
            [
                'title' => 'تیکت ها',
                'gate' => 'view_admin_support_tickets',
                'icon' => 'fa fa-question-circle-o',
                'route' => 'admin.support.tickets.index',
                'has_child' => false
            ],
            [
                'title' => 'سوالات پرتکرار',
                'gate' => 'view_admin_support_questions',
                'icon' => 'fa fa-question-circle-o',
                'route' => '',
                'has_child' => true,
                'children' => [
                    [
                        'title' => 'لیست',
                        'gate' => 'view_admin_support_questions',
                        'icon' => 'fa fa-list',
                        'route' => 'admin.support.questions.index',
                        'has_child' => false
                    ],
                    [
                        'title' => 'افزودن',
                        'gate' => 'add_admin_support_questions',
                        'icon' => 'fa fa-plus-circle',
                        'route' => 'admin.support.questions.create',
                        'has_child' => false
                    ],
                ]
            ],
        ]
    ]
];
