<?php

return [
    'name' => 'Blog',
    'Permission' => [
        'group' => [
            'name' => 'admin_blog_access',
            'display_name' => 'بخش اخبار',
            'section' => 'admin_section'
        ],
        'permissions' => [
            'اخبار' => [
                'view_admin_blog' => 'مشاهده بخش اخبار',
                'add_admin_blog' => 'افزودن خبر',
                'edit_admin_blog' => 'ویرایش خبر',
                'delete_admin_blog' => 'حذف خبر',
                'change_group_admin_blog' => 'تغییر گروه خبر',
                'upload_admin_blog' => 'آپلود عکس خبر',
                'remove_image_admin_blog' => 'حذف عکس خبر',
                'change_status_admin_blog' => 'تغییر وضعیت خبر',
            ],
            'دسته بندی اخبار' => [
                'view_admin_blog_category' => 'مشاهده دسته بندی ها',
                'add_admin_blog_category' => 'افزودن دسته بندی',
                'edit_admin_blog_category' => 'ویرایش دسته بندی',
                'delete_admin_blog_category' => 'حذف دسته بندی',
                'delete_group_admin_blog_category' => 'حذف گروه دسته بندی',
            ],
            'نظرات' => [
                'view_admin_blog_comments' => 'مشاهده نظرات',
                'add_admin_blog_comments' => 'افزودن نظر',
                'edit_admin_blog_comments' => 'ویرایش نظر',
                'change_group_admin_blog_comments' => 'تغییر گروه نظرات',
                'change_status_admin_blog_comments' => 'تغییر وضعیت نظرات',
            ],
            'Radio' => [
                'admin_blog_access' => [
                    'admin_all_blog_access' => 'دسترسی به تمام اخبار',
                    'admin_limit_blog_access' => 'دسترسی به اخبار کاربر'
                ]
            ]
        ],
    ],
    'Menu' => [
        'title' => 'اخبار',
        'gate' => 'admin_blog_access',
        'icon' => 'fa fa-newspaper-o',
        'route' => null,
        'has_child' => true,
        'children' => [
            [
                'title' => 'لیست اخبار',
                'gate' => 'view_admin_blog',
                'icon'  => 'fa fa-list',
                'route' => 'admin.blog.index',
                'has_child' => false,
            ],
            [
                'title' => 'اضافه کردن خبر',
                'gate' => 'add_admin_blog',
                'icon'  => 'fa fa-plus-circle',
                'route' => 'admin.blog.create',
                'has_child' => false,
            ],
            [
                'title' => 'موضوعات',
                'gate' => 'view_admin_blog_category',
                'icon'  => 'fa fa-file-text-o',
                'route' => 'admin.blog.category.index',
                'has_child' => false,
            ],
            [
                'title' => 'نظرات',
                'gate' => 'view_admin_blog_comments',
                'icon'  => 'fa fa-commenting-o',
                'route' => 'admin.blog.comments.index',
                'has_child' => false,
            ],
        ]
    ]
];
