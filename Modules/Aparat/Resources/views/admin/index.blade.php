
@extends('admin.layout.layout')
@section('title',$title ?? 'ویدیو‌ها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">مدیریت ویدیو‌های آپارات</a>
                </li>
            </ul>
        </div>
        <div class="topbar-left">
            @can('add_admin_aparat')
                <a href="{{ route('admin.aparat.create') }}" class="btn btn-default btn-sm light fw600 ml10 rtl yekan ">
                    <span class="fa fa-plus pl5"></span> اضافه کردن
                </a>
            @endcan

        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">

            <form>
                <!-- menu quick links -->
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500">فیلتر کردن ویدیو‌ها</h4>

                    <hr class="short">

                    <div class="section mb15">
                        <label for="title" class="field prepend-icon">
                            <input value="{{ ((isset($_GET['title'])) ? $_GET['title'] : '') }}" type="text"
                                   name="title" id="title" class="gui-input yekan" placeholder="عنوان ویدیو">
                            <label for="title" class="field-icon"><i class="fa fa-tag"></i>
                            </label>
                        </label>
                    </div>
                    <h6 class="fw400">وضعیت خبر</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="heroes-status" class="yekan">
                                <option value="0">فیلتر براساس وضعیت ویدیو</option>
                                <option {{ (isset($_GET['heroes-status']) && $_GET['heroes-status']==1 ? 'selected="selected"' : '') }} value="1">
                                    منتشر شده
                                </option>
                                <option {{ (isset($_GET['heroes-status']) && $_GET['heroes-status']==2 ? 'selected="selected"' : '') }} value="2">
                                    پیش نویس
                                </option>
                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>


                    <hr class="short">

                    <div class="section row">
                        <div class="col-sm-12">
                            <input type="hidden" name="act" value="search"/>
                            <button class="btn btn-default btn-sm ph25 yekan" type="submit">جستجو</button>
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
            <form method="post" id="change_news">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="panel">
                    <div class="panel-body pn">

                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تاریخ ارسال</th>
                                <th class="text-center">عنوان</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($aparats))
                                @foreach($aparats as $aparat)
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group text-right yekan rtl txt_right">
                                                @if($aparat->status == 1)
                                                    <button type="button"
                                                            class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        منتشر شده
                                                        @else
                                                            <button type="button"
                                                                    class="btn btn-info br2 btn-xs fs12 dropdown-toggle yekan"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                پیش نویس
                                                                @endif

                                                                <span class="caret mr5"></span>
                                                            </button>
                                                            <ul class="dropdown-menu txt_right" role="menu">
                                                                @can('edit_admin_blog')
                                                                    <li>
                                                                        <a href="{{ route('admin.aparat.edit', $aparat->id) }}">ویرایش</a>
                                                                    </li>
                                                                @endcan
                                                                @can('delete_admin_blog')
                                                                    <li>
                                                                        <a class="delete_blog"
                                                                           data-effect="mfp-rotateLeft"
                                                                           href="{{ route('admin.aparat.destroy', $aparat->id) }}">حذف</a>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                    </button>

                                            </div>
                                        </td>
                                        <td class="txt_center yekan rtl">{{ jdate('d-F-Y',strtotime($aparat->created_at)) }}</td>
                                        <td class="rtl yekan">{{ $aparat->title }}</td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($aparats))
                            <center>{{ $aparats->links() }}</center>
                        @endif
                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <form action="" method="POST" id="delete_link">
            @method('DELETE')
            @csrf

            <div class="panel">
                <div class="panel-heading" style="text-align: right">
								<span class="panel-icon"><i class="fa fa-pencil"></i>
								</span>
                    <span class="panel-title">حذف ویدیو</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این ویدیو اطمینان دارید؟</h3>
                </div>
                <div class="panel-footer text-left">

                    <button class="btn btn-primary" type="submit">بله حذف کن</button>
                </div>
            </div>
        </form>

    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- admin Modals CSS -->
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
            $('.delete_blog').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('#delete_link').attr('action', link);
                // Inline admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_blog").attr('data-effect');
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
