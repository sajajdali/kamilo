@extends('admin.layout.layout')
@section('title',$title ?? 'جزییات رژیم آفلاین')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ url('admin/consulting/ticket/setting') }}">نوبت دهی</a>
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
    <section class="table-layout" id="content">
        <!-- begin: .tray-center -->
        <div class="tray tray-center pv40 ph30 va-t posr animated-delay animated-long" data-animate='["800","fadeIn"]'>
            <div class="alert alert-system alert-dismissable rtl yekan">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-cubes pr10"></i>
                برای نمایش صحیح فایل فقط از فرمت pdf و حداکثر حجم ۸ مگابایت استفاده نمایید.
            </div>
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
            <div class="mw1100 center-block">

                <!-- begin: .admin-form -->
                <div class="admin-form rtl yekan">

                    <div id="p1" class="panel heading-border">

                        <div class="panel-body bg-light">
                            <form method="post" action="" id="form-ui" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="section-divider mb40" id="spy1">
                                    <span>مشخصات کاربر</span>
                                </div>
                                <!-- .section-divider -->

                                <!-- Basic Inputs -->
                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ $data['user']->fullname }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">نام</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ $data['user']->email }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">ایمیل</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ number2farsi($data['program']->height) }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">قد</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ number2farsi($data['program']->weight) }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">وزن</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ number2farsi($data['program']->wrist) }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">دورمچ</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ number2farsi(isAge($data['user']->id)) }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">سن</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ number2farsi($data['program']->target_weight) }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">وزن هدف</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="form-group">
                                            <div class="col-lg-8">
                                                <p class="form-control-static text-muted">{{ $data['program']->dietType->name }}</p>
                                            </div>
                                            <label for="inputStandard" class="col-lg-3 control-label">رژیم درخواستی</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-divider mv40" id="spy2">
                                    <span>ارسال فایل</span>
                                </div>
                                <!-- .section-divider -->
                                <div class="alert alert-dark alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-cog pr10 hidden"></i>
                                    در صورتی که قبلا برای این درخواست فایلی ارسال کرده باشید فایل جدید جایگزین فایل قبلی خواهد شد
                                </div>
                                <!-- File Uploaders2 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="section">
                                            <label class="field prepend-icon append-button file">
                                                <span class="button btn-primary">انتخاب فایل</span>
                                                <input type="file" class="gui-file" name="file3" id="file3"
                                                       onChange="document.getElementById('uploader3').value = this.value;">
                                                <input type="text" class="gui-input" id="uploader3"
                                                       placeholder="انتخاب فایل رژیم">
                                                <label class="field-icon"><i class="fa fa-upload"></i>
                                                </label>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr class="short alt">
                                <div class="section row mbn">
                                    <div class="col-sm-4">
                                        <p class="text-left yekan">
                                            <button type="submit" class="btn btn-primary yekan">ثبت و ذخیره</button>
                                        </p>
                                    </div>
                                    <div class="col-sm-8">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end: .admin-form -->

            </div>
        </div>
        <!-- end: .tray-center -->


    </section>
    <!-- Panel popup -->
    <div id="modal-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">عملیات گروهی</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف پرداخت نشده ها اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link" data-effect="mfp-rotateLeft">
                    <button id="sub_form" class="btn btn-primary" type="button">بله</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <style>
        .caption_table {
            padding: 10px 5px;
            text-align: right;
        }

        .caption_table span {
            margin-left: 10px;
            cursor: pointer;
        }

        .caption_table span i {
            margin-left: 2px;
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <!-- Chart Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/raphael/raphael.js') }}"></script>

    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet"
          href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
    <!-- Page Javascript -->

    <script type="text/javascript">
        var adminForm = $('.admin-form');
        var options = adminForm.find('.option');
        var switches = adminForm.find('.switch');
        var buttons = adminForm.find('.button');

        var Panel = $('#p1');
        jQuery(document).ready(function () {
            setTimeout(function () {
                adminForm.addClass('theme-primary');
                Panel.addClass('panel-primary');

                $(options).each(function (i, e) {
                    if ($(e).hasClass('block')) {
                        $(e).removeClass().addClass('block mt15 option option-primary');
                    } else {
                        $(e).removeClass().addClass('option option-primary');
                    }
                });
                $(switches).each(function (i, ele) {

                    if ($(ele).hasClass('switch-round')) {
                        if ($(ele).hasClass('block')) {
                            $(ele).removeClass().addClass('block mt15 switch switch-round switch-primary');
                        } else {
                            $(ele).removeClass().addClass('switch switch-round switch-primary');
                        }
                    } else {
                        if ($(ele).hasClass('block')) {
                            $(ele).removeClass().addClass('block mt15 switch switch-primary');
                        } else {
                            $(ele).removeClass().addClass('switch switch-primary');
                        }
                    }
                });
                buttons.removeClass().addClass('button btn-primary');
            }, 2200);
        });
    </script>
@endpush