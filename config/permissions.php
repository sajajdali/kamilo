<?php
return [
	//role permissions
	[
		'group'       => [
			'name'         => 'view_admin_role',
			'display_name' => 'نقش‌ها',
			'section'      => 'admin_section'
		],
		'permissions' => [
			"ایجاد"  => [
				'add_admin_role' => 'ایجاد نقش جدید',
			],
			"ویرایش" => [
				'edit_admin_role' => 'ویرایش نقش‌ها',
			],
			"حذف"    => [
				"delete_admin_role" => "حذف نقش"
			]
		]
	],
	//user permissions
	[
		'group'       => [
			'name'         => 'view_admin_user',
			'display_name' => 'کاربران',
			'section'      => 'admin_section'
		],
		'permissions' => [
			"ایجاد"  => [
				'add_admin_user' => 'ایجاد کاربر جدید',
			],
			"ویرایش" => [
				'edit_admin_user' => 'ویرایش کاربران',
			],
			"حذف"    => [
				"delete_admin_user" => "حذف کاربر"
			],
			'Radio'      => [
				"admin_user_radio_access" => [
					'admin_all_user_access'   => 'دسترسی به تمام کاربران',
					'admin_limit_user_access' => 'دسترسی به کاربران اضافه شده توسط مدیر',
				],
				"admin_user_radio_role_access" => [
					'admin_all_role_user_access'   => 'دسترسی به تمام نقش‌ها',
					'admin_limit_role_user_access' => 'دسترسی به نقش پیشفرض',
				]
			]
		]
	],
];