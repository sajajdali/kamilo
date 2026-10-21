@extends('admin.layout.layout')
@section('title',$title ?? 'نظرات')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li>
                    <a href="{{ route('admin.blog.index') }}">داشبورد بلاگ</a>
                </li>
            </ul>
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

                    <h4 class="mt5 text-muted fw500">فیلتر کردن خبرها</h4>

                    <hr class="short">

                    <div class="section mb15">
                        <label for="title" class="field prepend-icon">
                            <input value="{{ ((isset($_GET['username'])) ? $_GET['username'] : '') }}" type="text"
                                   name="username" id="title" class="gui-input yekan" placeholder="ارسال کننده">
                            <label for="title" class="field-icon"><i class="fa fa-user"></i>
                            </label>
                        </label>
                    </div>
                    <h6 class="fw400">تاریخ ارسال</h6>
                    <div class="section row mb15">
                        <div class="col-md-6">
                            <label class="field prepend-icon" for="end_at">
                                <input type="text" placeholder="تا تاریخ" class="gui-input yekan" id="end_at" name="to">

                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="field prepend-icon" for="start_at">
                                <input type="text" placeholder="از تاریخ" class="gui-input yekan" id="start_at"
                                       name="from">

                            </label>
                        </div>
                    </div>

                    <h6 class="fw400">وضعیت نظر</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="comment-status" class="yekan">
                                <option value="انتخاب کنید">فیلتر براساس وضعیت نظر</option>
                                <option {{ (isset($_GET['comment-status']) && $_GET['comment-status']==1 ? 'selected="selected"' : '') }} value="1">
                                    تایید شده
                                </option>
                                <option {{ (isset($_GET['comment-status']) && $_GET['comment-status']==2 ? 'selected="selected"' : '') }} value="2">
                                    رد شده
                                </option>
                                <option {{ (isset($_GET['comment-status']) && $_GET['comment-status']==3 ? 'selected="selected"' : '') }} value="0">
                                    در انتظار بررسی
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
                {{ method_field('PUT') }}
                <div class="panel">
                    <div class="panel-body pn">


                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تاریخ ارسال</th>
                                <th class="txt_center">مربوط به خبر</th>
                                <th class="text-center">ارسال کننده</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($comments))
                                @foreach($comments as $comment)
                                    <tr>
                                        <td class="text-right">
                                            <div class="btn-group text-right yekan rtl txt_right">
                                                @if($comment->status == 1)
                                                    <button type="button"
                                                            class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                        تایید شده
                                                        @elseif($comment->status == 0)
                                                            <button type="button"
                                                                    class="btn btn-warning br2 btn-xs fs12 dropdown-toggle yekan"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                در انتظار
                                                                @else
                                                                    <button type="button"
                                                                            class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan"
                                                                            data-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                        رد شده

                                                                        @endif
                                                                        <span class="caret mr5"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu txt_right" role="menu">
                                                                        <li>
                                                                            <a href="{{ route('admin.blog.comments.edit',$comment->id) }}">ارسال
                                                                                جواب | مشاهده نظر</a>
                                                                        </li>
                                                                        @can('delete_admin_blog_comments')
                                                                            <li>
                                                                                <a class="delete_blog"
                                                                                   data-effect="mfp-rotateLeft" href="{{ route('admin.blog.comments.destroy',$comment->id) }}">حذف</a>
                                                                            </li>
                                                                        @endcan
                                                                        <li class="divider"></li>
                                                                        @can('change_admin_blog_comments_state')
                                                                            @if($comment->status  == Modules\Blog\Entities\BlogComment::STATUS_PROVED)
                                                                                <li>
                                                                                    <a href="{{ route('admin.blog.comments.changeStatus',['comment'=>$comment->id,'status'=>0]) }}">لغو
                                                                                        انتشار</a>
                                                                                </li>
                                                                            @else
                                                                                <li>
                                                                                    <a href="{{ route('admin.blog.comments.changeStatus',['comment'=>$comment->id,'status'=>1]) }}">تایید نظر</a>
                                                                                </li>
                                                                            @endif
                                                                        @endcan

                                                                    </ul>
                                                            </button>
                                                    </button>
                                            </div>
                                        </td>
                                        <td class="txt_center yekan rtl">{{jdate("Y/m/d ساعت H:i",strtotime($comment->created_at))}}</td>
                                        <td class="rtl yekan">

                                            <span style="margin-right: 5px;">{{ $comment->blog->title }}</span>
                                        </td>
                                        <td class="rtl yekan">
                                            <span style="margin-right: 5px;">{{ $comment->name }}</span>
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($comments))
                            <center>{{ $comments->links() }}</center>
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
                    <span class="panel-title">حذف نظر</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این نظر اطمینان دارید؟</h3>
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

            $('.delete_blog').on('click', function (e) {
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
