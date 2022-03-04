<?php
return [
	'name'       => 'Page',
	'Permission' => [
		'group'       => [
			'name'         => 'admin_educational_access',
			'display_name' => 'بخش آموزشی'
		],
		'permissions' => [
		]
	],
	'Menu'       => [
		'title'     => 'صفحات',
		'gate'      => 'view_admin_basic',
		'has_child' => true,
		'icon'      => 'fa fa-bookmark',
		'route'     => null,
		'children'  => [
			[
				'title'     => 'پایه‌ها',
				'icon'      => 'fa fa-list',
				'route'     => 'admin.basic.index',
				'gate'      => 'view_admin_basic',
				'has_child' => true,
				'children'  => [
					[
						'title' => 'لیست پایه‌ها',
						'icon'  => 'fa fa-list',
						'route' => 'admin.basic.index',
						'gate'  => 'view_admin_basic',
						'has_child' => false,
					]
				]
			],
			[
				'title'     => 'اضافه کردن پایه',
				'icon'      => 'fa fa-list',
				'route'     => 'admin.basic.index',
				'gate'      => 'view_admin_basic',
				'has_child' => false,
			]
		]
	]
];