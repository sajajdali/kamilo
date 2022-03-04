@extends('admin.layout.layout')
@section('title',$title ?? 'مدیریت تیکت ها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ route('admin.support.tickets.index') }}">مدیریت تیکت ها</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="">پشتیبانی</a>
                </li>
                <li class="crumb-trail">تیکت ها</li>
            </ol>
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

                    <h4 class="mt5 text-muted fw500"> فیلتر کردن محصولات</h4>

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
                    <h6 class="fw400">تاریخ ارسال</h6>
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


                    <h6 class="fw400">نوع مشاوره</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="type" class="yekan">
                                <option value="0">فیلتر براساس نوع مشاوره</option>
                                <option value="soti" {{ (isset($_GET['type']) && $_GET['type']=='soti' ? 'selected="selected"' : '') }}>
                                    صوتی
                                </option>
                                <option value="sms" {{ (isset($_GET['type']) && $_GET['type']=='sms' ? 'selected="selected"' : '') }}>
                                    پیامکی
                                </option>
                                <option value="ticket" {{ (isset($_GET['type']) && $_GET['type']=='ticket' ? 'selected="selected"' : '') }}>
                                    تیکتی
                                </option>
                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>

                    <h6 class="fw400">وضعیت</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="status" class="yekan">
                                <option value="">فیلتر براساس وضعیت تیکت</option>
                                <option value="0" {{ (isset($_GET['status']) && $_GET['status']==0 ? 'selected="selected"' : '') }}>
                                    درانتظار
                                </option>
                                <option value="1" {{ (isset($_GET['status']) && $_GET['status']==1 ? 'selected="selected"' : '') }}>
                                    پاسخ داده شده
                                </option>
                                <option value="2" {{ (isset($_GET['status']) && $_GET['status']==2 ? 'selected="selected"' : '') }}>
                                    بسته شده
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
            @if(Session::has('successful'))
                <div class="alert alert-success alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ Session::get('successful') }}
                </div>
            @endif
            @unless((\Request::input('act')) != null || ((\Request::input('page')) != null && (\Request::input('page'))!=1))
                <div class="tray tray-center p25 va-t posr rtl yekan">

                    <!-- dashboard tiles -->
                    <div class="row">
                        <div class="col-sm-4 col-xl-4">
                            <div class="panel panel-tile text-center br-a br-grey">
                                <div class="panel-body">
                                    <h1 class="fs30 mt5 mbn">{{ number_format($statistics['today_tickets']) }}</h1>
                                    <h6 class="text-system">نیکت های امروز</h6>
                                </div>
                                <div class="panel-footer br-t p12">
                                    <span class="fs11"><i
                                                class="fa  fa-tags pr5"></i> تیکت های ایجاد شده در<b> {{ jdate('G F') }}</b>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xl-4">
                            <div class="panel panel-tile text-center br-a br-grey">
                                <div class="panel-body">
                                    <h1 class="fs30 mt5 mbn">{{ number_format($statistics['all_tickets']) }}</h1>
                                    <h6 class="text-success">کل تیکت ها</h6>
                                </div>
                                <div class="panel-footer br-t p12">
                                    <span class="fs11"><i class="fa  fa-bar-chart-o pr5"></i><b>تمام تیکت ها</b>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xl-4">
                            <div class="panel panel-tile text-center br-a br-grey">
                                <div class="panel-body">
                                    <h1 class="fs30 mt5 mbn">{{ number_format($statistics['pending_tickets']) }}</h1>
                                    <h6 class="text-warning">تیکت های در انتظار</h6>
                                </div>
                                <div class="panel-footer br-t p12">
                                    <span class="fs11"><i class="fa fa-exclamation-circle pr5"></i> تیکت های در انتظار تا<b> {{ jdate('G F') }}</b>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- dashboard metric chart -->
                    <div class="panel">
                        <div class="panel-heading rtl yekan">
                            <span class="panel-title"> تیکت ها</span>
                        </div>
                        <div class="panel-body pn" style="direction: ltr">
                            <div id="ecommerce_chart1" style="height: 300px;"></div>
                        </div>
                    </div>

                </div>
        @endunless
        <!-- recent orders table -->
            <div class="panel">
                <div class="panel-body pn">
                    <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                        <thead>
                        <tr class="bg-light yekan rtl ">

                            <th class="txt_center">عملیات</th>
                            <th class="txt_center">گروه</th>
                            <th class="txt_center">تاریخ</th>
                            <th class="txt_center">کاربر</th>
                            <th class="txt_center">عنوان</th>
                            <th class="txt_center">کد تیکت</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($tickets))
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td class="text-right">
                                        <div class="btn-group text-right yekan rtl txt_right">
                                            @if($ticket->status == \Modules\Support\Entities\Ticket::STATUS_CLOSED)
                                                <button type="button"
                                                        class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    بسته شده
                                                    <span class="caret mr5"></span>
                                                </button>
                                            @elseif($ticket->status == \Modules\Support\Entities\Ticket::STATUS_ANSWERED)
                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false"> پاسخ داده شده
                                                    <span class="caret mr5"></span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-warning br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false"> در انتظار
                                                    <span class="caret mr5"></span>
                                                </button>
                                            @endif
                                            <ul class="dropdown-menu txt_right" role="menu">
                                                @can('edit_admin_support_tickets')
                                                    <li>
                                                        <a href="{{ route('admin.support.tickets.edit', $ticket->id) }}">
                                                            ارسال پاسخ
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_admin_support_tickets')
                                                    <li>
                                                        <a class="delete_ticket" data-effect="mfp-rotateLeft"
                                                           href="{{ route('admin.support.tickets.destroy', $ticket->code) }}">حذف</a>
                                                    </li>
                                                @endcan
                                                <li class="divider"></li>
                                                @can('change_status_admin_support_tickets')
                                                    @if($ticket->status != \Modules\Support\Entities\Ticket::STATUS_CLOSED)
                                                        <li class="active">
                                                            <a class="change_status" data-closed="0"
                                                               href="{{ route('admin.support.tickets.changeStatus', $ticket->id) }}"
                                                               data-effect="mfp-rotateLeft">بستن تیکت</a>
                                                        </li>
                                                    @else
                                                        <li class="active">
                                                            <a class="change_status" data-closed="1"
                                                               href="{{ route('admin.support.tickets.changeStatus', $ticket->id) }}"
                                                               data-effect="mfp-rotateLeft">باز کردن تیکت</a>
                                                        </li>
                                                    @endif
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                    @if($ticket->part != null)
                                        <td class="txt_center yekan">{{ $ticket->part->title }}</td>
                                    @else
                                        <td class="txt_center text-danger yekan">این بخش حذف شده است</td>
                                    @endif
                                    <td class="txt_center yekan">{{ jdate('l d F',strtotime($ticket->created_at)) }}</td>
                                    <td class="txt_center yekan">
                                        @if($ticket->user != null)
                                            {{ $ticket->user->full_name }}
                                            <img class="img-responsive mw40 ib mr10" title="user"
                                                 src="{{ ($ticket->user->avatar != null ? $ticket->user->avatar :  asset('images/nophoto_user.png'))}}"
                                                 style="margin-left: 2px;">
                                        @else
                                            <span class="text-danger">کاربر حذف شده است</span>
                                        @endif

                                    </td>
                                    <td class="txt_center yekan rtl">
                                        {{ $ticket->title }}
                                    </td>
                                    <td class="rtl yekan">{{ $ticket->code }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(sizeof($tickets))
                        <center>{{ $tickets->links() }}</center>
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
                    <span class="panel-title">حذف تیکت</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این تیکت و پاسخ های مربوطه اطمینان دارید؟</h3>
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
            $('.change_status').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                var closed = $(this).data('closed');
                if (closed == '1') {
                    $('#text_alert').text('ایا از باز کردن مجدد این تیکت اطمینان دارید؟');
                } else {
                    $('#text_alert').text('آیا از بستن این تیکت اطمینان دارید؟');
                }
                $('#delete_link2').attr('href', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
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
                name: 'تیکت های ایجاد شده',
                data: [@foreach($statistics['chart'] as $chart) {{ $chart }}, @endforeach]
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
    <!-- END: PAGE SCRIPTS -->
@endpush
