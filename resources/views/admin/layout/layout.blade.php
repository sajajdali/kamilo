<!DOCTYPE html>
<html>
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <meta name="author" content="selakpardaz">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Font CSS (Via CDN) -->
        <!-- Admin Forms CSS -->
        <link rel="stylesheet" type="text/css"
              href="{{ asset('admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}">
        <!-- Fontawsome CSS -->
        <link rel="stylesheet" type="text/css"
              href="{{ asset('admin/files/assets/fonts/font-awesome-4.6.3/css/font-awesome.css') }}">
        <link rel="stylesheet" type="text/css"
              href="{{ asset('admin/files/assets/fonts/yekan/yekan-css.css') }}">
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css"
              href="{{ asset('admin/files/assets/skin/default_skin/css/theme.css?ver=1') }}">
        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/my-edit.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/my-edit-res.css') }}">-->
        @stack('style')
        <style>
            div.switch label::after {
                top : 0 !important;
            }
        </style>
        {{--Custom Plugins for all pages--}}
        <link href="{{ asset('admin/files/select2/select2.min.css') }}" rel="stylesheet" />
        @php
            Eventy::action('cms_admin_head');
        @endphp
    </head>
    <body class="@stack('body_class')">
        <!-- Start: Main -->
        <div id="main">
            <!-- Start: Header -->
            <header class="navbar navbar-fixed-top bg-light">
                <div class="navbar-branding">
                    <a id="panel-brand" class="navbar-brand yekan rtl txt_center " href="#"> پنل مدیریت</a>
                    <span id="toggle_sidemenu_l" class="glyphicons glyphicons-show_lines"></span>
                    <ul class="nav navbar-nav pull-right hidden">
                        <li>
                            <a href="#" class="sidebar-menu-toggle">
                                <span class="octicon octicon-ruby fs20 mr10 pull-right "></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle fw600 p15 yekan" data-toggle="dropdown">
                            <span class="caret caret-tp hidden-xs"></span>
                            <span>{{ \Auth::user()->full_name}}</span>
                            <img src="{{ \Auth::user()->avatar }}"
                                 alt="avatar" class="mw30 br64 mr15">
                        </a>
                        <ul class="dropdown-menu dropdown-persist pn w250 bg-white" role="menu">
                            <li class="br-t of-h">
                                <a href="{{ \Auth::user()->avatar}}"
                                   class="fw600 p12 animated animated-short fadeInUp rtl txt_right  yekan">
                                    <span class="fa fa-gear pl5"></span>
                                    ویرایش پروفایل
                                </a>
                            </li>
                            <li class="br-t of-h">
                                <a href="{{ url('/logout') }}"
                                   class="fw600 p12 animated animated-short fadeInDown rtl txt_right yekan">
                                    <span class="fa fa-power-off pl5"></span>
                                    خروج
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </header>
            <!-- End: Header -->
            <!-- Start: Sidebar -->
            <aside id="sidebar_left" class="nano nano-primary" style="position: absolute;right:0">
                <div class="nano-content">
                    <!-- Start: Sidebar Header -->
                    <header class="sidebar-header">
                        <div class="user-menu">
                            <div class="row text-center mbn">
                                <div class="col-xs-4">
                                    <a href="dashboard.html" class="text-primary" data-toggle="tooltip" data-placement="top"
                                       title="Dashboard">
                                        <span class="glyphicons glyphicons-home"></span>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="pages_messages.html" class="text-info" data-toggle="tooltip" data-placement="top"
                                       title="Messages">
                                        <span class="glyphicons glyphicons-inbox"></span>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="pages_profile.html" class="text-alert" data-toggle="tooltip" data-placement="top"
                                       title="Tasks">
                                        <span class="glyphicons glyphicons-bell"></span>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="pages_timeline.html" class="text-system" data-toggle="tooltip" data-placement="top"
                                       title="Activity">
                                        <span class="glyphicons glyphicons-imac"></span>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="pages_profile.html" class="text-danger" data-toggle="tooltip" data-placement="top"
                                       title="Settings">
                                        <span class="glyphicons glyphicons-settings"></span>
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="pages_gallery.html" class="text-warning" data-toggle="tooltip" data-placement="top"
                                       title="Cron Jobs">
                                        <span class="glyphicons glyphicons-restart"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- End: Sidebar Header -->
                @include('admin.layout.sidebar')
                
                <!-- sidebar menu -->
                    {{--<ul class="nav sidebar-menu">--}}
                    {{--@if(Auth::user()->role_id != \App\Role::ROLE_ADVISER)--}}
                    
                    {{--<li {{ (Request::is('admin/dashbord') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/dashbord') }}">--}}
                    {{--<span class="glyphicons glyphicons-home pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">داشبورد مدیریت</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endif--}}
                    {{--@can('check_access','users')--}}
                    {{--<li class="sidebar-label pt15">کاربران</li>--}}
                    {{--@can('check_access','roles')--}}
                    {{--<li {{ Request::is('admin/user/roles') ? 'class=active' : '' }}>--}}
                    {{--<a href="{{ url('admin/user/roles') }}">--}}
                    {{--<span class="glyphicons glyphicons-bookmark pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">مدیریت نقش ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--<li>--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/users') || Request::is('admin/users/add'))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-user"></span>--}}
                    {{--<span class="sidebar-title right yekan">کاربران</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--<li {{ (Request::is('admin/users') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/users') }}">--}}
                    {{--<span class="glyphicons glyphicons-parents"></span>داشبورد</a>--}}
                    {{--</li>--}}
                    {{--@can('check_access','add_user')--}}
                    {{--<li {{ (Request::is('admin/users/add') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/users/add') }}">--}}
                    {{--<span class="glyphicons glyphicons-user_add"></span>اضافه کردن کاربر جدید</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','admin_notification')--}}
                    {{--<li {{ (Request::is('admin/notification') || Request::is('admin/notification/*')) ? 'class=active' : '' }}>--}}
                    {{--<a href="{{ url('admin/notification') }}">--}}
                    {{--<span class="glyphicons glyphicons-direction pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan"> پیام ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@if(Auth::user()->role_id != \App\Role::ROLE_ADVISER)--}}
                    {{--<li id="system_li_title" class="sidebar-label pt20">سیستم</li>--}}
                    {{--@endif--}}
                    
                    
                    {{--@can('check_access','blog')--}}
                    {{--<li class="system_li">--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/blog') || Request::is('admin/blog/add') || Request::is('admin/blog/category') || Request::is('admin/blog/category/*') || Request::is('admin/blog/comments')))--}}
                    {{--{{ 'menu-open' }}--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-blogger "></span>--}}
                    {{--<span class="sidebar-title right yekan">اخبار</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--<li {{ (Request::is('admin/blog') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog') }}">--}}
                    {{--<span class="glyphicons glyphicons-shopping_cart"></span> داشبورد--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@can('check_access','add_blog')--}}
                    {{--<li {{ (Request::is('admin/blog/add') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog/add') }}">--}}
                    {{--<span class="glyphicons glyphicons-tags"></span> ارسال خبر </a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','blog_category')--}}
                    {{--<li {{ ((Request::is('admin/blog/category') || Request::is('admin/blog/category/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog/category') }}">--}}
                    {{--<span class="glyphicons glyphicons-coins"></span> موضوعات </a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','blog_comment')--}}
                    {{--<li {{ (Request::is('admin/blog/comments') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog/comments') }}">--}}
                    {{--<span class="glyphicons glyphicons-comments"></span>نظرات</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','admin_blog_field')--}}
                    {{--<li {{ (Request::is('admin/blog/field') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog/field') }}">--}}
                    {{--<span class="glyphicons glyphicons-list"></span>رشته ها</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','admin_blog_rate')--}}
                    {{--<li {{ (Request::is('admin/blog/rate') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/blog/rate') }}">--}}
                    {{--<span class="glyphicons glyphicons-list"></span>مقام ها</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','pages')--}}
                    {{--<li class="system_li {{ ((Request::is('admin/pages') || Request::is('admin/pages/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/pages') }}">--}}
                    {{--<span class="glyphicons glyphicons-comments"></span>--}}
                    {{--<span class="sidebar-title right yekan">صفحات سایت</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','personalization')--}}
                    {{--<li class="system_li">--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/menu') || Request::is('admin/tab') || Request::is('admin/tab/edit/*') || Request::is('admin/widget') || Request::is('admin/widget/*'))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-magic"></span>--}}
                    {{--<span class="sidebar-title right yekan">شخصی سازی</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--@can('check_access','menu')--}}
                    {{--<li {{ (Request::is('admin/menu') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/menu') }}">--}}
                    {{--<span class="glyphicons glyphicons-show_thumbnails"></span>منو</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','tab')--}}
                    {{--<li {{ ((Request::is('admin/tab') || Request::is('admin/tab/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/tab') }}">--}}
                    {{--<span class="glyphicons glyphicons-sort"></span>تب</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','admin_widget')--}}
                    {{--<li {{ ((Request::is('admin/widget') || Request::is('admin/widget/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/widget') }}">--}}
                    {{--<span class="glyphicons glyphicons-show_thumbnails"></span>متن های ثابت</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','admin_transaction')--}}
                    {{--<li class="system_li {{ ((Request::is('admin/transaction') || Request::is('admin/transaction/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/transaction') }}">--}}
                    {{--<span class="glyphicons glyphicons-database_plus"></span>--}}
                    {{--<span class="sidebar-title right yekan">تراکنش ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    
                    {{--@endcan--}}
                    {{--@can('check_access','admin_payment')--}}
                    {{--<li class="system_li {{ ((Request::is('admin/payment') || Request::is('admin/payment/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/payment') }}">--}}
                    {{--<span class="glyphicons glyphicons-database_plus"></span>--}}
                    {{--<span class="sidebar-title right yekan">پرداخت ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    
                    
                    {{--@can('check_access','admin_sms')--}}
                    {{--<li class="system_li">--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/optimization/*'))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="fa fa-envelope-o"></span>--}}
                    {{--<span class="sidebar-title right yekan">پیامک ها</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--@can('check_access','admin_optimization_sms')--}}
                    {{--<li class=" {{ ((Request::is('admin/optimization/smsQueue') || Request::is('admin/optimization/smsQueue/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/optimization/smsQueue') }}">--}}
                    {{--<span class="fa fa-envelope-o pl5"></span>--}}
                    {{--آرشیو پیامک ها ارسالی--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','admin_optimization_smsAuto')--}}
                    {{--<li class=" {{ ((Request::is('admin/optimization/smsAuto') || Request::is('admin/optimization/smsAuto/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/optimization/smsAuto') }}">--}}
                    {{--<span class="fa fa-magic pl5"></span>--}}
                    {{--پیامک های اتوماتیک--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    
                    
                    
                    {{--@can('check_access','uploaded_files')--}}
                    {{--<li class="system_li {{ ((Request::is('admin/uploaded_file') || Request::is('admin/uploaded_file/*')) ? 'active' : '') }}">--}}
                    {{--<a href="{{ url('admin/uploaded_file') }}">--}}
                    {{--<span class="glyphicons glyphicons-file pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">فایل ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--<li class="sidebar-label pt20 yekan">رژیم</li>--}}
                    {{--@can('check_access','admin_user_diet')--}}
                    {{--<li @if(Request::is('admin/userDiet') || Request::is('admin/userDiet/*')))--}}
                    {{--class="active" @endif>--}}
                    {{--<a href="{{ url('admin/userDiet') }}">--}}
                    {{--<span class="glyphicons glyphicons-notes_2 pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">لیست رژیم های دریافتی</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li @if(Request::is('admin/offlineDiet') || Request::is('admin/offlineDiet/*')))--}}
                    {{--class="active" @endif>--}}
                    {{--<a href="{{ url('admin/offlineDiet') }}">--}}
                    {{--<span class="glyphicons glyphicons-heart pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">رژیم‌های آفلاین</span>--}}
                    {{--@if(\App\Request_rejim_program::where([['offline','1'],['active','1'],['link',null]])->exists())--}}
                    {{--<span class="label label-s label-rounded bg-danger yekan rtl"--}}
                    {{--style="margin-left: 1.35em">--}}
                    {{--{{ ta_persian_num(\App\Request_rejim_program::where([['offline','1'],['active','1'],['link',null]])->count()) }}--}}
                    {{--</span>--}}
                    {{--@endif--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li @if(Request::is('admin/recall')))--}}
                    {{--class="active" @endif>--}}
                    {{--<a href="{{ url('admin/recall') }}">--}}
                    {{--<span class="glyphicons glyphicons-fast_food pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">فرم یادآمد</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','meal')--}}
                    {{--<li class="system_li">--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/meal') || Request::is('admin/meal/add') || Request::is('admin/meal/category') || Request::is('admin/meal/category/*') )--}}
                    {{--{{ 'menu-open' }}--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-blogger "></span>--}}
                    {{--<span class="sidebar-title right yekan">وعده های غذایی</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--<li {{ (Request::is('admin/meal') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/meal') }}">--}}
                    {{--<span class="glyphicons glyphicons-shopping_cart"></span> داشبورد--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@can('check_access','add_blog')--}}
                    {{--<li {{ (Request::is('admin/meal/add') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/meal/add') }}">--}}
                    {{--<span class="glyphicons glyphicons-tags"></span> اضافه کردن وعده </a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','meal_category')--}}
                    {{--<li {{ ((Request::is('admin/meal/category') || Request::is('admin/meal/category/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/meal/category') }}">--}}
                    {{--<span class="glyphicons glyphicons-coins"></span> الگوهای رژیم </a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@can('check_access','admin_meal_setting')--}}
                    {{--<li @if(Request::is('admin/meal/setting')))--}}
                    {{--class="active" @endif>--}}
                    {{--<a href="{{ url('admin/meal/setting') }}">--}}
                    {{--<span class="fa fa-cog pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">تنظیمات ٰرژیم</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_shop')--}}
                    
                    {{--<li class="sidebar-label pt20 yekan">فروشگاه</li>--}}
                    
                    {{--@can('check_access','admin_shop_cart')--}}
                    {{--<li {{ ((Request::is('admin/shop/factor') || Request::is('admin/shop/factor/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/shop/factor') }}">--}}
                    {{--<span class="glyphicons glyphicons-shopping_cart pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">لیست فاکتور ها</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','admin_shop_setting')--}}
                    {{--<li>--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/shop/setting/*')))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-cogwheels"></span>--}}
                    {{--<span class="sidebar-title right yekan">تنظیمات فروشگاه</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    
                    
                    {{--@can('check_access','admin_shop_setting_product')--}}
                    {{--<li {{ (Request::is('admin/shop/setting/product') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/shop/setting/product') }}">--}}
                    {{--<span class="glyphicons glyphicons-list ">--}}
                    {{--محصول ها--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_shop_setting_category')--}}
                    {{--<li {{ (Request::is('admin/shop/setting/category') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/shop/setting/category') }}">--}}
                    {{--<span class="glyphicons glyphicons-list ">--}}
                    {{--دسته بندی ها--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_place_city')--}}
                    {{--<li {{ (Request::is('admin/place/city') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/place/city') }}">--}}
                    {{--<span class="glyphicons glyphicons-list ">--}}
                    {{--شهرها--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_shop_setting')--}}
                    {{--<li {{ (Request::is('admin/shop/setting/list') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/shop/setting/list') }}">--}}
                    {{--<span class="glyphicons glyphicons-list ">--}}
                    {{--تنظیمات--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--</ul>--}}
                    
                    
                    {{--</li>--}}
                    
                    
                    {{--@endcan--}}
                    
                    
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_telegramBot')--}}
                    
                    {{--<li class="sidebar-label pt20 yekan">ربات تلگرام</li>--}}
                    {{--<li>--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/telegramBot/*')))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-cogwheels"></span>--}}
                    {{--<span class="sidebar-title right yekan">تنظیمات</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    
                    
                    {{--@can('check_access','admin_telegramBot_manager')--}}
                    {{--<li {{ (Request::is('admin/telegramBot/manager/*') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/telegramBot/manager') }}">--}}
                    {{--<span class="glyphicons glyphicons-list ">--}}
                    {{--مدیریت--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_telegramBot_step')--}}
                    {{--<li {{ (Request::is('admin/telegramBot/step/*') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/telegramBot/step') }}">--}}
                    {{--<span class="fa fa-arrow-circle-right ">--}}
                    {{--مرحله--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','admin_telegramBot_‌keyboard')--}}
                    {{--<li {{ (Request::is('admin/telegramBot/keyboard/*') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/telegramBot/keyboard') }}">--}}
                    {{--<span class="fa fa-keyboard-o ">--}}
                    {{--کیبورد--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@can('check_access','admin_telegramBot_alert')--}}
                    {{--<li {{ (Request::is('admin/telegramBot/alert/*') ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/telegramBot/alert') }}">--}}
                    {{--<span class="fa fa-bell-o  ">--}}
                    {{--اخطار و پیام--}}
                    {{--</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    {{--</ul>--}}
                    
                    
                    {{--</li>--}}
                    
                    
                    
                    
                    {{--@endcan--}}
                    
                    
                    
                    
                    
                    
                    
                    
                    {{--@can('check_access','admin_report')--}}
                    {{--<!-- sidebar resources -->--}}
                    {{--<li class="sidebar-label pt20 yekan">گزارش ها</li>--}}
                    
                    {{--@can('check_access','admin_summary_report')--}}
                    {{--<li {{ (Request::is('admin/report/summary') || Request::is('admin/report/summary/*')) ? 'class=active' : '' }}>--}}
                    {{--<a href="{{ url('admin/report/summary') }}">--}}
                    {{--<span class="glyphicons glyphicons-list pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">خلاصه آمار</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    {{--@endcan--}}
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    {{--@can('check_access','setting')--}}
                    {{--<!-- sidebar resources -->--}}
                    {{--<li class="sidebar-label pt20 yekan">تنظیمات</li>--}}
                    
                    {{--@can('check_access','site_setting')--}}
                    {{--<li>--}}
                    {{--<a class="accordion-toggle--}}
                    {{--@if(Request::is('admin/setting/general')))--}}
                    {{--menu-open--}}
                    {{--@endif--}}
                    {{--" href="#">--}}
                    {{--<span class="glyphicons glyphicons-cogwheels"></span>--}}
                    {{--<span class="sidebar-title right yekan">تنظیمات عمومی</span>--}}
                    {{--<span class="caret"></span>--}}
                    {{--</a>--}}
                    {{--<ul class="nav sub-nav rtl txt_right yekan">--}}
                    {{--<li @if(Request::is('admin/setting/general'))) class="active" @endif>--}}
                    {{--<a href="{{ url('admin/setting/general') }}">--}}
                    {{--<span class="glyphicons glyphicons-tags"></span>سایت</a>--}}
                    {{--</li>--}}
                    
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    {{--@endcan--}}
                    
                    
                    {{--@can('check_access','admin_staticText')--}}
                    {{--<li {{ ((Request::is('admin/staticText') || Request::is('admin/staticText/*')) ? 'class=active' : '') }}>--}}
                    {{--<a href="{{ url('admin/staticText') }}">--}}
                    {{--<span class="glyphicons glyphicons-file pl5"></span>--}}
                    {{--<span class="sidebar-title right yekan">متن های ثابت </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--@endcan--}}
                    
                    
                    
                    
                    
                    {{--<!-- sidebar bullets -->--}}
                    {{--<li class="sidebar-label pt20">دسترسی سریع</li>--}}
                    {{--<li class="sidebar-proj">--}}
                    {{--<a href="{{ url('/') }}" target="_blank">--}}
                    {{--<span class="fa fa-dot-circle-o text-primary"></span>--}}
                    {{--<span class="sidebar-title  right yekan">مشاهده سایت</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    
                    {{--</ul>--}}
                </div>
            </aside>
            <!-- Start: Content-Wrapper -->
            <section id="content_wrapper">
                @yield('content')
            </section>
            <!-- Start: Right Sidebar -->
            <aside id="sidebar_right" class="nano">
                <div class="sidebar_right_content nano-content">
                    <div class="tab-block sidebar-block br-n">
                        <ul class="nav nav-tabs tabs-border nav-justified hidden">
                            <li class="active">
                                <a href="#sidebar-right-tab1" data-toggle="tab">Tab 1</a>
                            </li>
                            <li>
                                <a href="#sidebar-right-tab2" data-toggle="tab">Tab 2</a>
                            </li>
                            <li>
                                <a href="#sidebar-right-tab3" data-toggle="tab">Tab 3</a>
                            </li>
                        </ul>
                        <div class="tab-content br-n">
                            <div id="sidebar-right-tab1" class="tab-pane active">
                                <h5 class="title-divider text-muted mb20"> Server Statistics
                                    <span class="pull-right"> 2013 <i
                                                class="fa fa-caret-down ml5"></i> </span>
                                </h5>
                                <div class="progress mh5">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="45"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 44%">
                                        <span class="fs11">DB Request</span>
                                    </div>
                                </div>
                                <div class="progress mh5">
                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="45"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 84%">
                                        <span class="fs11 text-left">Server Load</span>
                                    </div>
                                </div>
                                <div class="progress mh5">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 61%">
                                        <span class="fs11 text-left">Server Connections</span>
                                    </div>
                                </div>
                                <h5 class="title-divider text-muted mt30 mb10">Traffic Margins</h5>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <h3 class="text-primary mn pl5">132</h3>
                                    </div>
                                    <div class="col-xs-7 text-right">
                                        <h3 class="text-success-dark mn">
                                            <i class="fa fa-caret-up"></i>
                                            13.2%
                                        </h3>
                                    </div>
                                </div>
                                <h5 class="title-divider text-muted mt25 mb10">Database Request</h5>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <h3 class="text-primary mn pl5">212</h3>
                                    </div>
                                    <div class="col-xs-7 text-right">
                                        <h3 class="text-success-dark mn">
                                            <i class="fa fa-caret-up"></i>
                                            25.6%
                                        </h3>
                                    </div>
                                </div>
                                <h5 class="title-divider text-muted mt25 mb10">Server Response</h5>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <h3 class="text-primary mn pl5">82.5</h3>
                                    </div>
                                    <div class="col-xs-7 text-right">
                                        <h3 class="text-danger mn">
                                            <i class="fa fa-caret-down"></i>
                                            17.9%
                                        </h3>
                                    </div>
                                </div>
                                <h5 class="title-divider text-muted mt40 mb20"> Server Statistics
                                    <span
                                            class="pull-right text-primary fw600">USA</span>
                                </h5>
                                <div id="sidebar-right-map" class="hide-jzoom" style="width: 100%; height: 180px;"></div>
                            </div>
                            <div id="sidebar-right-tab2" class="tab-pane"></div>
                            <div id="sidebar-right-tab3" class="tab-pane"></div>
                        </div>
                        <!-- end: .tab-content -->
                    </div>
                </div>
            </aside>
            <!-- End: Right Sidebar -->
        </div>
        <!-- End: Main -->
        <!-- BEGIN: PAGE SCRIPTS -->
        <style>
            #message-table > tbody > tr.highlight > td {
                background-color : #FFFEF0;
            }
        </style>
        <!-- jQuery -->
        <script type="text/javascript" src="{{ asset('admin/files/vendor/jquery/jquery-1.11.1.min.js') }}"></script>
        <script type="text/javascript"
                src="{{ asset('admin/files/vendor/jquery/jquery_ui/jquery-ui.min.js') }}"></script>
        <!-- Bootstrap -->
        <script type="text/javascript"
                src="{{ asset('admin/files/assets/js/bootstrap/bootstrap.min.js') }}"></script>
        <!-- FileUpload JS -->
        <script type="text/javascript"
                src="{{ asset('admin/files/vendor/plugins/fileupload/fileupload.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/files/assets/js/bootstrap/holder.min.js') }}"></script>
        <!-- Tagmanager JS -->
        <script type="text/javascript"
                src="{{ asset('admin/files/vendor/plugins/tagsinput/tagsinput.min.js') }}"></script>
        <!-- Theme Javascript -->
        <script type="text/javascript" src="{{ asset('admin/files/assets/js/utility/utility.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/files/assets/js/main.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/files/assets/js/demo.js') }}"></script>
        <script type="text/javascript">
            jQuery( document ).ready( function () {
                
                "use strict";
                
                // Init Theme Core
                Core.init();
                
                // Init Theme Core
                Demo.init();
                
                // grant file-upload preview onclick functionality
                $( '.fileupload-preview' ).on( 'click', function () {
                    $( '.btn-file > input' ).click();
                } );
                
                // select dropdowns - placeholder like creation
                var selectList = $( '.admin-form select' );
                selectList.each( function ( i, e ) {
                    $( e ).on( 'change', function () {
                        if ( $( e ).val() == "0" ) $( e ).addClass( "empty" );
                        else $( e ).removeClass( "empty" );
                    } );
                } );
                selectList.each( function ( i, e ) {
                    $( e ).change();
                } );
                
                // Init tagsinput plugin
                $( "input#tagsinput" ).tagsinput( {
                    tagClass: function ( item ) {
                        return 'label bg-primary light';
                    }
                } );
                
            } );
        </script>
    @stack('scripts')
    <!-- END: PAGE SCRIPTS -->
        <script src="{{ asset('admin/files/select2/select2.min.js') }}"></script>
        <script>
            $( '.select2' ).select2( {
                dir: "rtl"
            } );

            if ( $( '.system_li' ).length < 1 )
            {
                $( '#system_li_title' ).remove();
            }
            $( '.nav.sidebar-menu li.active' ).parents( '.nav.sidebar-menu >li:has(".active")' ).find( '>.accordion-toggle' ).addClass( 'menu-open' );

            function openLi()
            {
                $( '.nav.sidebar-menu .menu-open' ).parent().find( '>ul >li:has(".active")' ).each( function () {
                    if ( !$( this ).find( '>.accordion-toggle' ).hasClass( 'menu-open' ) )
                    {
                        $( this ).find( '>.accordion-toggle' ).addClass( 'menu-open' );
                    }
                } );
                // if ( !$( '.nav.sidebar-menu li.active' ).parent().is( ':visible' ) )
                // {
                //     openLi();
                // }
            }

            openLi();
        </script>
        @php
            Eventy::action('cms_admin_foot');
        @endphp
    </body>
</html>
