<?php

return [
    'menu'    => [
        [ // <<<<<<<<<<<<<<<< general >>>>>>>>>>>>>>>>
            'id'             => 'appointment',
            'title'          => 'بخش نوبت‌دهی',
            'icon'           => 'fa fa-clock',
            'children'       => [
                [
                    'id'             => 'appointment',
                    'title'          => 'تنظیمات',
                    'class'          => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'id'             => 'appointment_address',
                    'title'          => 'بخش رسید پیامکی به کاربر',
                    'class'          => '',
                    'before_content' => '',
                    'after_content'  => '',
                ]
            ],
            'class'          => '',
            'before_content' => '',
            'after_content'  => '',
        ],
    ],
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    // <<<<<<<<<<<<<<<<<<<<<<<<<<< ################################################ >>>>>>>>>>>>>>>>>>>>>>>>>>>
    'content' => [
        [ // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< single content >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            'id'             => 'appointment',
            'title'          => 'تنظیمات',
            'class'          => '',
            'before_content' => '',
            'after_content'  => '',
            'settings'       => [
                // ------------ setting ------------
                [
                    'title'          => 'وضعیت سیستم برای کاربران',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_USER_STATUS ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_USER_STATUS,
                        'items' => [
                            '0' => 'غیرفعال',
                            '1' => 'فعال',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'فعال بودن نوبت دهی برای کاربران مرکز تماس',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_AGENT_STATUS ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_AGENT_STATUS,
                        'items' => [
                            '0' => 'غیرفعال',
                            '1' => 'فعال',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'متن نمایشی در صورت غیر فعال بودن نوبت دهی',
                    'value'          => get_option( 'MESSAGE_IF_TURN_APPOINTMENT_IS_INACTIVE' ),
                    'type'           => 'textarea',
                    'input_args'     => [
                        'name' => 'MESSAGE_IF_TURN_APPOINTMENT_IS_INACTIVE',
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => 'این متن در صورتی که نوبت دهی را برای کارکنان مرکز تماس غیر فعال کرده اید به انها نمایش داده خواهد شد',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'متن پیامک پیشفرض',
                    'value'          => get_option( 'user_appointment_sms_message' ),
                    'type'           => 'textarea',
                    'input_args'     => [
                        'name' => 'user_appointment_sms_message',
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '{نام}: نام کاربر - {نام-خانوادگی}: نام خانوادگی کاربر - {جنسیت}: آقا یا خانوم - {تاریخ}: تاریخ نوبت - {ساعت}: ساعت نوبت - {اپراتور}: کاربر قبت کننده نوبت - {پزشک}: پزشک - {بخش}: عنوان بخش - جزئیات نوبت : {جزئیات-نوبت}',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'آیا به کاربران پس از کنسل شدن نوبت پیامک ارسال شود',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CANCEL ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CANCEL,
                        'items' => [
                            '0' => 'ارسال نشود',
                            '1' => 'ارسال شود',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'متن پیامک پس از کنسل شدن نوبت',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CANCEL_MESSAGE ),
                    'type'           => 'textarea',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CANCEL_MESSAGE,
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '{نام}: نام کاربر - {نام-خانوادگی}: نام خانوادگی کاربر - {جنسیت}: آقا یا خانوم - {تاریخ}: تاریخ نوبت - {ساعت}: ساعت نوبت - {اپراتور}: کاربر قبت کننده نوبت - {پزشک}: پزشک - {بخش}: عنوان بخش - جزئیات نوبت : {جزئیات-نوبت}',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'آیا پس از ویرایش زمان نوبت به کاربران پیامک ارسال شود؟',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CHANGE_TIME ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CHANGE_TIME,
                        'items' => [
                            '0' => 'ارسال نشود',
                            '1' => 'ارسال شود',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'متن پیامک پس از ویرایش زمان نوبت',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CHANGE_TIME_MESSAGE ),
                    'type'           => 'textarea',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SETTING_SMS_AFTER_CHANGE_TIME_MESSAGE,
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '{نام}: نام کاربر - {نام-خانوادگی}: نام خانوادگی کاربر - {جنسیت}: آقا یا خانوم - {تاریخ}: تاریخ نوبت - {ساعت}: ساعت نوبت - {اپراتور}: کاربر قبت کننده نوبت - {پزشک}: پزشک - {بخش}: عنوان بخش - جزئیات نوبت : {جزئیات-نوبت}',
                    'before_content' => '',
                    'after_content'  => '',
                ],


            ],
        ], // #-- end block --#
        [ // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< single content >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            'id'             => 'appointment_address',
            'title'          => 'تنظیمات نمایش جزئیات نوبت پیامک شده به کاربر',
            'class'          => '',
            'before_content' => '',
            'after_content'  => '',
            'settings'       => [
                // ------------ setting ------------


                [
                    'title'          => ' آیا ادرس به کاربران پس از دریافت نوبت نمایش داده شود؟',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::APPOINTMENT_SHOW_CLINIC_ADDRESS ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::APPOINTMENT_SHOW_CLINIC_ADDRESS,
                        'items' => [
                            '0' => 'نمایش داده نشود',
                            '1' => 'آدرس نمایش داده شود',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => ' آیا امکان ارسال جزئیات نوبت در صفحه رسید به دیگران فراهم باشد؟',
                    'value'          => get_option( Modules\Appointment\Entities\Appointment::SHARE_DETAIL ),
                    'type'           => 'radio',
                    'input_args'     => [
                        'name' => Modules\Appointment\Entities\Appointment::SHARE_DETAIL,
                        'items' => [
                            '0' => 'فعال نباشد',
                            '1' => 'فعال باشد',
                        ]
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => '',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'ادرس کلینیک',
                    'value'          => get_option( 'user_appointment_clinic_address' ),
                    'type'           => 'textarea',
                    'input_args'     => [
                        'name' => 'user_appointment_clinic_address',
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => 'این آدرس برای کاربران پس از دریافت نوبت ارسال میشود',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'ادرس نقشه گوگل',
                    'value'          => get_option( 'user_appointment_clinic_map' ),
                    'type'           => 'input',
                    'input_args'     => [
                        'name' => 'user_appointment_clinic_map',
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => 'این ادرس از طریقه نقشه گوگل باید درج شود',
                    'before_content' => '',
                    'after_content'  => '',
                ],
                [
                    'title'          => 'شماره تلفن نمایشی در بخش گزارش نوبت به کاربر',
                    'value'          => get_option( 'user_appointment_tell' ),
                    'type'           => 'input',
                    'input_args'     => [
                        'name' => 'user_appointment_tell',
                    ],
                    'list_item_args' => '',
                    'class'          => '',
                    'id'             => '',
                    'description'    => 'این شماره فقط در صفحه گزارش نوبت به کاربر نمایش داده میشدو',
                    'before_content' => '',
                    'after_content'  => '',
                ],

            ],
        ], // #-- end block --#
    ],
];
