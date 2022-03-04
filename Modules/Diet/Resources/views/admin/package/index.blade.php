@extends('admin.layout.layout')
@section('title',$title ?? 'پکیج‌ها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li {{ (Request::is('admin/disease') ? 'class=active' : '') }}>
                    <a href="#">داشبورد پکیج ها</a>
                </li>

            </ul>
        </div>
        <div class="topbar-left">
            @can('add_admin_diet_package')
                <a href="{{ route('admin.diet.package.create') }}"
                   class="btn btn-default btn-sm light fw600 ml10 rtl yekan "><span
                            class="fa fa-plus pl5"></span> اضافه کردن پکیج</a>
            @endcan

        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

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
            <form method="post" id="change_news">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="panel">
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">
                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تعداد فروش</th>
                                <th class="txt_center">میزان شارژ</th>
                                <th class="txt_center">قیمت</th>
                                <th class="txt_center">وضعیت</th>
                                <th class="txt_center">نام</th>
                                <th>ردیف</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($packages))
                                @foreach($packages as $package)
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group text-right yekan rtl txt_right">

                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    عملیات
                                                    <span class="caret mr5"></span>
                                                </button>
                                                <ul class="dropdown-menu txt_right" role="menu">
                                                    @can('delete_admin_diet_package')
                                                        <li>
                                                            <a class="delete_meal" data-effect="mfp-rotateLeft"
                                                               href="{{ route('admin.diet.package.destroy', $package->id) }}">حذف</a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_admin_diet_package')
                                                        <li>
                                                            <a href="{{ route('admin.diet.package.edit', $package->id) }}">ویرایش</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <a href="{{ route('admin.diet.package.change', $package->id) }}">
                                                                    @if($package->status)
                                                                        غیرفعال سازی
                                                                    @else
                                                                        فعال سازی
                                                                    @endif
                                                                </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="text-center rtl yekan">{{ number2farsi(number_format($package->counter)) }}</td>
                                        <td class="text-center rtl yekan">{{ number2farsi(number_format($package->charge)) }}
                                            تومان
                                        </td>
                                        <td class="text-center rtl yekan">{{ number2farsi(number_format($package->price)) }}
                                            تومان
                                        </td>
                                        <td class="text-center rtl yekan">
                                            @if($package->status)
                                                <span class="label label-success">فعال</span>
                                            @else
                                                <span class="label label-danger">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td class="text-center rtl yekan">{{ $package->title }}</td>
                                        <td class="rtl yekan">{{ $package->id }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <center>{{ $packages->links() }}</center>
                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
        <form method="post" action="" id="delete_link">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <div class="panel">
                <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                    <span class="panel-title">حذف پکیج</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این پکیج اطمینان دارید؟</h3>
                </div>
                <div class="panel-footer text-left">
                    <button class="btn btn-primary" type="submit">بله حذف کن</button>
                </div>
            </div>
        </form>
    </div>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">عملیات گروهی</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این بیماری اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a>
                    <button id="sub_form" class="btn btn-primary" type="button">بله</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet"
          href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("#start_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#start_at").val("{{ ((isset($_GET['from'])) ? $_GET['from'] : '') }}");
            $("#end_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#end_at").val("{{ ((isset($_GET['to'])) ? $_GET['to'] : '') }}");
            // Init jQuery masked inputs
            $('#start_at').mask('9999/99/99');
            $('#end_at').mask('9999/99/99');
            $('#sub_form').on('click', function (e) {
                $('#change_news').submit();
            });
            $('#group_change').on('click', function (e) {
                e.preventDefault();
                var selected_item = $('#change_news').find('input[name="blog_id[]"]:checked');
                if (selected_item.length == 0) {
                    alert_danger('ایتدا یک یا چند وعده را انتخاب نمایید.');
                    return false;
                }
                var val = $('#filter-category').val();
                var text = $('#filter-category').find('option[value="' + val + '"]').text();
                $('#modal-panel2').find('.mt5').text('آیا از ' + text + ' همه اطمینان دارید؟');
                if (val != 0) {
                    $.magnificPopup.open({
                        removalDelay: 500, //delay removal by X to allow out-animation,
                        items: {
                            src: '#modal-panel2'
                        },
                        // overflowY: 'hidden', //
                        callbacks: {
                            beforeOpen: function (e) {
                                var Animation = $(".delete_meal").attr('data-effect');
                                this.st.mainClass = Animation;
                            }
                        },
                        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                    });
                } else {
                    alert_danger('ابتدا یک عملیات انتخاب نمایید.');
                }
            });
            $('.delete_meal').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('#delete_link').attr('action', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_meal").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });

            function alert_danger(text) {
                var noteStyle = 'danger';
                var noteShadow = true;
                var noteOpacity = '1';
                var noteStack = "stack_bar_bottom";
                var width = "70%";
                var Stacks = {
                    stack_top_right: {
                        "dir1": "down",
                        "dir2": "left",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_top_left: {
                        "dir1": "down",
                        "dir2": "right",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bottom_left: {
                        "dir1": "right",
                        "dir2": "up",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bottom_right: {
                        "dir1": "left",
                        "dir2": "up",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bar_top: {
                        "dir1": "down",
                        "dir2": "right",
                        "push": "top",
                        "spacing1": 0,
                        "spacing2": 0
                    },
                    stack_bar_bottom: {
                        "dir1": "up",
                        "dir2": "right",
                        "spacing1": 0,
                        "spacing2": 0
                    },
                    stack_context: {
                        "dir1": "down",
                        "dir2": "left",
                        "context": $("#stack-context")
                    },
                };
                new PNotify({
                    title: 'خطا',
                    text: text,
                    shadow: noteShadow,
                    opacity: noteOpacity,
                    addclass: noteStack,
                    type: noteStyle,
                    stack: Stacks[noteStack],
                    width: width,
                    delay: 3000,
                });
            }
        });
    </script>
@endpush
