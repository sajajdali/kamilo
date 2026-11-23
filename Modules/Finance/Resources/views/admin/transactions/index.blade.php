@extends('admin.layout.layout')
@section('title', $title)
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="">امور مالی</a>
                </li>
                <li class="crumb-trail">تراکنش ها</li>
            </ol>
        </div>
        <div class="topbar-left">
            @can('add_admin_finance_transactions')
                <a class="btn btn-default btn-sm light fw600 ml10 rtl yekan" href="{{ route('admin.finance.transactions.create') }}"><span
                            class="fa fa-plus pl5"></span>شارژ کاربر</a>
            @endcan
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
                            <select id="filter-categories" name="status" class="yekan">
                                <option value="">فیلتر براساس وضعیت</option>
                                @foreach(Modules\Finance\Entities\Transaction::$STATUS as $key => $value)
                                    <option value="{{ $key }}" {{ (isset($_GET['status']) && $_GET['status']==$key ? 'selected="selected"' : '') }}>{{ $value['title'] }}
                                    </option>
                                @endforeach
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
                            <span class="panel-title">تراکنش های موفق</span>
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

                                <th class="txt_center">وضعیت</th>
                                <th class="txt_center">مبلغ کل</th>
                                <th class="txt_center">تاریخ پرداخت</th>
                                <th class="txt_center">کد پیگیری</th>
                                <th class="txt_right">کاربر</th>
                                <th class="txt_right">ویرایش</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($data['transaction'] != null)
                                @foreach($data['transaction'] as $transaction)
                                    <tr class="data-visit">
                                        <td class="">
                                            <span class="text-{{ Modules\Finance\Entities\Transaction::$STATUS[$transaction->status]['class'] }}">{{ Modules\Finance\Entities\Transaction::$STATUS[$transaction->status]['title'] }}</span>
                                        </td>
                                        <td class="">
                                            {{ number2farsi(number_format($transaction->price)) }}
                                        </td>
                                        <td class="">
                                            @if($transaction->created_at != null) {{ jdate('l j F Y ساعت H:i',strtotime($transaction->created_at)) }} @endif
                                        </td>
                                        <td class="">{{ $transaction->code }}</td>
                                        <td class="w175">
                                            @if($transaction->user != null)
                                                <img src="{{ ($transaction->user->avatar!=null ? $transaction->user->avatar :  asset('images/nophoto_user.png'))}}"
                                                     title="user" class="img-responsive mw20 ib mr10">
                                                <span>{{ $transaction->user->full_name }}</span>
                                            @else
                                                <span class="text-danger">کاربر حذف شده است</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.finance.transactions.show', ['transaction' => $transaction->id]) }}"><button class="btn btn-system">ویرایش</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($data['transaction']))
                            <center class="rtl">{{ $data['transaction']->links() }}</center>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </section>
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
            $('.delete_appoint').on('click', function (e) {
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
                            var Animation = $(".delete_appoint").attr('data-effect');
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
                name: 'تراکنش های موفق',
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
