<?php
return [
	'Menu' => [
		[
			'title'     => 'کاربران',
			'gate'      => 'view_admin_user',
			'has_child' => true,
			'icon'      => 'fa fa-user',
			'route'     => null,
			'children'  => [
				[
					'title'     => 'لیست کاربران',
					'icon'      => 'fa fa-list',
					'route'     => 'admin.user.index',
					'gate'      => 'view_admin_user',
					'has_child' => false,
				],
				[
					'title'     => 'اضافه کردن کاربر',
					'icon'      => 'fa fa-plus',
					'route'     => 'admin.user.create',
					'gate'      => 'add_admin_user',
					'has_child' => false,
				],
			],
		],
		[
			'title'     => 'نقش‌ها',
			'gate'      => 'view_admin_role',
			'has_child' => false,
			'icon'      => 'fa fa-user',
			'route'     => 'admin.role.index',
		]
	],
	'Cat'  => [
//		[
//			'title'   => 'مدیریت محتوا',
//			'icon'    => 'fa fa-remove',
//			'modules' => [
//				'Page','Blog','Heroes','Aparat'
//			]
//		]
	]
];