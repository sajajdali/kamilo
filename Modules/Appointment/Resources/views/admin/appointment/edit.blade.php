@extends('admin.layout.layout')
@section('title',$title ?? 'تنظیم وقت')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="http://127.0.0.1/zend/public/admin/consulting/ticket/setting">نوبت دهی</a>
                </li>
                <li class="crumb-icon">
                    <a href="http://127.0.0.1/zend/public/admin/dashbord">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="http://127.0.0.1/zend/public">خانه</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section class="table-layout" id="content">

        <!-- begin: .tray-left -->
        <aside data-tray-height="match" class="tray tray-left tray290 va-t yekan" style="height: 1130px;">

            <div class="fc-title-clone date_show_my">شهریور ۱۳۹۵</div>

            <div class="section admin-form theme-primary">
                <div class="inline-mp center-block hasMonthpicker" id="dp1474268318877">
                    <div class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"
                         style="display: block;">
                        <div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all">
                            <div class="ui-datepicker-title"></div>
                        </div>
                        <table class="ui-datepicker-calendar rtl yekan right_month_picker">
                            <thead></thead>
                            <tbody>
                            <tr>
                                <td data-year="2016" data-month="1" data-event="click" class="change_month_right"><a
                                            href="#" class="ui-state-default">فروردین</a></td>
                                <td data-year="2016" data-month="2" data-event="click" data-handler="selectDay"
                                    class="change_month_right"><a href="#" class="ui-state-default">اردیبهشت</a></td>
                                <td data-year="2016" data-month="3" data-event="click" data-handler="selectDay"
                                    class="change_month_right "><a href="#" class="ui-state-default">خرداد</a></td>
                                <td data-year="2016" data-month="4" data-event="click" data-handler="selectDay"
                                    class="change_month_right "><a href="#" class="ui-state-default">تیر</a></td>
                            </tr>
                            <tr>
                                <td data-year="2016" data-month="5" data-event="click" data-handler="selectDay"
                                    class=" change_month_right"><a href="#" class="ui-state-default">مرداد</a></td>
                                <td data-year="2016" data-month="6" data-event="click" data-handler="selectDay"
                                    class="change_month_right "><a href="#" class="ui-state-default">شهریور</a></td>
                                <td data-year="2016" data-month="7" data-event="click" data-handler="selectDay"
                                    class="change_month_right "><a href="#" class="ui-state-default">مهر</a></td>
                                <td data-year="2016" data-month="8" data-event="click" data-handler="selectDay"
                                    class=" change_month_right"><a href="#" class="ui-state-default">آبان</a></td>
                            </tr>
                            <tr>
                                <td data-year="2016" data-month="9" data-event="click" data-handler="selectDay"
                                    class="change_month_right"><a href="#" class="ui-state-default">آذر</a></td>
                                <td data-year="2016" data-month="10" data-event="click" data-handler="selectDay"
                                    class=" change_month_right"><a href="#" class="ui-state-default">دی</a></td>
                                <td data-year="2016" data-month="11" data-event="click" data-handler="selectDay"
                                    class=" change_month_right"><a href="#" class="ui-state-default">بهمن</a></td>
                                <td data-year="2016" data-month="12" data-event="click" data-handler="selectDay"
                                    class=" change_month_right"><a href="#" class="ui-state-default">اسفند</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h4 class="ph10 mt25 mb20 rtl yekan"> راهنما </h4>
            <hr class="mn br-light">
            <ul role="menu" class="nav nav-messages p5 rtl yekan">
                <li class="change_show" data-class="fc-event">
                    <a class="text-dark fw600 p8 animated animated-short fadeInUp" href="#">
                        نمایش همه
                        <span class="fa fa-circle text-danger fs14 pull-left lh20"></span>
                    </a>
                </li>
                <li class="change_show" data-class="fc-event-success">
                    <a class="text-dark fw600 p8 animated animated-short fadeInUp" href="#">
                        روز های حضور
                        <span class="fa fa-circle text-system fs14 pull-left lh20"></span>
                    </a>
                </li>
                <li class="change_show" data-class="fc-event-info">
                    <a class="text-dark fw600 p8 animated animated-short fadeInUp" href="#">
                        روز های حضور خارج از برنامه
                        <span class="fa fa-circle text-info fs14 pull-left lh20"></span>
                    </a>
                </li>
                <li class="change_show" data-class="fc-event-warning">
                    <a class="text-dark fw600 p8 animated animated-short fadeInUp" href="#">
                        روزهای عدم حضور
                        <span class="fa fa-circle text-warning fs14 pull-left lh20"></span>
                    </a>
                </li>
            </ul>

        </aside>
        <!-- end: .tray-left -->

        <!-- begin: .tray-center -->
        <div class="tray tray-center p20 va-t rtl yekan">
            <!-- Calendar  -->
            <div class="admin-theme fc fc-ltr fc-unthemed" id="calendar" style="position: relative">
                <div class="loader" style="display: none">
                    <i class="glyphicons glyphicons-repeat"></i>
                </div>
                <div class="fc-toolbar">
                    <div class="fc-left">
                        <div class="fc-button-group">
                            <button class="fc-prev-button fc-button fc-state-default fc-corner-left next-month"
                                    type="button">
                                <span class="fc-icon fc-icon-left-single-arrow"></span></button>
                            <button class="fc-next-button fc-button fc-state-default fc-corner-right prev-month"
                                    type="button">
                                <span class="fc-icon fc-icon-right-single-arrow"></span></button>
                        </div>
                        <button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right today-btn"
                                type="button">امروز
                        </button>
                    </div>

                    <div class="fc-right"><h2 class="date_show_my">شهریور ۱۳۹۵</h2></div>

                    <div class="fc-clear"></div>
                </div>
                <div class="fc-view-container" style="">
                    <div class="fc-view fc-month-view fc-basic-view" style="">
                        <table>
                            <thead>
                            <tr>
                                <td class="fc-widget-header">
                                    <div class="fc-row fc-widget-header">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th class="fc-day-header fc-widget-header fc-sun">جمعه</th>
                                                <th class="fc-day-header fc-widget-header fc-mon">پنج شنبه</th>
                                                <th class="fc-day-header fc-widget-header fc-tue">چهارشنبه</th>
                                                <th class="fc-day-header fc-widget-header fc-wed">سه شنبه</th>
                                                <th class="fc-day-header fc-widget-header fc-thu">دوشنبه</th>
                                                <th class="fc-day-header fc-widget-header fc-fri">یکشنبه</th>
                                                <th class="fc-day-header fc-widget-header fc-sat">شنبه</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="fc-widget-content">
                                    <div class="fc-day-grid-container" style="">
                                        <div class="fc-day-grid rtl yekan" id="content_table">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: .tray-center -->

        <!-- end: .tray-left -->

    </section>
    <!-- End: Content -->
    <div class="admin-form theme-primary popup-basic popup-lg mfp-with-anim mfp-hide rtl yekan" id="calendarEvent">

        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title pull-right"><i class="fa fa-pencil-square"></i>اضافه کردن روز جدید
                </span>
            </div>
            <!-- end .form-header section -->

            <form method="post" action="/" id="calendarEventForm">
                <div class="panel-body p25">
                    <div class="section-divider mt10 mb40">
                        <span>ساعت حضور صبح</span>
                    </div>
                    <input type="hidden" name="data_add_event" id="data_add_event"/>
                    <!-- .section-divider -->
                    <div class="section row">
                        <div class="col-md-6 pull-right">
                            <label for="firstname" class="field prepend-icon">
                                <input type="text" name="from_am" id="from_am" class="gui-input time_picker"
                                       placeholder="صبح از ساعت"/>
                                <label for="from_am" class="field-icon"><i class="fa fa-clock-o"></i>
                                </label>
                            </label>
                        </div>
                        <!-- end section -->

                        <div class="col-md-6 pull-right">
                            <label for="firstname" class="field prepend-icon">
                                <input type="text" name="to_am" id="to_am" class="gui-input time_picker"
                                       placeholder="صبح تا ساعت">
                                <label for="to_am" class="field-icon"><i class="fa  fa-clock-o"></i>
                                </label>
                            </label>
                        </div>
                    </div>
                    <div class="section-divider mt20 mb40">
                        <span>ساعت حضور عصر</span>
                    </div>
                    <!-- .section-divider -->
                    <div class="section row">
                        <div class="col-md-6 pull-right">
                            <label for="firstname" class="field prepend-icon">
                                <input type="text" name="from_pm" id="from_pm" class="gui-input time_picker"
                                       placeholder="بعدظهر از ساعت">
                                <label for="from_pm" class="field-icon"><i class="fa fa-clock-o"></i>
                                </label>
                            </label>
                        </div>
                        <!-- end section -->

                        <div class="col-md-6 pull-right">
                            <label for="to_pm" class="field prepend-icon">
                                <input type="text" name="to_pm" id="to_pm" class="gui-input time_picker"
                                       placeholder="بعدظهر از ساعت">
                                <label for="to_pm" class="field-icon"><i class="fa fa-clock-o"></i>
                                </label>
                            </label>
                        </div>
                    </div>
                    <div class="section-divider mt30 mb40">
                        <span>تعداد نوبت‌ها</span>
                    </div>
                    <div class="section">
                        <div class="smart-widget sm-right" style="padding-right: 216px">
                            <label class="field">
                                <input type="text" name="user_max" id="user_max" class="gui-input"
                                       placeholder="حداکثر نوبت قابل ثبت توسط کاربر">
                            </label>
                            <button type="button" class="button btn-primary" disabled="disabled" style="width: 216px;">
                                حداکثر نوبت قابل ثبت توسط کاربر
                            </button>
                        </div>
                        <!-- end .smart-widget section -->
                    </div>
                    <div class="section">
                        <div class="smart-widget sm-right" style="padding-right: 216px">
                            <label class="field">
                                <input type="text" name="clerk_max" id="clerk_max" class="gui-input"
                                       placeholder="حداکثر نوبت قابل ثبت توسط منشی">
                            </label>
                            <button type="button" class="button btn-primary" disabled="disabled" style="width: 216px;">
                                حداکثر نوبت قابل ثبت توسط منشی
                            </button>
                        </div>
                        <!-- end .smart-widget section -->
                    </div>
                    <div class="section-divider mt30 mb40">
                        <span>نوع ویزیت</span>
                    </div>
                    <div class="section wraper_type_of_visit">
                        @foreach(Modules\Appointment\Entities\Appointment::$VISIT_TYPE as $key => $value)
                            <div class="checkbox-custom fill checkbox-primary mb5 pull-right" style="direction: ltr">
                                <input type="checkbox" id="visit_type_{{ $key }}"
                                       value="{{ $key }}"
                                       class="type_of_visit"
                                       name="type[]">
                                <label for="visit_type_{{ $key }}">{{ $value }}</label>
                            </div>
                        @endforeach
                        <div style="clear: both;"></div>
                    </div>

                </div>
                <!-- end .form-body section -->
                <div class="panel-footer text-left">
                    <button type="button" class="button btn-primary" id="add_event_exception">اضافه کردن</button>
                </div>
                <!-- end .form-footer section -->
            </form>
        </div>
        <!-- end .admin-form section -->
    </div>
    <a id="clickHelper" style="display: none" href="#calendarEvent" data-effect="mfp-flipInY">clickHelper</a>

