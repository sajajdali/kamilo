@extends('admin.layout.layout')
@section('title',$title)
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ route('admin.support.questions.index') }}">سوالات پرتکرار</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="">پشتیبانی</a>
                </li>
                <li class="crumb-trail">سوالات پرتکرار</li>
            </ol>
        </div>
        
        <div class="topbar-left yekan">
            @can('add_admin_support_questions')
                <a href="{{ route('admin.support.questions.create') }}" class="btn btn-default btn-sm light fw600 ml10 rtl yekan "><span
                            class="fa fa-plus pl5"></span>اضافه کردن سوال</a>
            @endcan
        </div>
    </header>
    <!-- End: Topbar -->

    <section id="content" class="table-layout animated fadeIn">
        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">
            <form method="get">
                <!-- menu quick links -->
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500"> فیلتر کردن سوالات</h4>

                    <hr class="short">
                    
                    <h6 class="fw400">نام کاربر</h6>
                    <div class="section mb15">
                        <label for="order-id" class="field prepend-icon">
                            <input type="text" value="{{ ((isset($_GET['name'])) ? $_GET['name'] : '') }}" name="name"
                                   id="name" class="gui-input yekan" placeholder="نام کاربر">
                            <label for="name" class="field-icon"><i class="fa fa-user"></i>
                            </label>
                        </label>
                    </div>
    
                    <h6 class="fw400">عنوان سوال</h6>
                    <div class="section mb15">
                        <label for="order-id" class="field prepend-icon">
                            <input type="text" value="{{ ((isset($_GET['title'])) ? $_GET['title'] : '') }}" name="title"
                                   id="title" class="gui-input yekan" placeholder="عنوان سوال">
                            <label for="title" class="field-icon"><i class="fa fa-question"></i>
                            </label>
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
            @if(Session::has('successful'))
                <div class="alert alert-success alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ Session::get('successful') }}
                </div>
            @endif
            
        <!-- recent orders table -->
            <div class="panel">
                <div class="panel-body pn">
                    <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                        <thead>
                        <tr class="bg-light yekan rtl ">
                            <th class="txt_center">عملیات</th>
                            <th class="txt_center">تاریخ</th>
                            <th class="txt_center">کاربر</th>
                            <th class="txt_center">عنوان</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($questions))
                            @foreach($questions as $question)
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group text-right yekan rtl txt_right">
                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false"> عملیات
                                                    <span class="caret mr5"></span>
                                                </button>
                                            <ul class="dropdown-menu txt_right" role="menu">
                                                @can('edit_admin_support_questions')
                                                    <li>
                                                        <a href="{{ route('admin.support.questions.edit', $question->id) }}">
                                                            ویرایش سوال
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_admin_support_questions')
                                                    <li>
                                                        <a class="delete_ticket" data-effect="mfp-rotateLeft"
                                                           href="{{ route('admin.support.questions.destroy', $question->id) }}">حذف</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="txt_center yekan">{{ jdate('l d F',strtotime($question->created_at)) }}</td>
                                    <td class="txt_center yekan">
                                        @if($question->user != null)
                                            {{ $question->user->full_name }}
                                            <img class="img-responsive mw40 ib mr10" title="user"
                                                 src="{{ ($question->user->avatar != null ? $question->user->avatar :  asset('images/nophoto_user.png'))}}"
                                                 style="margin-left: 2px;">
                                        @else
                                            <span class="text-danger">کاربر حذف شده است</span>
                                        @endif
                                    </td>
                                    <td class="txt_center yekan rtl">
                                        {{ $question->title }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(sizeof($questions))
                        <center>{{ $questions->links() }}</center>
                    @endif
                </div>
            </div>

        </div>

    </section>
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <form action="" method="POST" id="delete_link">
            @method('DELETE')
            @csrf

            <div class="panel">
                <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                    <span class="panel-title">حذف سوال</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این سوال و پاسخ مربوطه اطمینان دارید؟</h3>
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
    <span class="panel-title">تیکت</span>
    </div>
    <div class="panel-body" style="text-align: right">
    <h3 class="mt5" id="text_alert">متن پیام</h3>
    </div>
    <div class="panel-footer text-left">
    <a id="delete_link2">
    <button class="btn btn-primary" type="button">بله</button>
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

    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('.delete_ticket').on('click', function (e) {
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
                            var Animation = $(".delete_ticket").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            
        });
    </script>
    <!-- END: PAGE SCRIPTS -->
@endpush
