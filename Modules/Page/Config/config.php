<?php
return [
	'name'       => 'Page',
	'Permission' => [
		'group'       => [
			'name'         => 'admin_page_access',
			'display_name' => 'بخش صفحات',
			'section'      => 'admin_section'
		],
		'permissions' => [
			"مشاهده"     => [
				'view_admin_pages' => 'مشاهده بخش صفحات'
			],
			"ویرایش"     => [
				'edit_admin_pages' => 'ویرایش صفحات'
			],
			"اضافه کردن" => [
				'add_admin_pages' => 'اضافه کردن صفحه'
			],
			"حذف"        => [
				'delete_admin_pages' => 'حذف صفحه'
			],
			'Radio'      => [
				"admin_page_access" => [
					'admin_all_page_access'   => 'دسترسی به تمام صفحات',
					'admin_limit_page_access' => 'دسترسی به صفحات خود کاربر',
				]
			]
		]
	],
	'Menu'       => [
		'title'     => 'صفحات',
		'gate'      => 'admin_page_access',
		'has_child' => true,
		'icon'      => 'fa fa-list-ol',
		'route'     => null,
		'children'  => [
			[
				'title'     => 'لیست صفحات',
				'icon'      => 'fa fa-list',
				'route'     => 'admin.pages.index',
				'gate'      => 'view_admin_pages',
				'has_child' => false,
			],
			[
				'title'     => 'اضافه کردن صفحه',
				'icon'      => 'fa fa-plus',
				'route'     => 'admin.pages.create',
				'gate'      => 'add_admin_pages',
				'has_child' => false,
			],
		]
	]
];

