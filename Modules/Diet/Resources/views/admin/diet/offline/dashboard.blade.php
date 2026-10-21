@extends('admin.layout.layout')
@section('title',$title ?? 'رژیم‌های آفلاین')

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
        <!-- end: .tray-center -->

        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">
            <form method="get">
                <!-- menu quick links -->
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500"> فیلتر کردن</h4>

                    <hr class="short">

                    <div class="section mb15">
                        <label for="order-id" class="field prepend-icon">
                            <input type="text" value="{{ ((isset($_GET['name'])) ? $_GET['name'] : '') }}" name="name"
                                   id="name" class="gui-input yekan" placeholder="نام کاربر">
                            <label for="name" class="field-icon"><i class="fa fa-user"></i>
                            </label>
                        </label>
                    </div>
                    <div class="section mb15">
                        <label for="order-id" class="field prepend-icon">
                            <input type="text" value="{{ ((isset($_GET['code'])) ? $_GET['code'] : '') }}" name="code"
                                   id="code" class="gui-input yekan" placeholder="کد پیگیری">
                            <label for="code" class="field-icon"><i class="fa fa-barcode"></i>
                            </label>
                        </label>
                    </div>
                    <h6 class="fw400">تاریخ</h6>
                    <div class="section row mb15">
                        <div class="col-md-6">
                            <label for="end_at" class="field prepend-icon">
                                <input type="text" name="end_at" id="end_at" class="gui-input yekan"
                                       placeholder="تا تاریخ">

                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="start_at" class="field prepend-icon">
                                <input type="text" name="start_at" id="start_at" class="gui-input yekan"
                                       placeholder="از تاریخ">

                            </label>
                        </div>
                    </div>


                    <h6 class="fw400">وضعیت</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="file" class="yekan">
                                <option value="0">فیلتر براساس فایل</option>
                                <option value="2" {{ (isset($_GET['file']) && $_GET['file']==2 ? 'selected="selected"' : '') }}>
                                    فایل ارسال شده
                                </option>
                                <option value="1" {{ (isset($_GET['file']) && $_GET['file']==1 ? 'selected="selected"' : '') }}>
                                    بدونه فایل
                                </option>
                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>


                    <div class="section row">
                        <div class="col-sm-12">
                            <input type="hidden" name="act" value="search"/>
                            <button class="btn btn-default btn-sm ph25 yekan" type="submit">جستجو</button>
                        </div>
                    </div>
                    @can('delete_admin_user_meal')
                        <hr class="short">
                        <a id="delAll" href="{{ url('admin/userDiet/delUnpaies') }}">
                            <button type="button" id="quick-compose" class="btn btn-danger light btn-block compose-btn">
                                حذف
                                همه پرداخت نشده ها
                            </button>
                        </a>
                    @endcan

                </div>
            </form>
        </aside>
        <!-- end: .tray-right -->
        <div class="tray tray-center p25 va-t posr">
            <!-- create new order panel -->
            <div class="tray tray-center p25 va-t posr rtl yekan">
                @if(Session::has('successful'))
                    <div class="alert alert-success alert-dismissable rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                        <i class="fa fa-check pr10"></i>
                        {{ Session::get('successful') }}
                    </div>
                @endif
                @unless((\Request::input('act')) != null || ((\Request::input('page')) != null && (\Request::input('page'))!=1))
                    <div class="panel">
                        <div class="panel-heading rtl yekan">
                            <span class="panel-title">رژیم های دریافتی</span>
                        </div>
                        <div class="panel-body pn" style="direction: ltr">
                            <div id="ecommerce_chart1" style="height: 300px;"></div>
                        </div>
                    </div>
            @endunless
            <!-- recent orders table -->
                <div class="panel">
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13 rtl yekan">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">کاربر</th>
                                <th class="txt_center">نوع رژیم</th>
                                <th class="txt_center">جلسه</th>
                                <th class="txt_center">فایل رژیم</th>
                                <th class="txt_center">وضعیت پرداخت</th>
                                <th class="txt_center">تاریخ</th>
                                <th class="txt_center">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($data['rejimList'] != null)
                                @foreach($data['rejimList'] as $diet)
                                    <tr class="data-visit">
                                        <td class="w175">
                                            @if($diet->user != null)
                                                <img src="{{ $diet->user->avatar }}"
                                                     title="user" class="img-responsive mw20 ib mr10">
                                                <span>{{$diet->user->full_name }}</span>
                                            @else
                                                <span class="text-danger">کاربر حذف شده است</span>
                                            @endif
                                        </td>
                                        <td class="txt_center">
                                            @if($diet->dietType != null)
                                                {{ $diet->dietType->name }}
                                            @else
                                                <span class="text-danger">این رژیم حذف شده است</span>
                                            @endif
                                        </td>
                                        <td class="txt_center">
                                            @if($diet->link == null)
                                                -
                                            @else
                                                {{ $diet->no_shift }}
                                            @endif
                                        </td>
                                        <td class="txt_center">
                                            @if($diet->link == null)
                                                <i class="fa fa-remove text-danger"></i>
                                            @else
                                                <a href="{{ url('uploads/dietFiles/'.$diet->link) }}"
                                                   class="btn btn-success">دانلود</a>
                                            @endif
                                        </td>
                                        <td class="txt_center">
                                            @if($diet->tid != 0 && $diet->transaction != null && $diet->transaction->status)
                                                <a target="_blank"
                                                   href="{{ url("admin/transaction?name=&code=".$diet->transaction->code."&end_at=&start_at=&status=0&act=search") }}"><span
                                                            class="text-success">پرداخت شده</span></a>
                                            @else
                                                <span class="text-danger">پرداخت نشده</span>
                                            @endif
                                        </td>
                                        <td class="txt_center">
                                            {{ jdate('Y/m/d',strtotime($diet->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group yekan rtl text-center">
                                                <button type="button" class="btn btn-info br2 btn-xs fs12 dropdown-toggle yekan" data-toggle="dropdown" aria-expanded="false">
                                                    عملیات

                                                    <span class="caret mr5"></span>
                                                </button>
                                                <ul class="dropdown-menu txt_right" role="menu">
                                                    @can('edit_admin_user_offline_diet')
                                                        <li>
                                                            <a href="{{ url('admin/offlineDiet/'.$diet->id) }}">جزییات</a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($data['rejimList']))
                            <center class="rtl">{{ $data['rejimList']->links() }}</center>
                        @endif
                    </div>
                </div>

            </div>

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
        jQuery(document).ready(function () {
            $('.caption_table span').mouseenter(function () {
                var csl = $(this).data('filter');
                $('.data-visit').css('opacity', '0.3');
                $('tr.' + csl).css('opacity', '1');
            });
            $('.caption_table span').mouseleave(function () {
                $('.data-visit').css('opacity', '1');
            });
            $('#delAll').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('#delete_link').attr('href', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $("#delete_link").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $("#start_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#start_at").val("{{ ((isset($_GET['start_at'])) ? $_GET['start_at'] : '') }}");
            $("#end_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#end_at").val("{{ ((isset($_GET['end_at'])) ? $_GET['end_at'] : '') }}");
            // Init jQuery masked inputs
            $('#start_at').mask('9999/99/99');
            $('#end_at').mask('9999/99/99');
            // Chart colors
                    @unless((\Request::input('act')) != null || ((\Request::input('page')) != null && (\Request::input('page'))!=1))
            var highColors = [bgInfo];

            // Chart color groups
            var sparkColors = {
                "info": [bgInfo, bgInfoLr, bgInfoDr],
            };

            // Chart data
            var seriesData = [{
                name: 'رژیم های دریافتی',
                @if($data['statistics']['chart'] != null)
                data: [@foreach($data['statistics']['chart'] as $chart) {{ $chart }}, @endforeach]
                @endif
            }
            ];

            var ecomChart = $('#ecommerce_chart1');

            if (ecomChart.length) {
                ecomChart.highcharts({
                    credits: false,
                    colors: highColors,
                    chart: {
                        backgroundColor: 'transparent',
                        className: 'br-r',
                        type: 'line',
                        zoomType: 'x',
                        panning: true,
                        panKey: 'shift',
                        marginTop: 45,
                        marginRight: 1,
                    },
                    title: {
                        text: null
                    },
                    xAxis: {
                        gridLineColor: '#EEE',
                        lineColor: '#EEE',
                        tickColor: '#EEE',
                        categories: [@for($i=1;$i<=jdate('j','','','','en');$i++) '{{ $i }} {{jdate('F')}}', @endfor]
                    },
                    yAxis: {
                        min: 0,
                        tickInterval: 5,
                        gridLineColor: '#EEE',
                        title: {
                            text: null,
                        }
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 3,
                        },
                        area: {
                            fillOpacity: 0.2
                        }
                    },
                    legend: {
                        enabled: true,
                        floating: false,
                        align: 'right',
                        verticalAlign: 'top',
                        // y: '-160px'
                    },
                    series: seriesData
                });
            }

            @endunless
        });
    </script>
@endpush