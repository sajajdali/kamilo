@extends('admin.layout.layout')
@section('content')
    
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ url('admin/menu') }}">مدیریت منو</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ url('admin/dashbord') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->
    
    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">
        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->
        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">
            <!-- menu quick links -->
            <form method="post" id="admin-form" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="p20 admin-form rtl yekan">
                    <h4 class="mt5 text-muted fw500">اضافه کردن منو</h4>
                    <hr class="short">
                    <div class="section">
                        <label class="field prepend-icon" for="title">
                            <input type="text" placeholder="عنوان" class="gui-input" id="title" name="title" aria-required="true">
                            <label class="field-icon" for="title">
                                <i class="fa fa-globe"></i>
                            </label>
                        </label>
                    </div><!-- end section -->
                    <div class="section">
                        <label class="field prepend-icon" for="title">
                            <input type="text" placeholder="کلید دستیابی" class="gui-input" id="key" name="menu_key" aria-required="true">
                            <label class="field-icon" for="key">
                                <i class="fa fa-key"></i>
                            </label>
                        </label>
                    </div><!-- end section -->
                    <hr class="short">
                    <div class="section row">
                        <div class="col-sm-12">
                            <button class="btn btn-default btn-sm ph25 yekan" type="submit">اضافه کردن</button>
                            <input type="hidden" name="page" value="1">
                            <input type="hidden" name="act" value="search">
                        </div>
                    </div>
                </div>
            </form>
        </aside>
        <!-- end: .tray-right -->
        <div class="tray tray-center p25 va-t posr">
            @if (Session::has('sys_error'))
                <div class="alert alert-danger alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-remove pr10"></i>
                    <strong>خطا!</strong>
                    {{ Session::get('sys_error') }}
                </div>
            @endif
            @if(Session::has('successful'))
                <div class="alert alert-success alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ Session::get('successful') }}
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable mb30 rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h3 class="mt5">خطا!</h3>
                    <p>لطفا خطا های زیر را برطرف و مجددا تلاش نمایید.</p>
                    <ul class="list">
                        @foreach($errors->all() as $err)
                            <li class="liste-item">{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="del_group" method="post">
                <div class="panel">
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">
                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تعداد زیرمنوها</th>
                                <th class="txt_center">کلید دستیابی</th>
                                <th class="txt_center">عنوان منو</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menu as $mm)
                                <tr>
                                    <td class="txt_center">
                                        <div class="btn-group text-right yekan rtl txt_right">
                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan" type="button">
                                                عملیات
                                                <span class="caret mr5"></span>
                                            </button>
                                            <ul role="menu" class="dropdown-menu txt_right">
                                                @can('check_access','admin_personalization_menucontoller_edit')
                                                    <li>
                                                        <a href="{{ url('admin/personalize/menu/edit/'.$mm->id) }}">
                                                            ویرایش
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('check_access','admin_personalization_menucontroller_destroy')
                                                    <li>
                                                        <a class="delete_menu" data-effect="mfp-rotateLeft" href="{{ url('admin/personalize/menu/delete/'.$mm->id) }}">
                                                            حذف
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="txt_center yekan">
                                        {{ $mm->menuitems->count() }}
                                    </td>
                                    <td class="rtl yekan txt_center">
                                        {{ $mm->menu_key }}
                                    </td>
                                    <td class="rtl yekan txt_center">
                                        {{ $mm->title }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">منو</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این منو و تمام زیرمنو ها اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@section('scripts')
    <!-- Vendor CSS -->
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <link rel="stylesheet" type="text/css" href={{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            
            
            $( "#admin-form" ).validate( {
                
                
                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",
                
                rules: {
                    title: {
                        required: true
                    },
                    key: {
                        required: true
                    }
                },
                
                
                messages: {
                    title: {
                        required: 'عنوان را وارد نمایید.'
                    },
                    key: {
                        required: 'کلید دستیابی را وارد نمایید.'
                    }
                },
                
                highlight: function ( element, errorClass, validClass ) {
                    $( element ).closest( '.field' ).addClass( errorClass ).removeClass( validClass );
                },
                unhighlight: function ( element, errorClass, validClass ) {
                    $( element ).closest( '.field' ).removeClass( errorClass ).addClass( validClass );
                },
                errorPlacement: function ( error, element ) {
                    if ( element.is( ":radio" ) || element.is( ":checkbox" ) )
                    {
                        element.closest( '.option-group' ).after( error );
                    } else
                    {
                        error.insertAfter( element.parent() );
                    }
                },
                submitHandler: function ( form ) {
                    form.submit();
                }
                
            } );
            $( '.delete_menu' ).on( 'click', function ( e ) {
                e.preventDefault();
                var link = $( this ).attr( 'href' );
                $( '#delete_link' ).attr( 'href', link );
                // Inline Admin-Form example
                $.magnificPopup.open( {
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function ( e ) {
                            var Animation = $( ".delete_menu" ).attr( 'data-effect' );
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                } );
            } );
            $( '#btn_form' ).on( 'click', function ( e ) {
                e.preventDefault();
                // Inline Admin-Form example
                $.magnificPopup.open( {
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function ( e ) {
                            var Animation = $( ".delet_cat" ).attr( 'data-effect' );
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                } );
            } );
        } );
    </script>
    <!-- END: PAGE SCRIPTS -->
@endsection
