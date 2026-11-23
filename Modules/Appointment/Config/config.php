<?php
return [
    'name' => 'Appointment',
    'Permission' => [
        'group' => [
            'name' => 'admin_appointment_access',
            'display_name' => 'بخش نوبت‌دهی',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'بلک لیست' => [
              'delete_admin_black_list' => 'حذف شماره'
            ],
            'بخش‌ها' => [
                'view_admin_appointment_part' => 'مشاهده بخش‌ها',
                'add_admin_appointment_part' => 'افزودن بخش جدید',
                'edit_admin_appointment_part' => 'ویرایش بخش‌ها',
                'delete_admin_appointment_part' => 'حذف بخش‌ها',
            ],
            'پیامک‌های نوبت‌دهی' => [
                'view_admin_appointment_sms' => 'مشاهده بخش پیامک',
                'add_admin_appointment_sms' => 'افزودن پیامک جدید',
                'edit_admin_appointment_sms' => 'ویرایش پیامک‌ها',
                'delete_admin_appointment_sms' => 'حذف پیامک‌ها',
            ],
            'نوبت‌ها' => [
                'view_admin_appointment' => 'مشاهده بخش نوبت‌ها',
                'add_admin_appointment' => 'ویرایش زمان پزشکان',
                'edit_admin_appointment' => 'ویرایش روز‌های خاص پزشکان',
            ],
            'عملیات نوبت‌ها' => [
                'view_admin_appointment_user' => 'مشاهده بخش عملیات نوبت‌ها',
                'add_admin_appointment_user' => 'افزودن نوبت جدید',
                'edit_admin_appointment_user' => 'ویرایش و تغییر وضعیت',
                'edit_admin_appointment_user_details' => 'ویرایش جزئیات نوبت',
                'confirm_admin_appointment_user' => 'دسترسی به confirm نوبت‌ها',
                'delete_admin_appointment_user' => 'امکان حذف نوبت‌ها',
                'cancel_admin_appointment_user' => 'امکان کنسل کردن نوبت‌ها',
                'change_time_admin_appointment_user' => 'امکان تغییر زمان نوبت‌ها',
                'view_deleted_appointment' => 'مشاهده نوبت های حذف شده',
            ],
            'Radio' => [
                'admin_type_appointment_user_access' => [
                    'admin_type_full_appointment_user_access' => 'دسترسی به تمام نوبت‌ها',
                    'admin_type_limited_appointment_user_access' => 'دسترسی به نوبت‌های ثبت شده توسط خود کاربر'
                ],
                'admin_type_appointment_user_access_cancel' => [
                    'admin_type_full_appointment_user_access_cancel' => 'دسترسی به کنسل کردن تمامی نوبت ها',
                    'admin_type_limited_appointment_user_access_cancel' => 'دسترسی به کنسل کردن نوبت هایی که خود کاربر ثبت کرده'
                ],
                'admin_type_appointment_user_access_update_time' => [
                    'admin_type_full_appointment_user_access_update_time' => 'دسترسی به تغییر زمان تمامی نوبت ها',
                    'admin_type_limited_appointment_user_access_update_time' => 'دسترسی به تغییر زمان نوبت هایی که خود کاربر ثبت کرده'
                ],
                'admin_type_appointment_user_access_delete' => [
                    'admin_type_full_appointment_user_access_delete' => 'دسترسی به  حذف تمامی نوبت ها',
                    'admin_type_limited_appointment_user_access_delete' => 'دسترسی به  حذف نوبت هایی که خود کاربر ثبت کرده'
                ]
            ],

        ],
    ],
    'Menu' => [
        'title' => 'نوبت‌دهی',
        'gate' => 'admin_appointment_access',
        'icon' => 'fa fa-calendar',
        'route' => 'admin.appointment_part.index',
        'has_child' => true,
        'children' => [
            [
                'title' => 'گزارش‌گیری',
                'gate' => 'view_admin_appointment_user',
                'icon' => 'fa fa-file',
                'route' => 'admin.appointment_user.index',
                'has_child' => false,
            ],
            [
                'title' => 'لیست confirm شده‌ها',
                'gate' => 'confirm_admin_appointment_user',
                'icon' => 'fa fa-list-alt',
                'route' => 'admin.appointment_user.confirmed.index',
                'has_child' => false,
            ],
            [
                'title' => 'لیست سیاه',
                'gate' => 'view_admin_black_list',
                'icon' => 'fa fa-rocket',
                'route' => 'admin.blackList.index',
                'has_child' => false,
            ],
            [
                'title' => 'بخش‌ها',
                'gate' => 'view_admin_appointment_part',
                'icon' => 'fa fa-building',
                'route' => 'admin.appointment_part.index',
                'has_child' => false,
            ],
            [
                'title' => 'تنظیمات نوبت',
                'gate' => 'view_admin_appointment',
                'icon' => 'fa fa-book',
                'route' => 'admin.appointment.index',
                'has_child' => false,
            ],
            [
                'title' => 'پیامک',
                'gate' => 'view_admin_appointment_sms',
                'icon' => 'fa fa-envelope',
                'route' => 'admin.appointment_sms.index',
                'has_child' => false,
            ],
            [
                'title' => 'وضعیت پرتال پیامک',
                'gate' => 'view_admin_appointment_sms',
                'icon' => 'fa fa-commenting',
                'route' => 'admin.status.result',
                'has_child' => false,
            ],
            [
                'title' => 'فایل نوبت های روز',
                'gate' => 'view_admin_appointment_user',
                'icon' => 'fa fa-newspaper-o',
                'route' => 'admin.report.index',
                'has_child' => false,
            ],
            [
                'title' => 'اتصال به سامانه ویپ',
                'gate' => 'admin_appointment_access',
                'icon' => 'fa fa-phone',
                'route' => 'admin.voip_connection.edit',
                'has_child' => false,
            ],
        ]
    ]
];