@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/fullcalendar/fullcalendar.min.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">

    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">

    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/skin/default_skin/css/theme.css') }}">

    <!-- Favicon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/my-edit.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/my-edit-res.css') }}">
    <style>
        i.change_time_exception {
            margin-left: 5px;
        }

        .loader {
            background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;
            height: 100%;
            padding-top: 20%;
            position: absolute;
            text-align: center;
            width: 100%;
            z-index: 30000;
        }

        .loader i {
            font-size: 30px;
            -webkit-animation: spin 1.5s linear infinite;
            -moz-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }

        @-moz-keyframes spin {
            60% {
                -moz-transform: rotate(90deg);
            }
            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            60% {
                -webkit-transform: rotate(90deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            60% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        .compose-event-btn:hover {
            text-decoration: none;
        }

        .compose-event-btn {
            color: #ccc;
            display: block;
            font-size: 11px;
            margin-top: 40px;
            text-align: center;
            text-decoration: none;
        }

        .bg-danger {
            background: #fff1ee !important;
        }

        .text-danger {
            color: #e9573f !important;
        }

        .fc-view-container .fc-event {
            margin-top: 30px;
        }

        .hozor {
            color: #ccc;
            float: right;
            font-size: 10px;
            position: relative;
            right: 0;
            text-align: center;
            top: 83px;
            width: 100%;
        }

        .datepicker-plot-area {
            z-index: 10000;
        }

        .datepicker-plot-area .datepicker-time-view .divider {
            display: none;
        }
    </style>

    <script type="text/javascript"
            src="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.css') }}">

    <script type="text/javascript">
        var currect_month = "{{ jdate("n",'','','','en') }}";
        var currect_year = "{{ jdate("Y",'','','','en') }}";
        var currect_day = "{{ jdate("j",'','','','en') }}";
        var month_name = "{{ jdate("F",'','','','en') }}";
        var loader = $('.loader');
        var noteStyle = 'success';
        var noteShadow = true;
        var noteOpacity = '1';
        var noteStack = "stack_bottom_left";
        var width = "290px";
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
        }

        jQuery(document).ready(function () {
            $('#saman').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            $('#clickHelper').magnificPopup({
                removalDelay: 500, //delay removal by X to allow out-animation
                callbacks: {
                    beforeOpen: function (e) {
                        $('.time_picker').mask('99:99').clockpicker({
                            donetext: 'تایید',
                            autoclose: true,
                        });
                        $('body').addClass('mfp-bg-open');
                        this.st.mainClass = this.st.el.attr('data-effect');
                    },
                    afterClose: function (e) {
                        $('body').removeClass('mfp-bg-open');
                    }
                },
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
            updateCalender(currect_year, currect_month, currect_day);

            $(".prev-month").on('click', function () {
                if (currect_month == "01") {
                    currect_month = "12";
                    currect_year = parseInt(currect_year) - 1;
                } else {
                    currect_month = parseInt(currect_month) - 1;
                }
                updateCalender(currect_year, currect_month, currect_day);
            });
            $(".next-month").on('click', function () {
                if (currect_month == "12") {
                    currect_month = "01";
                    currect_year = parseInt(currect_year) + 1;
                } else {
                    currect_month = parseInt(currect_month) + 1;
                }
                updateCalender(currect_year, currect_month, currect_day);
            });
            $(".change_month_right").on('click', function () {
                var currect_month2 = $(this).data('month');
                updateCalender(currect_year, currect_month2, currect_day);
            });
            $(".today-btn").on('click', function () {
                currect_month = "{{ jdate("n",'','','','en') }}";
                currect_year = "{{ jdate("Y",'','','','en') }}";
                currect_day = "{{ jdate("j",'','','','en') }}";
                updateCalender(currect_year, currect_month, currect_day);
            });
            $('body').on('click', '.add_exeption', function () {
                var type = $(this).data('type');
                var date = $(this).parents('td').data('date');
                if (type) {

                } else {
                    ExceptionHelper(type, date, 'add');
                }
            });
            $('body').on('click', '.remove_exeption', function () {
                var id = $(this).data('id');
                ExceptionHelper('true', id, 'remove');
            });
            $('body').on('click', '.compose-event-btn', function () {
                $("#from_am").val("00:00");
                $("#to_am").val("00:00");
                $("#from_pm").val("00:00");
                $("#to_pm").val("00:00");
                $("#user_max").val("");
                $("#clerk_max").val("");
                @foreach(Modules\Appointment\Entities\Appointment::$VISIT_TYPE as $key => $value)
                    @if($loop->first)
                        $("#visit_type_{{ $key }}").prop('checked', true);
                    @else
                        $("#visit_type_{{ $key }}").prop('checked', false);
                    @endif
                @endforeach
                $('#data_add_event').val($(this).data('date'));
            });

            $('body').on('click', '#add_event_exception', function () {
                const date_add = $('#data_add_event').val();
                const from_am = $('#from_am').val();
                const to_am = $('#to_am').val();
                const from_pm = $('#from_pm').val();
                const to_pm = $('#to_pm').val();
                const clerkMax = $('#clerk_max').val();
                const userMax = $('#user_max').val();
                const types = $("input[name='type[]']")
                    .map(function(){return $(this).val();}).get();
                //start validation
                //-----step 1
                let amTimeFrom = getTimeInDay(from_am);
                let amTimeTo = getTimeInDay(to_am);
                let pmTimeFrom = getTimeInDay(from_pm);
                let pmTimeTo = getTimeInDay(to_pm);
                /*
                if(amTimeFrom !== amTimeTo){
                    if(amTimeFrom > 840 || amTimeTo > 840 || amTimeFrom > amTimeTo){
                        $('#from_am').fadeOut(100).fadeIn(300);
                        $('#to_am').fadeOut(100).fadeIn(300);
                        displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                        return false;
                    }
                }
                if(pmTimeFrom !== pmTimeTo){
                    if(pmTimeFrom < 840 || pmTimeTo < 840 || pmTimeFrom > pmTimeTo){
                        $('#from_pm').fadeOut(100).fadeIn(300);
                        $('#to_pm').fadeOut(100).fadeIn(300);
                        displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                        return false;
                    }
                }*/
                //-----step 2
                if(clerkMax.length >= 1 && !isInt(clerkMax)){
                    $('#clerk_max').fadeOut(100).fadeIn(300);
                    $('#clerk_max').focus();
                    displayErrorNotify("حداقل نوبت قابل اخذ توسط کاربر صحیح وارد نشده است");
                    return false;
                }
                if(userMax.length >= 1 && !isInt(userMax)){
                    $('#user_max').fadeOut(100).fadeIn(300);
                    $('#user_max').focus();
                    displayErrorNotify("حداقل نوبت قابل اخذ توسط کاربر صحیح وارد نشده است");
                    return false;
                }
                if($('.type_of_visit:checked').length == 0){
                    $('.wraper_type_of_visit').fadeOut(100).fadeIn(300);
                    displayErrorNotify("حداقل یک نوع ویزیت را انتخاب کنید");
                    return false;
                }
                addException(date_add, from_am, to_am, from_pm, to_pm,clerkMax,userMax,types);

            });
            $('.change_show').on('click', function () {
                var cls = $(this).data('class');
                $('.fc-event').hide();
                $("." + cls).show();
                return false;
            });

            $('body').on('click', '.change_time_exception', function () {
                getExceptionData($(this).data('date'));
            });
        });
        function displayErrorNotify(message) {
            new PNotify({
                title: 'خطا!',
                text: message,
                shadow: true,
                opacity: '1',
                addclass: 'stack_bar_top rtl yekan',
                type: 'warning',
                stack: {
                    "dir1": "down",
                    "dir2": "right",
                    "push": "top",
                    "spacing1": 0,
                    "spacing2": 0
                },
                width: "100%",
                delay: 3000,
            });
        }
        function getExceptionData(date) {
            $('input[name="type[]"]:checked').each(function () {
                $(this).removeAttr('checked');
            });
            loader.show();
            let result = false;
            data = new FormData();
            data.append("act", "getData");
            data.append("date", date);
            data.append("part_id", "{{ $data['part_id'] }}");
            data.append("doctor_id", "{{ $data['doctor_id'] }}");
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('admin/appointment/exceptionhelper') }}",
                cache: false,
                contentType: false,
                dataType: 'json',
                timeout: 3000000,
                processData: false,
                success: function (url) {
                    $.each(url, function (index, element) {
                        if (index === "status") {
                            result = element;
                        }
                        if (result) {
                            switch (index) {
                                case 'from_am':
                                    $("#from_am").val(element);
                                    break;
                                case 'to_am':
                                    $("#to_am").val(element);
                                    break;
                                case 'from_pm':
                                    $("#from_pm").val(element);
                                    break;
                                case 'to_pm':
                                    $("#to_pm").val(element);
                                    break;
                                case 'CLERK_MAX':
                                    $("#clerk_max").val(element);
                                    break;
                                case 'USER_MAX':
                                    $("#user_max").val(element);
                                    break;
                                case 'tarikh':
                                    $('#data_add_event').val(element);
                                    break;
                                case 'TYPE':
                                    element.forEach(function (item) {
                                        $("#visit_type_" + item).prop('checked', true);
                                    });
                                    break;
                            }
                        }
                        $('#clickHelper').click();
                        loader.fadeOut();
                    });
                },
            }).fail(function () {
                alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                loader.fadeOut();
            });
        }
        function addException(date_add, from_am, to_am, from_pm, to_pm,clerkMax,userMax,types) {
            var result = false;
            loader.show();
            data = new FormData();
            data.append("act", "add_day");
            data.append("date", date_add);
            data.append("from_am", from_am);
            data.append("to_am", to_am);
            data.append("from_pm", from_pm);
            data.append("to_pm", to_pm);
            data.append("clerkMax", clerkMax);
            data.append("userMax", userMax);
            data.append("types", types);
            data.append("part_id", "{{ $data['part_id'] }}");
            data.append("doctor_id", "{{ $data['doctor_id'] }}");
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('admin/appointment/exceptionhelper') }}",
                cache: false,
                contentType: false,
                dataType: 'json',
                timeout: 3000000,
                processData: false,
                success: function (url) {
                    $.each(url, function (index, element) {
                        if (index == "status") {
                            result = element;
                        }
                        if (result) {
                            new PNotify({
                                title: 'انجام شد!',
                                text: 'عملیات با موفقیت انجام شد.',
                                shadow: noteShadow,
                                opacity: noteOpacity,
                                addclass: noteStack,
                                type: noteStyle,
                                stack: Stacks[noteStack],
                                width: width,
                                delay: 3000,
                                addclass: 'rtl yekan'
                            });
                        }
                        $('.mfp-close').click();
                        updateCalender(currect_year, currect_month, currect_day);
                    });
                },
            }).fail(function () {
                $('.mfp-close').click();
                alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                loader.fadeOut();
            });
        }
        function ExceptionHelper(type, date, act) {
            loader.show();
            data = new FormData();
            data.append("act", act);
            data.append("content", date);
            data.append("type", type);
            data.append("part_id", "{{ $data['part_id'] }}");
            data.append("doctor_id", "{{ $data['doctor_id'] }}");
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('admin/appointment/exceptionhelper') }}",
                cache: false,
                contentType: false,
                dataType: 'json',
                timeout: 3000000,
                processData: false,
                success: function (url) {
                    $.each(url, function (index, element) {
                        var result = false;
                        if (index == "status") {
                            result = element;
                        }
                        if (result) {
                            new PNotify({
                                title: 'انجام شد!',
                                text: 'عملیات با موفقیت انجام شد.',
                                shadow: noteShadow,
                                opacity: noteOpacity,
                                addclass: noteStack,
                                type: noteStyle,
                                stack: Stacks[noteStack],
                                width: width,
                                delay: 3000,
                                addclass: 'rtl yekan'
                            });
                        }
                        updateCalender(currect_year, currect_month, currect_day);
                    });
                },
            }).fail(function () {
                alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                loader.fadeOut();
            });
        }
        function changeMonth(month_number, month_name, year) {
            var month_filed = $('.date_show_my');
            month_filed.text(month_name + " " + year);
            $('.right_month_picker tr td').each(function () {
                if ($(this).data('month') == month_number) {
                    $(this).find('a').addClass('ui-state-highlight ui-state-active')
                } else {
                    $(this).find('a').removeClass('ui-state-highlight ui-state-active')
                }
            });

            if (currect_year == "{{ jdate("Y",'','','','en') }}" && currect_month == "{{ jdate("n",'','','','en') }}" && currect_day == "{{ jdate("j",'','','','en') }}") {
                $(".today-btn").attr('disabled', 'disabled');
            } else {
                $(".today-btn").removeAttr('disabled');
            }
        }
        function updateCalender(year, month, day) {
            loader.show();
            data = new FormData();
            data.append("day", day);
            data.append("month", month);
            data.append("year", year);
            data.append("part_id", "{{ $data['part_id'] }}");
            data.append("doctor_id", "{{ $data['doctor_id'] }}");
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                data: data,
                type: "POST",
                url: "{{ url('admin/appointment/calenderhelper') }}",
                cache: false,
                contentType: false,
                dataType: 'json',
                timeout: 3000000,
                processData: false,
                success: function (url) {
                    $.each(url, function (index, element) {
                        if (index == "month_name") {
                            month_name = element;
                        } else if (index == "currect_day") {
                            currect_day = element;
                        } else if (index == "currect_month") {
                            currect_month = element;
                        } else if (index == "currect_year") {
                            currect_year = element;
                        } else {
                            $('#content_table').html(element);
                        }
                        changeMonth(currect_month, month_name, currect_year);
                        $(".checkss").tooltip();
                        $('.compose-event-btn').magnificPopup({
                            removalDelay: 500, //delay removal by X to allow out-animation
                            callbacks: {
                                beforeOpen: function (e) {
                                    $('.time_picker').mask('99:99').clockpicker({
                                        donetext: 'تایید',
                                        autoclose: true,
                                    });
                                    $('body').addClass('mfp-bg-open');
                                    this.st.mainClass = this.st.el.attr('data-effect');
                                },
                                afterClose: function (e) {
                                    $('body').removeClass('mfp-bg-open');
                                }
                            },
                            midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                        });
                        loader.fadeOut();
                    });
                },
            }).fail(function () {
                $('.closeModel').click();
                alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                loader.fadeOut();
            });
        }
        function isInt(value) {
            let x;
            if (isNaN(value)) {
                return false;
            }
            x = parseFloat(value);
            return (x | 0) === x;
        }
        function getTimeInDay(value){
            let timeBase = value.split(":");
            const min = parseInt(timeBase[1]);
            const hr = parseInt(timeBase[0]);
            return (hr*60)+min;
        }
    </script>

@endpush
@push('body_class') calendar-page @endpush
