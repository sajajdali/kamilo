@extends('admin.layout.layout')
@section('title',($title ?? 'مدیریت'))
@section('content')
    <section id="content" class="rtl">
        @if (Session::has('sys_error'))
            <div class="alert alert-danger alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-remove pr10"></i>
                <strong>خطا!</strong>
                {{ Session::get('sys_error') }}
            </div>
    @endif
    <!-- Dashboard Tiles -->
        <div class="row mb10">
            <div class="col-md-3">
                <div class="panel bg-alert light of-h mb10">
                    <div class="pn pl20 p5">
                        <div class="icon-bg"><i class="fa fa-comments-o"></i></div>
                        <h2 class="mt15 lh15"><b>{{ number2farsi(number_format($data['top']['ticket_count'])) }}</b>
                        </h2>
                        <h5 class="text-muted">کل تیکت‌ها</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel bg-info light of-h mb10">
                    <div class="pn pl20 p5">
                        <div class="icon-bg"><i class="fa fa-cutlery"></i></div>
                        <h2 class="mt15 lh15">
                            <b>{{ number2farsi(number_format($data['top']['diet_request_count'])) }}</b></h2>
                        <h5 class="text-muted">رژیم‌های درخواستی</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel bg-danger light of-h mb10">
                    <div class="pn pl20 p5">
                        <div class="icon-bg"><i class="fa fa-file-text"></i></div>
                        <h2 class="mt15 lh15"><b>{{ number2farsi(number_format($data['top']['blog_count'])) }}</b></h2>
                        <h5 class="text-muted">خبر‌ها</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel bg-warning light of-h mb10">
                    <div class="pn pl20 p5">
                        <div class="icon-bg"><i class="fa fa-envelope"></i></div>
                        <h2 class="mt15 lh15"><b>{{ number2farsi(number_format($data['top']['user_count'])) }}</b></h2>
                        <h5 class="text-muted">کل کاربران</h5>
                    </div>
                </div>
            </div>
        </div>
        @can('view_admin_support_tickets')
            @if($data['waitingTicket'] >= 1)
                    <div class="alert alert-system alert-dismissable mb30">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h3 class="mt5">شما {{ number2farsi(number_format($data['waitingTicket'])) }} تیکت در انتظار دارید.</h3>
                        <br>
                        <p>
                            <a class="btn btn-system" href="{{ route('admin.support.tickets.index') }}">مشاهده و پاسخ</a>
                        </p>
                    </div>
            @endif
        @endcan
        @can('edit_admin_user_offline_diet')
                @if($data['offlineDiet'] >= 1)
                    <div class="alert alert-info alert-dismissable mb30">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h3 class="mt5">شما {{ number2farsi(number_format($data['offlineDiet'])) }} درخواست رژیم آفلاین در انتظار دارید.</h3>
                        <br>
                        <p>
                            <a class="btn btn-info" href="{{ route('admin.user_offline_diet.index') }}">مشاهده</a>
                        </p>
                    </div>
            @endif
        @endcan
        <!-- Stats Top Graph Bot -->
        <div class="panel" id="p7">
            <div class="panel-heading">
                <span class="panel-title"><i class="fa fa-pencil hidden"></i> رژیم‌های دریافتی</span>
            </div>
            <div class="panel-body pn" style="direction: ltr">
                <div id="high-line3" style="width: 100%; height: 210px; margin: 0 auto"></div>
            </div>
        </div>

        <div class="panel" id="p7">
            <div class="panel-heading">
                <span class="panel-title"><i class="fa fa-pencil hidden"></i> کاربران جدید</span>
            </div>
            <div class="panel-body pn" style="direction: ltr">
                <div id="high-line4" style="width: 100%; height: 210px; margin: 0 auto"></div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <!-- Sparklines CDN -->
    <script type="text/javascript" src="{{ asset('admin/files/assets/js/jquery.sparkline.min.js') }}"></script>
    <!-- Chart Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/circles/circles.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/raphael/raphael.js') }}"></script>
    <!-- Holder js  -->
    <script type="text/javascript" src="{{ asset('admin/files/assets/js/bootstrap/holder.min.js') }}"></script>
    <!-- Page Javascript -->
    <script>
        let highColors = [bgWarning, bgPrimary, bgInfo, bgAlert,
            bgDanger, bgSuccess, bgSystem, bgDark
        ];
        let line3 = $('#high-line3');
        let line4 = $('#high-line4');

        if (line3.length) {

            // High Line 3
            $('#high-line3').highcharts({
                credits: false,
                colors: highColors,
                chart: {
                    backgroundColor: '#f9f9f9',
                    className: 'br-r',
                    type: 'line',
                    zoomType: 'x',
                    panning: true,
                    panKey: 'shift',
                    marginTop: 25,
                    marginRight: 1,
                },
                title: {
                    text: null
                },
                xAxis: {
                    gridLineColor: '#EEE',
                    lineColor: '#EEE',
                    tickColor: '#EEE',
                    labels: {
                        style: {
                            fontFamily: 'wyekan'
                        }
                    },
                    categories: [
                        @foreach($data['chart']['X'] as $x) '{{ $x }}' @unless($loop->last),@endunless @endforeach
                    ]
                },
                yAxis: {
                    min: 0,
                    tickInterval: 1,
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
                    enabled: false,
                },
                fontFamily: 'wyekan',
                series: [{
                    name: 'رژیم',
                    data: [@foreach($data['chart']['diet_request']['Y'] as $y) {{ $y }} @unless($loop->last),@endunless @endforeach]
                }]
            });

        }
        if (line4.length) {

            // High Line 3
            $('#high-line4').highcharts({
                credits: false,
                colors: highColors,
                chart: {
                    backgroundColor: '#f9f9f9',
                    className: 'br-r',
                    type: 'line',
                    zoomType: 'x',
                    panning: true,
                    panKey: 'shift',
                    marginTop: 25,
                    marginRight: 1,
                },
                title: {
                    text: null
                },
                xAxis: {
                    gridLineColor: '#EEE',
                    lineColor: '#EEE',
                    tickColor: '#EEE',
                    labels: {
                        style: {
                            fontFamily: 'wyekan'
                        }
                    },
                    categories: [
                        @foreach($data['chart']['X'] as $x) '{{ $x }}' @unless($loop->last),@endunless @endforeach
                    ]
                },
                yAxis: {
                    min: 0,
                    tickInterval: 1,
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
                    enabled: false,
                },
                fontFamily: 'wyekan',
                series: [{
                    name: 'رژیم',
                    data: [@foreach($data['chart']['user']['Y'] as $y) {{ $y }} @unless($loop->last),@endunless @endforeach]
                }]
            });

        }
    </script>
@endpush
