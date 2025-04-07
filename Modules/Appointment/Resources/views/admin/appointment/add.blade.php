@extends('admin.layout.layout')
@section('title',$title ?? 'افزودن نوبت')
@section('content')
    @inject('appointmentNameSpace', 'Modules\Appointment\Entities\Appointment')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl">

            <ol class="breadcrumb">
                @foreach($breadcrumbPartName as $item)
                    <li class="crumb-trail">
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
                <li class="crumb-icon">
                    <a href="http://127.0.0.1/nobat/public/admin/dashbord">
                        <span class="glyphicons glyphicons-hand_left"></span>
                    </a>
                </li>
                <li class="crumb-active">
                    <a>{{ optional(\App\User::find($data['doctor_id']))->full_name }}</a>
                </li>
            </ol>

        </div>
    </header>
    <!-- End: Topbar -->
    <div id="content">
        <div class="row">
            <div class="com-md-12">

                @if(Session::has('successful'))
                    <div class="alert alert-success alert-dismissable rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                        <i class="fa fa-check pr10"></i>
                        {{ Session::get('successful') }}
                    </div>
                @endif
                @if(Session::has('sys_error'))
                    <div class="alert alert-danger alert-dismissable mb30 rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{ Session::get('sys_error') }}
                    </div>
                @endif
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
                @if($EditingAppointment && $EditingAppointment != null)
                    <div class="alert alert-info alert-dismissable mb30 rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h3 class="mt5">شما در حال ویرایش زمان نوبت {{$EditingAppointment->user->full_name}} با شماره پیگیری {{request()->get('code')}} میباشید</h3>

                        <p>زمان فعلی نوبت                                          {{ jdate('l j F Y',jmktime(0,0,0,$EditingAppointment->month,$EditingAppointment->day,$EditingAppointment->year)) }}
                            از ساعت {{$EditingAppointment->time_from}} تا ساعت {{$EditingAppointment->time_to}} میباشد
                        </p>


                    </div>
                @endif
                <div class="panel rtl yekan">
                    <div class="col-md-3 pl5-md message-tray">

{{--                        @if(Auth::user()->roles[0]['name']  == 'SUPER_ADMIN' || Auth::user()->id == '389')--}}
{{--                        <!-- quick compose button -->--}}
                        <button type="button" id="quick-compose" class="btn btn-danger light btn-block compose-btn">ثبت
                            نوبت
                        </button>
{{--                            @endif--}}
                        <br>

                        <!-- message tray panel -->
                        <div class="panel">
                            <div class="panel-body p10">
                                <h4 class="ph10 mv15"> راهنما </h4>
                                <hr class="mn br-light">
                                <ul class="nav nav-messages p5" role="menu">
                                    <li class="">
                                        <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                                            نوبت‌های در انتظار
                                            <span class="fa fa-circle text-warning fs14 pull-left lh20"></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                                            نوبت‌های کنسل شده
                                            <span class="fa fa-circle text-danger fs14 pull-left lh20"></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                                            نوبت‌های بین مریض
                                            <span class="fa fa-circle text-primary fs14 pull-left lh20"></span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                                            نوبت‌های ثبت شده
                                            <span class="fa fa-circle text-success fs14 pull-left lh20"></span>
                                        </a>
                                    </li>
                                </ul>

                                <h4 class="ph10 mv15" style="position: relative;"> ساعت های حضور
                                    <div class="pull-left">
                                        <button style="background: none;border: none;padding: 0px;" type="button"
                                                class="btn btn-default light dropdown-toggle ph8" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span class="fa fa-gear"></span>
                                        </button>
                                        <ul id="picker-ul" class="dropdown-menu" role="menu" style="left: 0px;">
                                            <li data-partid="{{ $data['part']->id }}">
                                                <a href="#" class="part_picker">{{ $data['part']->title }}</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="active" data-partid="0">
                                                <a href="#" class="part_picker">
                                                    <span class="fa fa-check pr5"></span>نمایش همه</a>
                                            </li>
                                        </ul>
                                    </div>
                                </h4>
                                <hr class="mn br-light"/>
                                <br/>
                                <div style="position: relative">
                                    <table class="table table-bordered animated animated-short fadeInLeft"
                                           id="doctor_time_table">
                                    </table>
                                    <div id="get_time_loader">
                                        <i class="glyphicons glyphicons-roundabout"></i>
                                    </div>
                                </div>
                                <h4 class="ph10 mv15" style="position: relative;">روزهای استثنا
                                </h4>
                                <hr class="mn br-light"/>
                                <br/>
                                <div id="external-events" class="bg-dotted">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 prn-md animated fadeIn">
                        <div class="panel">

                            <!-- message toolbar header -->
                            <div class="panel-menu">
                                <div class="row table-layout">
                                    <div class="hidden-xs hidden-sm col-md-3 pull-left text-left">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default light" id="refresh"><i
                                                        class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-9 text-right pull-right">
                                        <div class="btn-group ml10">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default light dropdown-toggle ph8"
                                                        data-toggle="dropdown">
                                                    <span class="fa fa-calendar"></span>
                                                    <span class="caret ml5"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li class="month_selector @if(1 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="1">فروردین</a>
                                                    </li>
                                                    <li class="month_selector @if(2 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="2">اردیبهشت</a>
                                                    </li>
                                                    <li class="month_selector @if(3 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="3">خرداد</a>
                                                    </li>
                                                    <li class="month_selector @if(4 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="4">تیر</a>
                                                    </li>
                                                    <li class="month_selector @if(5 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="5">مرداد</a>
                                                    </li>
                                                    <li class="month_selector @if(6 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="6">شهریور</a>
                                                    </li>
                                                    <li class="month_selector @if(7 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="7">مهر</a>
                                                    </li>
                                                    <li class="month_selector @if(8 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="8">آبان</a>
                                                    </li>
                                                    <li class="month_selector @if(9 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="9">آذر</a>
                                                    </li>
                                                    <li class="month_selector @if(10 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="10">دی</a>
                                                    </li>
                                                    <li class="month_selector @if(11 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="11">بهمن</a>
                                                    </li>
                                                    <li class="month_selector @if(12 == jdate('n','','','','en')) active @endif">
                                                        <a href="#" data-month="12">اسفند</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default light" id="nav-next-month"><i
                                                        class="fa fa-chevron-left"></i>
                                            </button>
                                            <button type="button" class="btn btn-default light" id="nav-prev-month"><i
                                                        class="fa fa-chevron-right"></i>
                                            </button>

                                        </div>
                                        <span class="hidden-xs va-m text-muted mr15"
                                              id="display_data">{{ jdate('l j F Y') }}</span>

                                    </div>
                                </div>
                            </div>

                            <!-- message listings table -->
                            <div class="panel-body pn br-t-n">
                                <div class="panel">
                                    <div class="panel-body pn" style="position: relative">
                                        <div class="calender_timeline">
                                            <a class="nav-month" href="#" id="nav-prev-day">
                                                <i class="glyphicons glyphicons-chevron-right"></i></a>
                                            @for($i=1;$i<=jdate('t','','','','en');$i++)
                                                <div class="cal_item @if($i == jdate('j','','','','en')) highlight @endif"
                                                     data-date="{{ $i }}" data-toggle="tooltip"
                                                     data-original-title="{{ jdate('l',(time()-((jdate('j','','','','en')-$i)*84600))) }}">
                                                    <div class="top"></div>
                                                    <a href="#" class="number">{{ $i }}</a>
                                                </div>
                                            @endfor
                                            <a class="nav-month" href="#" id="nav-next-day">
                                                <i class="glyphicons glyphicons-chevron-left"></i></a>
                                        </div>
                                        <table class="table table-bordered mbn table_appointment"
                                               id="appointment_content">
                                        </table>
                                        <div id="table_loader">
                                            <i class="glyphicons glyphicons-roundabout"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- BEGIN: PAGE SCRIPTS -->

    <!-- Admin Form Popup -->
    <div id="modal-form" class=" popup-basic admin-form mfp-with-anim mfp-hide">
        <div class="panel yekan rtl">
            <div class="panel-heading text-right">
                <span class="panel-title">افزودن نوبت جدید</span>
            </div>
            <!-- end .panel-heading section -->

            <form method="post" action="/" id="comment">
                <div class="panel-body p25">
                    <div id="add_step_1" style="display: none">
                        <div class="section row">
                            <div class="col-md-12">
                                <p class="lead text-right">شماره موبایل فرد مورد نظر را وارد کنید و برروی «مرحله
                                    بعد» کلیک کنید
                                </p>
                                <label for="email" class="field prepend-icon">
                                    <input type="text" name="mobile" id="add_mobile" class="gui-input"
                                           onkeyup="AutoFarsiNum('#add_mobile');"
                                           placeholder="شماره موبایل">
                                    <label for="add_mobile" class="field-icon"><i class="fa fa-mobile"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->
                        </div>
                        <!-- end section row section -->
                    </div>
                    <div id="add_step_2">
                        <div class="section-divider mb25" id="spy4">
                            <span class="header_expandable collapse" href=".item_user_data" id="user_details_header">مشخصات کاربر <i
                                        class="fa fa-chevron-down"></i></span>
                        </div>
                        <div class="section row item_user_data" style="display: none;">
                            <div class="col-md-6 pull-right">
                                <label for="firstname" class="field prepend-icon">
                                    <input type="text" name="firstname" id="add_firstname" class="gui-input"
                                           placeholder="نام">
                                    <label for="add_firstname" class="field-icon"><i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->

                            <div class="col-md-6 pull-right">
                                <label for="lastname" class="field prepend-icon">
                                    <input type="text" name="lastname" id="add_lastname" class="gui-input"
                                           placeholder="نام‌خانوادگی">
                                    <label for="add_lastname" class="field-icon"><i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->

                        </div>
                        <div class="section row item_user_data" style="display: none;">
                            <div class="col-md-6 pull-right">
                                <label class="field select">
                                    <select id="gender" name="gender">
                                        <option value="1">مرد</option>
                                        <option value="2">زن</option>
                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                            <!-- end section -->

                            <div class="col-md-6 pull-right text-right pt15" id="user_details">
                                کاربر جدید
                            </div>
                            <!-- end section -->

                        </div>


                        <div class="section row item_user_data" style="display: none;">
                            <div class="col-md-6 pull-right">
                                <label class="field select">
                                    <select id="acquainted" name="acquainted">
                                        <option value="">طریقه آشنایی</option>
                                        @foreach(\Modules\Appointment\Entities\Appointment::$ACQUAINTED as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach

                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                            <!-- end section -->

                            <div class="col-md-6 pull-right">
                                <label for="add_age" class="field prepend-icon">
                                    <input type="text" name="age" id="add_age" class="gui-input"
                                           placeholder="سن">
                                    <label for="add_age" class="field-icon"><i class="fa fa-info"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->

                        </div>
                        <div class="section row item_user_data" style="display: none;">

                            <!-- end section -->

                            <div class="col-md-12 pull-right">
                                <label for="add_address" class="field prepend-icon">
                                    <input type="text" name="address" id="add_address" class="gui-input"
                                           placeholder="محل سکونت">
                                    <label for="add_address" class="field-icon"><i class="glyphicons glyphicons-cargo"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->
                        </div>
                        <div class="section row item_user_data" style="display: none;">
                            <div class="col-md-6 pull-right">
                                <label class="field select">
                                    <select id="appointment_first_type" name="appointment_first_type">
                                        <option value="">نوع نوبت</option>
                                        @foreach(\Modules\Appointment\Entities\Appointment::$appointmentType as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach

                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                        </div>
                        <div class="section-divider mt40 mb25" id="spy8">
                            <span class="header_expandable collapse" href=".items_visit_type" id="add_visit_type">نوع ویزیت<i
                                        class="fa fa-chevron-down" style="margin-right: 8px;"></i></span>
                        </div>
                        <div class="section row items_visit_type" style="display: none;">
                            <div class="col-md-12 pull-right text-right">
                                @foreach($appointmentNameSpace::$VISIT_TYPE as $key => $value)
                                    <label for="visit_type_{{ $key }}" class="col-md-3 block mt15 option option-danger pull-right">
                                        <input type="radio" name="visit_type" id="visit_type_{{ $key }}"
                                               value="{{ $key }}" @if($loop->first) CHECKED @endif>
                                        <span class="radio"></span>{{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="section-divider mt40 mb25" id="spy2">
                            <span class="header_expandable collapse" href=".item_time_data" id="add_nobat_time">زمان<i
                                        class="fa fa-chevron-down" style="margin-right: 8px;"></i></span>
                        </div>
                        <div class="section row item_time_data" style="display: none;">
                            <div class="col-md-12 pull-right text-right pt20" id="add_display_date">
                                سه شنبه ۰۳ اردیبهشت ۱۳۹۸
                            </div>
                        </div>
                        <div class="section row item_time_data" style="display: none;">
                            <div class="col-md-6 pull-right">
                                <label for="firstname" class="field prepend-icon">
                                    <input type="text" name="start_time" id="start_time" class="gui-input time_select"
                                           placeholder="ساعت شروع">
                                    <label for="start_time" class="field-icon"><i class="fa fa-clock-o"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->

                            <div class="col-md-6 pull-right">
                                <label for="lastname" class="field prepend-icon">
                                    <input type="text" name="end_time" id="end_time" class="gui-input time_select"
                                           placeholder="ساعت پایان">
                                    <label for="end_time" class="field-icon"><i class="fa fa-clock-o"></i>
                                    </label>
                                </label>
                            </div>
                            <!-- end section -->
                        </div>
                        @if($data['appointment']->time_for_visit[$appointmentNameSpace::JSON_STATUS])
                            <div class="section-divider mt40 mb25" id="spy3">
                                <span>بخش‌های مرتبط</span>
                            </div>
                            <div class="section row">
                                <div class="col-md-12 pull-right text-right">
                                    @foreach($data['appointment']->time_for_visit[$appointmentNameSpace::JSON_TIME] as $item)
                                        @if($data['appointment']->time_for_visit[$appointmentNameSpace::JSON_SELECT_TYPE] == "multi")
                                            <div class="option-group field mb10">
                                                <label class="option option-primary">
                                                    <input type="checkbox" name="r_part"
                                                           class="related_part"
                                                           data-name="{{ $item[$appointmentNameSpace::JSON_TITLE] }}"
                                                           value="{{ $item[$appointmentNameSpace::JSON_TIME] }}">
                                                    <span class="checkbox"></span>{{ $item[$appointmentNameSpace::JSON_TITLE] }} - <small>{{ date('H:i', mktime(0,$item[$appointmentNameSpace::JSON_TIME])) }}</small>
                                                </label>
                                            </div>
                                        @else
                                            <label for="item_part_time_{{ $loop->index }}" class="block mt15 option option-primary">
                                                <input type="radio" name="r_part" id="item_part_time_{{ $loop->index }}" class="related_part"
                                                       value="{{ $item[$appointmentNameSpace::JSON_TIME] }}" data-name="{{ $item[$appointmentNameSpace::JSON_TITLE] }}">
                                                <span class="radio"></span>{{ $item[$appointmentNameSpace::JSON_TITLE] }} - <small>{{ date('H:i', mktime(0,$item[$appointmentNameSpace::JSON_TIME])) }}</small>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="section-divider mb25" id="spy4">
                            <span>نوع خدمت و کد معرف و توضیحات</span>
                        </div>
                        <div class="section row">
                            <div class="col-md-6 col-xs-12 pull-right">
                                <label for="add_service" class="field prepend-icon">
                                    <input type="text" name="service_detail" id="service_detail" class="gui-input"
                                           placeholder="نام خدمت">
                                    <label for="add_service" class="field-icon"><i class="glyphicons glyphicons-tie"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="col-md-6 col-xs-12 pull-right">
                                <label for="identifierـcode" class="field prepend-icon">
                                    <input type="text" name="identifierـcode" id="identifierـcode" class="gui-input"
                                           placeholder="کد معرف">
                                        <label for="identifierـcode" class="field-icon"><i class="glyphicons glyphicons-gift"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="col-md-12  pull-right" style="margin-top: 5px">
                                <label for="second_mobile" class="field prepend-icon">
                                    <input type="text" name="second_mobile" id="second_mobile" class="gui-input"
                                           placeholder="شماره دوم">
                                    <label for="second_mobile" class="field-icon"><i class="glyphicons glyphicons-headphones"></i>
                                    </label>
                                </label>
                            </div>
                            <div class="col-md-12 pull-right" style="margin-top: 5px">
                                <label for="service" class="field prepend-icon">
                                    <input type="text" name="service" id="service" class="gui-input"
                                           placeholder="توضیحات بیشتر">
                                    <label for="service" class="field-icon"><i class="fa fa-comment"></i>
                                    </label>
                                </label>
                            </div>
                        </div>


                        <div class="section-divider mb25" id="spy4">
                            <span>نوع نوبت</span>
                        </div>
                        <div class="section row">
                            <div class="col-md-4 pull-right text-right">
                                <label for="other" class="block mt15 option option-dark">
                                    <input type="radio" name="type_appointment" id="other" value="1" checked>
                                    <span class="radio"></span>نوبت اصلی</label>
                            </div>
                            <div class="col-md-4 pull-right text-right">
                                <label for="other2" class="block mt15 option option-dark">
                                    <input type="radio" name="type_appointment" id="other2" value="0">
                                    <span class="radio"></span>بین مریض</label>
                            </div>

                        </div>
                        <div class="section-divider mt40 mb25" id="spy6">
                            <span>وضعیت نوبت </span>
                        </div>
                        <div class="section row">
                            <div class="col-md-4 pull-right text-right">
                                <label for="appointment_type" class="block mt15 option option-dark">
                                    <input type="radio" name="status_appointment" id="appointment_type" value="1" checked>
                                    <span class="radio"></span>تایید شده</label>
                            </div>
                            <div class="col-md-4 pull-right text-right">
                                <label for="appointment_type2" class="block mt15 option option-dark">
                                    <input type="radio" name="status_appointment" id="appointment_type2" value="0">
                                    <span class="radio"></span>در انتظار</label>
                            </div>
                            <div class="col-md-4 pull-right text-right">
                                <label for="appointment_type6" class="block mt15 option option-dark">
                                    <input type="radio" name="status_appointment" id="appointment_type6" value="6">
                                    <span class="radio"></span>تماس  بدون وقت</label>
                            </div>
                        </div>
                        <div class="section-divider mt40 mb25" id="spy5">
                            <span>ارسال پیامک</span>
                        </div>
                        <div class="section row">
                            <div class="col-md-6 pull-right text-right">
                                <label for="sms_not_send" class="block mt15 option option-system">
                                    <input type="radio" name="sms_status" id="sms_not_send" value="0" checked>
                                    <span class="radio"></span>پیامک ارسال نشود</label>

                            </div>
                            <div class="col-md-6 pull-right text-right">
                                <label for="sms_send" class="block mt15 option option-system">
                                    <input type="radio" name="sms_status" id="sms_send" value="1">
                                    <span class="radio"></span>پیامک ارسال شود</label>
                            </div>
                        </div>
                        <div class="section row" style="display: none;" id="type_sms_text">
                            <div class="col-md-6 pull-right text-right">
                                <label for="sms_default_message" class="block mt15 option option-danger">
                                    <input type="radio" name="sms_text_status" id="sms_default_message" value="0"
                                           class="sms_text_status" checked>
                                    <span class="radio"></span>استفاده از متن پیشفرض</label>

                            </div>
                            <div class="col-md-6 pull-right text-right">
                                <label for="sms_custom_message" class="block mt15 option option-danger">
                                    <input type="radio" name="sms_text_status" class="sms_text_status"
                                           id="sms_custom_message" value="1">
                                    <span class="radio"></span>درج متن پیامک</label>
                            </div>
                        </div>
                        <div class="section row" style="display: none;" id="sms_text_content">
                            <div class="col-md-12">
                                <div class="section">
                                    <label class="field prepend-icon">
                                        <textarea class="gui-textarea" id="custom_sms_text" name="custom_sms_text"
                                                  placeholder="متن پیامک"></textarea>
                                        <label for="custom_sms_text" class="field-icon"><i class="fa fa-comments"></i>
                                        </label>
                                        <span class="input-footer">
                                                            <strong>راهنما: </strong>متن پیامک را وارد کنید</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end .form-body section -->
                <input type="hidden" name="user_id" id="user_id"/>
                <input type="hidden" name="const_now_year" id="const_now_year"/>
                <input type="hidden" name="usconst_now_monther_id" id="const_now_month"/>
                <input type="hidden" name="const_now_day" id="const_now_day"/>
                <div class="panel-footer">
                    <button type="button" class="btn ladda-button btn-primary" data-style="contract">
                        <span class="ladda-label">مرحله بعد</span>
                        <span class="ladda-spinner"></span></button>
                    <button type="button" class="btn btn-danger pull-right" id="prev_step" style="display: none"><i
                                class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <!-- end .form-footer section -->
            </form>
        </div>
        <!-- end: .panel -->
    </div>
    <!-- end: .admin-form -->
    <!-- Panel popup -->
    <div id="model-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <form action="" method="POST" id="delete_link">
            @method('DELETE')
            @csrf
            <div class="panel">
                <div class="panel-heading" style="text-align: right">
								<span class="panel-icon"><i class="fa fa-pencil"></i>
								</span>
                    <span class="panel-title">حذف نوبت</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این نوبت اطمینان دارید؟</h3>
                </div>
                <div class="panel-footer text-left">

                    <button class="btn btn-primary" type="submit">بله حذف کن</button>
                </div>
            </div>
        </form>

    </div>
    <div id="model-edit" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <form action="" method="POST" id="delete_link">
            <input type="hidden" id="appointment_code" value="" name="appointment_code">
            <input type="hidden" id="appointment_update_start_time" value="" name="appointment_update_start_time">
            <input type="hidden" value="" id="appointment_update_end_time" name="appointment_update_end_time">
            @csrf
            <div class="panel">
                <div class="panel-heading" style="text-align: right">
								<span class="panel-icon"><i class="fa fa-pencil"></i>
								</span>
                    <span class="panel-title">ویرایش زمان نوبت</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <div style="display: none" class="loading_update text-center"><img src="{{ url('loading.gif') }}" alt="در حال بارگزاری..."></div>
                    <div class="result_message">
                        <h3 class="mt5">آیا میخواهید نوبت انتخابی به همین زمان تغییر کند ؟</h3>


                    </div>
                    <div class="col-md-12 pull-right send_sms_after_change_time">
                        <div class=" fill checkbox-primary mb5" dir="lt">
                            <input type="checkbox" dir="ltr" id="send_sms_after_change_time" class="type_of_visit" value="ok" name="send_sms_after_change_time">
                            <label for="send_sms_after_change_time">ارسال پیامک به کاربر پس از تغییر زمان نوبت</label>
                        </div>
                    </div>


                </div>
                <div class="panel-footer text-left">

                    <a class="btn btn-primary" href="#" id="update_appointment_time">بله انتقال انجام شود</a>
                </div>
            </div>
        </form>

    </div>
    <div id="model-panel3" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-body text-center" style="text-align: right">
                <h3 class="mt5">در حال انجام...</h3>
                <img src="{{ url('loading.gif') }}" alt="در حال بارگزاری...">
            </div>
        </div>

    </div>

    <div id="modal-alert" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                                    <span class="panel-icon"><i class="fa fa-pencil"></i>
                                    </span>
                <span class="panel-title">لطفا اخطارهای زیر را بررسی کنید</span>
            </div>

            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">با توجه به اخطار های زیر آیا مایل به ثبت نوبت میباشید؟</h3>
                <div id="content-error">

                </div>
            </div>
            <div class="panel-footer text-left">

                <a class="btn btn-danger" id="cancell_appointment" href="#">خیر ثبت نشود</a>
                <a class="btn btn-success" id="success_appointment" href="#">بله ثبت شود</a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <script type="text/javascript"
            src="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.css') }}">
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>


    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/daterange/daterangepicker.js') }}"></script>


    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet"
          href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>

    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.js') }}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.css') }}">
    <style>
        .header_expandable {
            cursor: pointer;
        }

        .popup-full {
            margin: 0 auto;
        }

        #table_loader {
            display: none;
            background: rgba(55, 188, 155, 0.7) none repeat scroll 0 0;
            border-radius: 10px;
            color: white;
            font-size: 30px;
            height: 97%;
            padding-top: 50%;
            position: absolute;
            text-align: center;
            top: 50px;
            width: 100%;
        }

        #table_loader i {
            -webkit-animation: spin 1.5s linear infinite;
            -moz-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
            position: absolute;
            top: 50px;
        }

        #get_time_loader {
            display: none;
            background: rgba(55, 188, 155, 0.7) none repeat scroll 0 0;
            border-radius: 10px;
            color: white;
            font-size: 30px;
            height: 100%;
            padding-top: 50%;
            position: absolute;
            text-align: center;
            top: 0;
            width: 100%;
        }

        #get_time_loader i {
            -webkit-animation: spin 1.5s linear infinite;
            -moz-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }

        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(-360deg);
            }
        }

        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(-360deg);
            }
        }

        @keyframes spin {
            100% {
                -webkit-transform: rotate(-360deg);
                transform: rotate(-360deg);
            }
        }

        .calender_timeline {
            margin: 0 auto;
            padding: 11px 10px;
            width: 100%;
            position: relative;
        }

        .calender_timeline .cal_item {
            float: right;
            border-left: 1px solid #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 15px;
            height: 100%;
            line-height: 33px;
            position: relative;
            top: -5px;
            width: 25.6px;
            text-align: center;
        }

        .calender_timeline .cal_item_deactivate {
            float: right;
            border-left: 1px solid #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 15px;
            height: 100%;
            line-height: 33px;
            position: relative;
            top: -5px;
            width: 25.6px;
            text-align: center;
        }

        .calender_timeline .cal_item:hover .number {
            background-color: #d4d4d4;
            color: #5f5f5f;
            text-decoration: none;
            transition: all 200ms ease 0s;
        }

        .calender_timeline .cal_item .number {
            background-color: #f3f3f3;
            border: 1px solid #eee;
            color: #969696;
            display: block;
            transition: all 200ms ease 0s;
        }

        .calender_timeline .cal_item_deactivate .number {
            background-color: #f3f3f3;
            border: 1px solid #eee;
            color: #969696;
            display: block;
            transition: all 200ms ease 0s;
        }

        .calender_timeline .cal_item .top {
            background-color: #969696;
            height: 5px;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .calender_timeline .cal_item_deactivate .top {
            background-color: #969696;
            height: 5px;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .calender_timeline .cal_item.highlight .top {
            background-color: #2d75ec;
            z-index: 1;
        }

        .calender_timeline .cal_item.highlight .number::after {
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #4285f4;
            bottom: -9px;
            color: #4285f4;
            content: "";
            height: 0;
            position: absolute;
            right: 1px;
            width: 0;
        }

        .calender_timeline .cal_item.highlight .number {
            background-color: #4285f4;
            border-color: #4285f4;
            color: #fff;
            position: relative;
        }

        .nav-month {
            border-top: 5px solid #e6e6e6;
            float: right;
            color: #bbbbbb;
            display: inline-block;
            font-size: 15px;
            height: 100%;
            line-height: 33px;
            position: relative;
            text-align: center;
            top: -5px;
            width: 25.6px;
        }

        .tooltip.top .tooltip-inner {
            background-color: #00acd6;
            color: #fff;
            font-family: wyekan, Roboto !important;
        }

        .tooltip.top .tooltip-arrow {
            border-top-color: #00acd6;
        }

        .selp-label-alert {
            background-color: #ece7f9;
            border-color: #c6b7ec;
            color: #542fb2;
        }

        .selp-label-warning {
            background-color: #fdf0d4;
            border-color: #fadb9a;
            color: #c88a0a;
        }

        .free_time_class {
            height: 50px;
        }

        .free_time_class td {
            font-size: 14px;
        }

        .free_time_class td .time_display {
            color: rgb(0, 0, 0);
            font-size: 17px;
        }

        .free_time_class td .label-info {
            font-weight: normal
        }

        .datepicker-plot-area {
            z-index: 100000;

        }

        .datepicker-plot-area .divider {
            opacity: 0;
            display: none !important;
        }

        .add_appoint_dast {
            cursor: pointer;
        }

        .ladda-button[data-style="contract"] .ladda-spinner {
            left: 5% !important;
        }

        .calender_timeline .cal_item.full_mark .top {
            background-color: #a94442;
        }

        .calender_timeline .cal_item.full_mark .number {
            background-color: #f2dede !important;
            color: #a94442 !important;
        }

        .calender_timeline .cal_item.day_after .top {
            background-color: #8a6d3b;
        }

        .calender_timeline .cal_item.day_after .number {
            background-color: #fcf8e3 !important;
            color: #8a6d3b !important;
        }


        .calender_timeline .cal_item.holiday .top ,.calender_timeline .cal_item_deactivate.holiday .top  {
            background-color: #d43b00 !important;
        }

        .calender_timeline .cal_item.holiday .number {
            background-color: #ec8585 !important;
            color: #e6e6e6 !important;
        }

        .calender_timeline .cal_item_deactivate .top {
            background-color: #ccc;
        }

        .calender_timeline .cal_item_deactivate .number {
            background-color: #fff !important;
            color: #bbb !important;
            cursor: default;
        }

        .header_expandable i {
            font-size: 12px;
        }

        span.expanded i {
            -webkit-transform: rotate(-180deg); /* Chrome, Safari 3.1+ */
            -moz-transform: rotate(-180deg); /* Firefox 3.5-15 */
            -ms-transform: rotate(-180deg); /* IE 9 */
            -o-transform: rotate(-180deg); /* Opera 10.50-12.00 */
            transform: rotate(-180deg); /* Firefox 16+, IE 10+, Opera 12.10+ */
            transition: all 0.6s linear;
        }

        span.collapse i {
            transition: all 0.4s linear;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){

            $('body').on('click','.has_action_with_loading', function (e) {
                const action = $(this).data('action');
                const id = $(this).data('id');
                e.preventDefault();
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#model-panel3'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = "mfp-slideDown";
                        }
                    },
                    midClick: true,
                    closeBtnInside: false,
                    closeOnBgClick: false,
                });
                changeActionRequest(action,id);
            });
            $('body').on('click','.delete_blog', function (e) {
                e.preventDefault();
                const link = $(this).attr('href');
                $('#delete_link').attr('action', link);
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#model-panel2'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_blog").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });

            function changeActionRequest(action,id) {
                const data = new FormData();
                data.append("action", action);
                data.append("id", id);
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/changeAction') }}",
                    cache: false,
                    contentType: false,
                    timeout: 60000,
                    processData: false,
                    success: function (data) {
                        if(data['status']){
                            $.magnificPopup.close();
                            $('.highlight').click();
                            new PNotify({
                                title: 'موفق!',
                                text: data['message'],
                                shadow: true,
                                opacity: '1',
                                addclass: 'stack_bar_top rtl yekan',
                                type: 'success',
                                stack: {
                                    "dir1": "down",
                                    "dir2": "right",
                                    "push": "top",
                                    "spacing1": 0,
                                    "spacing2": 0
                                },
                                width: "100%",
                                delay: 30000,

                            });
                        }else{
                            new PNotify({
                                title: 'خطا!',
                                text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
                                shadow: true,
                                opacity: '1',
                                addclass: 'stack_bar_top rtl yekan',
                                type: 'danger',
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
                    },
                }).fail(function () {
                    $.magnificPopup.close();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
                        shadow: true,
                        opacity: '1',
                        addclass: 'stack_bar_top rtl yekan',
                        type: 'danger',
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
                });
            }

        });
    </script>
    <script type="text/javascript">
        let isTimeSet = false;

        function AutoFarsiNum(btnid) {
            let val = $(btnid).val();
            val = val.replace('۰', '0');
            val = val.replace('۱', '1');
            val = val.replace('۲', '2');
            val = val.replace('۳', '3');
            val = val.replace('۴', '4');
            val = val.replace('۵', '5');
            val = val.replace('۶', '6');
            val = val.replace('۷', '7');
            val = val.replace('۸', '8');
            val = val.replace('۹', '9');
            $(btnid).val(val);
        }

        $(document).ready(function () {
            let totalTime;
            let endTimeValue;

            let stepAddAppointment = 0;
            const ladda_btn = Ladda.create(document.querySelector('.ladda-button'));
            $('body').on('click','#success_appointment', function () {
                $.magnificPopup.close();
                ladda_btn.start();
                requestFinalRegister();
            });
            $('body').on('click','#cancell_appointment', function () {
                //$.magnificPopup.close();
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-form'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = "mfp-zoomIn";
                            isTimeSet = true;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });


            $('body').on('click','.btn-add-appointment', function () {
                const dateClick = $(this);
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-form'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = "mfp-zoomIn";
                            stepAddAppointment = 0;
                            $("#add_step_1").show();
                            $("#add_step_2").hide();
                            $("#prev_step").hide();
                            ladda_btn.stop();
                            $('.ladda-button .ladda-label').html("مرحله بعد");
                            $("#add_mobile").val("");
                            if ($('#user_details_header').hasClass("expanded")) {
                                $('#user_details_header').click();
                            }
                            if ($('#add_nobat_time').hasClass("expanded")) {
                                $('#add_nobat_time').click();
                            }
                            if ($('#add_visit_type').hasClass("expanded")) {
                                $('#add_visit_type').click();
                            }
                            $("#visit_type_IN_PERSON").click();
                            $("#other").click();
                            $("#sms_not_send").click();
                            $('#start_time').val(dateClick.data('time-start'));
                            $('#end_time').val(dateClick.data('time-end'));
                            endTimeValue = dateClick.data('time-end');
                            $('#start_time').attr('data-time',dateClick.data('time-start'));
                            $('#end_time').attr('data-time',dateClick.data('time-end'));
                            $('.related_part').removeAttr('checked');
                            isTimeSet = true;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#quick-compose').on('click', function () {
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-form'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = "mfp-zoomIn";
                            stepAddAppointment = 0;
                            $("#add_step_1").show();
                            $("#add_step_2").hide();
                            $("#prev_step").hide();
                            ladda_btn.stop();
                            $('.ladda-button .ladda-label').html("مرحله بعد");
                            $("#add_mobile").val("");
                            if ($('#user_details_header').hasClass("expanded")) {
                                $('#user_details_header').click();
                            }
                            if ($('#add_nobat_time').hasClass("expanded")) {
                                $('#add_nobat_time').click();
                            }
                            if ($('#add_visit_type').hasClass("expanded")) {
                                $('#add_visit_type').click();
                            }
                            $("#visit_type_IN_PERSON").click();
                            $("#other").click();
                            $("#sms_not_send").click();
                            $('#start_time').val("");
                            $('#end_time').val("");
                            $('#start_time').attr('data-time',"");
                            $('#end_time').attr('data-time',"");
                            $('.related_part').removeAttr('checked');
                            isTimeSet =false;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            const mobileCheck = new RegExp('(\\+98|0)?9\\d{9}');
            $('.ladda-button').on('click', function () {
                if (stepAddAppointment === 0) {
                    const mobile = $("#add_mobile").val();
                    if (mobileCheck.test(mobile)) {
                        ladda_btn.start();
                        getUserDetail();
                    } else {
                        new PNotify({
                            title: 'خطا!',
                            text: "شماره موبایل وارد شده صحیح نمی‌باشد",
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
                } else {
                    let hasError = false;
                    let hasMessage = false;

                    if ($('#add_firstname').val().length === 0) {
                        hasError = true;
                        $('#add_firstname').focus();
                        hasMessage = "نام کاربر را وارد کنید";


                    }

                    if (!hasError && $('#add_lastname').val().length === 0) {
                        hasError = true;
                        $('#add_lastname').focus();
                        hasMessage = "نام خانوادگی کاربر را وارد کنید";
                    }
                    if (!hasError && $('#start_time').val().length === 0) {
                        hasError = true;
                        $('#start_time').focus();
                        hasMessage = "تاریخ شروع را وارد کنید";
                    }
                    if (!hasError && $('#end_time').val().length === 0) {
                        hasError = true;
                        $('#end_time').focus();
                        hasMessage = "تاریخ پایان را وارد کنید";
                    }
                    if (!hasError && getTimeInDay($('#start_time').val()) >= getTimeInDay($('#end_time').val())) {
                        hasError = true;
                        $('#start_time').focus();
                        hasMessage = "تاریخ شروع و پایان به درستی وارد نشده است";
                    }
                    if (hasError) {
                        new PNotify({
                            title: 'خطا!',
                            text: hasMessage,
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
                    } else {
                        checkAppointment();
                    }
//                    else {
//                        ladda_btn.start();
//                        requestFinalRegister();
//                    }
                }
            });
            $('.time_select').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            $('input[name="sms_status"]').change(function () {
                if (this.value === '0') {
                    $('#type_sms_text').hide();
                    $('#sms_default_message').click();
                } else if (this.value === '1') {
                    $('#type_sms_text').slideDown();
                }
            });
            $('input[name="sms_text_status"]').change(function () {
                if (this.value === '0') {
                    $('#sms_text_content').hide();
                } else if (this.value === '1') {
                    $('#sms_text_content').slideDown();
                }
            });
            $('#prev_step').click(function () {
                stepAddAppointment = 0;
                $("#add_step_1").slideDown();
                $("#add_step_2").hide();
                $("#prev_step").hide();
                ladda_btn.stop();
                $('.ladda-button .ladda-label').html("مرحله بعد");
                $("#add_mobile").val("");
                if ($('#user_details_header').hasClass("expanded")) {
                    $('#user_details_header').click();
                }
                if ($('#add_nobat_time').hasClass("expanded")) {
                    $('#add_nobat_time').click();
                }
                if ($('#add_visit_type').hasClass("expanded")) {
                    $('#add_visit_type').click();
                }
                $("#visit_type_IN_PERSON").click();
                $("#other").click();
                $("#sms_not_send").click();
                $('.related_part').removeAttr('checked');
                if($("#end_time").data("time") != undefined){
                    $("#end_time").val($("#end_time").data("time"));
                }
            });

            function checkAppointment() {
                const data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("user_id", $("#user_id").val());
                data.append("start_time", $("#start_time").val() + ":00");
                data.append("end_time", $("#end_time").val() + ":00");
                data.append("kind", $("input[name='type_appointment']:checked").val());
                data.append("status", $("input[name='status_appointment']:checked").val());
                data.append("type", $("input[name='visit_type']:checked ").val());
                data.append("year", $("#const_now_year").val());
                data.append("month", $("#const_now_month").val());
                data.append("day", $("#const_now_day").val());
                data.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/checkAppointment') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        ladda_btn.stop();
                        if(data['error'] == false){
                            ladda_btn.start();
                            requestFinalRegister();
                        }
                        else{
                            if(data['accessToInsert'] == false){
                                $("#success_appointment").css('display' , 'none');
                            } else {
                                $("#success_appointment").css('display' , 'inline-block');

                            }

                            $('#content-error').html(data['message'].join('<br />'));

                            $.magnificPopup.open({
                                removalDelay: 500, //delay removal by X to allow out-animation,
                                items: {

                                    src: '#modal-alert'
                                },
                                // overflowY: 'hidden', //
                                callbacks: {

                                    beforeOpen: function (e) {

                                    }
                                },
                                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.

                            });


                        }
                    },
                }).fail(function () {
                    ladda_btn.stop();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                });
            }

            $('body').on('click','.btn-edit-appointment', function () {
                $("#update_appointment_time").html("بله انتقال انجام شود");
                $("#update_appointment_time").removeClass("btn-danger");
                $("#send_sms_after_change_time").attr("checked", false);
                $(".result_message").html('<h3 class="mt5">آیا میخواهید نوبت انتخابی به همین زمان تغییر کند ؟</h3>');
                const dateClick = $(this);
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#model-edit'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            $('#appointment_code').val("{{Request::get('code')}}");
                            $('#appointment_update_start_time').val(dateClick.data('time-start'));
                            $('#appointment_update_end_time').val(dateClick.data('time-end'));
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });

            function checkAppointmentUpdateTime() {
                const data = new FormData();
                data.append("appointment_code", '{{ request()->get('code') }}');
                data.append("start_time", $("#appointment_update_start_time").val() + ":00");
                data.append("end_time", $("#appointment_update_end_time").val() + ":00");
                data.append("year", $("#const_now_year").val());
                data.append("month", $("#const_now_month").val());
                data.append("day", $("#const_now_day").val());
                data.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/checkAppointmentChangeTime') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        ladda_btn.stop();
                        if(data['error'] == false){
                            $("#update_appointment_time").css('display', 'inline');
                            $(".loading_update").css('display', 'none');
                            requestUpdateAppointment();
                        }
                        else{
                            $("#update_appointment_time").css('display', 'inline');
                            $(".loading_update").css('display', 'none');
                            $(".result_message").css('display','block');
                            $(".result_message").html('<h3 class="alert alert-warning">با توجه به اخطارهای زیر آیا باز هم میخواهید انتقال نوبت انجام گیرد ؟</h3>');
                            var dataRespone = data['message'].join('<br />');
                            $(".result_message").append(dataRespone);
                            $("#update_appointment_time").html("مطمئن هستم میخواهم انتقال انجام گیرد");
                            $("#update_appointment_time").addClass("btn-danger success_transfer");
                        }
                    },
                }).fail(function () {
                    ladda_btn.stop();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                });
            }
            $('body').on('click','#update_appointment_time', function () {

                $(".loading_update").css('display', 'block');
                $(this).css('display', 'none');
                if($(this).hasClass('success_transfer')){
                    $(this).removeClass('success_transfer');
                    requestUpdateAppointment();
                    $("#update_appointment_time").css('display', 'inline');
                    $(".loading_update").css('display', 'none');
                    $(".result_message").css('display','block');
                }else {
                    checkAppointmentUpdateTime();
                }

            });
            function requestUpdateAppointment() {
                const data = new FormData();
                data.append("code", '{{request()->get('code')}}');
                data.append("start_time", $("#appointment_update_start_time").val() + ":00");
                data.append("year", $("#const_now_year").val());
                data.append("month", $("#const_now_month").val());
                data.append("send_sms_after_change_time", $("#send_sms_after_change_time").prop('checked') === true) ? 1 : 2;
                data.append("day", $("#const_now_day").val());
                data.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/updateTime') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {

                        ladda_btn.stop();
                        if(data['status']){
                            $.magnificPopup.close();
                            $('.highlight').click();
                            new PNotify({
                                title: 'موفق!',
                                text: "زمان نوبت شما با کد " + data["code"] + " با موفقیت شد",
                                shadow: true,
                                opacity: '1',
                                addclass: 'stack_bar_top rtl yekan',
                                type: 'success',
                                height : '500px',
                                stack: {
                                    "dir1": "down",
                                    "dir2": "right",
                                    "push": "top",
                                    "spacing1": 0,
                                    "spacing2": 0
                                },
                                width: "100%",
                                delay: 1000,
                                callbacks: {
                                    function (e) {
                                        alert('sa');
                                    }
                                },


                            });
                        }else{
                            new PNotify({
                                title: 'خطا!',
                                text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                    },
                }).fail(function () {
                    ladda_btn.stop();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                });

            }
            function requestFinalRegister() {
                const data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("mobile", $("#add_mobile").val());
                data.append("user_id", $("#user_id").val());
                data.append("firstname", $("#add_firstname").val());
                data.append("lastname", $("#add_lastname").val());
                data.append("service_detail", $("#service_detail").val());
                data.append("identifierـcode", $("#identifierـcode").val());
                data.append("second_mobile", $("#second_mobile").val());
                data.append("acquainted", $("#acquainted").val());
                data.append("appointment_first_type", $("#appointment_first_type").val());
                data.append("age", $("#add_age").val());
                data.append("service", $("#service").val());
                data.append("address", $("#add_address").val());
                data.append("gender", $("#gender").val());
                data.append("start_time", $("#start_time").val() + ":00");
                data.append("end_time", $("#end_time").val() + ":00");
                data.append("kind", $("input[name='type_appointment']:checked").val());
                data.append("status", $("input[name='status_appointment']:checked").val());
                data.append("sms_status", $("input[name='sms_status']:checked").val());
                data.append("sms_text_status", $("input[name='sms_text_status']:checked").val());
                data.append("type", $("input[name='visit_type']:checked").val());
                data.append("custom_sms_text", $("#custom_sms_text").val());
                data.append("year", $("#const_now_year").val());
                data.append("month", $("#const_now_month").val());
                data.append("day", $("#const_now_day").val());
                data.append("_token", "{{ csrf_token() }}");
                $("input[name='r_part']:checked").each(function() {
                    var partName = $(this).attr('data-name');
                    data.append("r_part["+partName+"]", $(this).val());
                });
                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/storeAppointment') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        ladda_btn.stop();
                        if(data['status']){
                            $.magnificPopup.close();
                            $('.highlight').click();
                            new PNotify({
                                title: 'موفق!',
                                text: "نوبت شما با کد " + data["code"] + " به ثبت رسید",
                                shadow: true,
                                opacity: '1',
                                addclass: 'stack_bar_top rtl yekan',
                                type: 'success',
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
                        }else{
                            new PNotify({
                                title: 'خطا!',
                                text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                    },
                }).fail(function () {
                    ladda_btn.stop();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                });
            }
            $('.header_expandable').click(function () {
                const selector = $(this).attr('href');
                if ($(this).hasClass('collapse')) {
                    $(selector).slideDown();
                    $(this).removeClass('collapse').addClass('expanded');
                } else {
                    $(selector).slideUp();
                    $(this).addClass('collapse').removeClass('expanded');
                }
            });
            function getTimeInDay(value) {
                let timeBase = value.split(":");
                const min = parseInt(timeBase[1]);
                const hr = parseInt(timeBase[0]);
                return (hr * 60) + min;
            }
            function timeToString(value) {
                let hours = Math.floor(value / 60);
                if(hours.toString().length === 1) hours = "0" + hours;
                let minutes = value % 60;
                if(minutes.toString().length === 1) minutes = "0" + minutes;
                return hours + ":" + minutes;
            }
            function getUserDetail() {
                const data = new FormData();
                data.append("mobile", $("#add_mobile").val());
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('admin/appointment/ajax/getUser') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        ladda_btn.stop();
                        $('.ladda-button .ladda-label').hide().html("ثبت نهایی").fadeIn();
                        $('#prev_step').fadeIn();
                        $("#add_step_1").hide();
                        $("#add_step_2").slideDown();
                        $("#add_firstname").val(data["first_name"]);
                        $("#add_age").val(data["age"]);
                        $("#add_service").val(data["service"]);
                        $("#add_address").val(data["address"]);
                        $("#acquainted").val(data["acquainted"]);
                        $("#appointment_first_type").val(data["appointment_first_type"]);
                        $("#add_lastname").val(data["last_name"]);
                        $("#gender").val(data["gender"]);
                        $("#user_id").val(data["user_id"]);
                        if (data['status']) {
                            $("#user_details").text("کاربر شماره " + data["user_id"]);
                        } else {
                            $('#user_details_header').click();
                            $("#user_details").text("کاربر جدید");
                        }
                        if (!isTimeSet) {
                            $('#add_nobat_time').click();
                        }
                        $("#add_display_date").text($("#display_data").text());
                        stepAddAppointment = 1;
                    },
                }).fail(function () {
                    ladda_btn.stop();
                    new PNotify({
                        title: 'خطا!',
                        text: "خطا در سرور. لطفا مجددا تلاش نمایید.",
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
                });
            }

            @if($data['appointment']->time_for_visit[$appointmentNameSpace::JSON_STATUS])
            $('.related_part').on('change',function(){
                const typeIsCheckBox = "{{ $data['appointment']->time_for_visit[$appointmentNameSpace::JSON_SELECT_TYPE] == "multi" ? "true" : "false" }}";
                if(typeIsCheckBox === "true"){
                    let checkedCount = $('.related_part:checked:enabled').length;
                    totalTime = checkedCount <= 1 && $(this).is(":checked")
                        ? $('#start_time').val() :
                        $('#end_time').val();
                    if (totalTime !== undefined && totalTime.length >= 1) {
                        totalTime = getTimeInDay(totalTime);
                        if ($(this).is(":checked")) {
                            totalTime = totalTime + parseInt($(this).val());
                        } else {
                            totalTime = totalTime - parseInt($(this).val());
                        }
                        totalTime = timeToString(totalTime);
                        $("#end_time").val(totalTime);

                        if (checkedCount === 0) {
                            $("#end_time").val(endTimeValue);
                        }
                    }
                } else{
                    let endTime = $("#end_time").attr("data-time");
                    if(endTime !== undefined){
                        endTime = getTimeInDay(endTime);
                        endTime = endTime + parseInt($(this).val());
                        $("#end_time").val(timeToString(endTime));
                    }
                }
            });
            @endif
        });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function () {


            $('.public_parttime input').on('change', function () {
                $('#time_from').change();
            });

            $('#part').on('change', function () {
                var part_id_temp = $(this).val();
                $('.part_time').hide();
                $('.public_parttime').hide();
                $('.parttime_content_' + part_id_temp).show();
                $('.part_time').fadeIn();
                $('#time_from').change();

            });

            $('#send_sms_status').on('change', function () {
                var div_content = $('#sms_content_div');
                if (div_content.hasClass('disabled')) {
                    div_content.removeClass('disabled');
                    div_content.slideDown();
                } else {
                    div_content.addClass('disabled');
                    div_content.slideUp();
                }
            });

            $('input[name="sms_text"]').on('change', function () {
                var div_content_text = $('#sms_text_content');
                if (div_content_text.hasClass('disabled')) {
                    div_content_text.removeClass('disabled');
                    div_content_text.slideDown();
                } else {
                    div_content_text.addClass('disabled');
                    div_content_text.slideUp();
                }
            });

        });
    </script>
    <script type="text/javascript">

        let selp_day = parseInt("{{ (intval(request()->input('day'))==0) ? jdate('d','','','','en') : intval(request()->input('day')) }}");
        let selp_month = parseInt("{{ (intval(request()->input('month'))==0) ? jdate('n','','','','en') : intval(request()->input('month')) }}");
        let selp_year = parseInt("{{ (intval(request()->input('year'))==0) ? jdate('Y','','','','en') : intval(request()->input('year')) }}");


        jQuery(document).ready(function () {
            $('#display_date').on('click', function () {
                $(this).fadeOut();
                $("#complete_data_picker").delay(500).fadeIn();
                return false;
            });

            $('#mobile').mask('99999999999');


            $('#refresh').on('click', function () {
                get_month_Days(0);
            });
            $('body').tooltip({
                selector: '.cal_item'
            });
            $('.part_picker').on('click', function (e) {
                e.preventDefault();
                var li_parent = $(this).parent('li');
                var part_id = li_parent.data('partid');

                if (!li_parent.hasClass('active')) {
                    $('#picker-ul .active a span').remove();
                    li_parent.addClass('active');
                    li_parent.siblings('li').removeClass('active');
                    $('#picker-ul').find('li.active a').prepend('<span class="fa fa-check pr5"></span>');
                    update_doctor_times(part_id);
                }
            });

            $('body').on('click', '.cal_item', function (e) {
                e.preventDefault();
                if (!$(this).hasClass('highlight')) {
                    $(this).siblings('div').removeClass('highlight');
                    $(this).addClass('highlight');
                }
                const day = $(this).data('date');


                $("#const_now_year").val(selp_year);
                $("#const_now_month").val(selp_month);
                $("#const_now_day").val(day);
                $('#display_data').text(pdate);
                display_appointments(day);
            });

            $('body').on('click', '.month_selector', function (e) {
                e.preventDefault();
                if (!$(this).hasClass('active')) {
                    $(this).siblings('li').removeClass('active');
                    $(this).addClass('active');
                    selp_month = $(this).find('a').data('month');
                    get_month_Days(-1);
                    update_exception();
                }
            });

            $('body').on('click', '#nav-prev-day', function (e) {
                e.preventDefault();
                const indexElement = $('.highlight').index('.cal_item');
                if (indexElement === 0) {
                    $('#nav-prev-month').click();
                } else {
                    $('.cal_item')[(indexElement - 1)].click();
                }
            });
            $('body').on('click', '#nav-next-day', function (e) {
                e.preventDefault();
                const activeDays = $('.cal_item').length - 1;
                const indexElement = $('.highlight').index('.cal_item');
                if (activeDays === indexElement) {
                    $('#nav-next-month').click();
                } else {
                    $('.cal_item')[(indexElement + 1)].click();
                }
            });
            $('body').on('click', '#nav-prev-month', function (e) {
                e.preventDefault();
                if (selp_month == 1) {
                    selp_month = 12;
                    selp_year = selp_year - 1;
                } else {
                    selp_month = selp_month - 1;
                }
                var li_month = $('.month_selector a[data-month="' + selp_month + '"]').parent('li');
                li_month.siblings('li').removeClass('active');
                li_month.addClass('active');
                get_month_Days(-1);
                update_exception();
            });
            $('body').on('click', '#nav-next-month', function (e) {
                e.preventDefault();
                if (selp_month === 12) {
                    selp_month = 1;
                    selp_year = selp_year + 1;
                } else {
                    selp_month = selp_month + 1;
                }


                const li_month = $('.month_selector a[data-month="' + selp_month + '"]').parent('li');
                li_month.siblings('li').removeClass('active');
                li_month.addClass('active');
                get_month_Days(-1);
                update_exception();
            });
            $('body').on('click', '.add_appoint_dast', function (e) {
                $("#complete_data_picker").hide();
                $('#display_date').show();
                var time = parseInt($(this).data('from'));
                var hr = Math.floor(time / 60);
                var min = (time - (hr * 60));
                var day = parseInt($(this).data('day'));
                var month = parseInt($(this).data('month'));
                var year = parseInt($(this).data('year'));
                $("#time_from").persianDatepicker("setDate", [year, month, day, hr, min]);
                $("#complete_data_picker").persianDatepicker("setDate", [year, month, day, hr, min])
            });
            $('body').on('change', '#time_from', function (e) {
                if ($('#part').val() != undefined && $('#part option:selected').data('visittime') != '0') {
                    var time_for_visit = parseInt($('#part option:selected').data('visittime'));
                    if ($('.public_parttime:visible').find('input:checked').length >= 1) {
                        time_for_visit = 0;
                        $('.public_parttime:visible').find('input:checked').each(function () {
                            time_for_visit = time_for_visit + $(this).data('parttime');
                        });
                    }
                    var time2 = $('#time_from').val();
                    time2 = time2.split(':');
                    time2 = parseInt(time2[0]) * 60 + parseInt(time2[1]);
                    time2 = time_for_visit + time2;
                    var hr2 = Math.floor(time2 / 60);
                    var min2 = (time2 - (hr2 * 60));
                    if ($("#time_to").val() == undefined || $("#time_to").val().length == 0) {
                        $("#time_to").val(hr2 + ":" + min2);
                    }
                    $("#time_to").persianDatepicker("setDate", [1395, 05, 05, hr2, min2]);
                }
            });
            $('body').on('click', '.choice_user', function (e) {
                $('#firstname').val($(this).data('fname'));
                $('#lastname').val($(this).data('lname'));
                $('#mobile').val($(this).data('mobile'));
                $('#email').val($(this).data('email'));
                $('#sex').val($(this).data('gender'));
                $.magnificPopup.close();
            });

            function display_appointments(day_selected) {


                var loader = $('#table_loader');
                loader.show();
                data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("year", "" + selp_year);
                data.append("month", "" + selp_month);
                data.append("day", "" + day_selected);
                @if( Request::get('code') && Request::get('code') > 0)
                data.append("changeCode", '' + '{{Request::get('code')}}');
                @endif
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('admin/appointment/ajax/getappointment') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        loader.hide();
                        $('#appointment_content').hide();
                        $('#appointment_content').html(data);
                        $('#appointment_content').fadeIn();


                    },
                }).fail(function () {
                    alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                    loader.hide();
                });
            }

            function update_exception() {
                var month_temp = selp_month;
                var year_temp = selp_year;
                data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("year", '' + year_temp);
                data.append("month", '' + month_temp);
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('admin/appointment/ajax/getajaxexception') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        $('#external-events').html(data);
                        $('body').tooltip({
                            selector: '.fc-event-success'
                        });
                    },
                }).fail(function () {
                    alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                });
            }

            function get_month_Days(activeItem) {
                const calenderTimeLineObject = $('.calender_timeline');
                calenderTimeLineObject.find('.cal_item ').remove();
                calenderTimeLineObject.find('.cal_item_deactivate ').remove();
                calenderTimeLineObject.slideToggle('fast');

                const data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("year", '' + selp_year);
                data.append("month", '' + selp_month);
                @if( Request::get('code') && Request::get('code') > 0)
                data.append("changeCode", '' + '{{Request::get('code')}}');
                @endif
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    dataType: 'json',
                    url: "{{ url('admin/appointment/ajax/loadMonth') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        data["days"].forEach(function (item) {
                            $('<div class="item_day ' + item['class'] + '" data-date="' + item['day_number'] + '" data-toggle="tooltip" data-original-title="' + item['day_name'] + '"><div class="top"></div><a href="#" class="number">' + item['day_number'] + '</a></div>').insertBefore($('#nav-next-day'));
                        });
                        calenderTimeLineObject.slideToggle('slow');

                        if (activeItem > 0) {
                            $('.item_day:eq('+(--activeItem)+')').click();
                        }
                        else if (activeItem === -1) {
                            $('.cal_item').first().click();
                        } else {
                            window.formatPersian = false;
                            let daySetStatus = false;
                            const today = new persianDate().format("D");
                            const allItems = $('.item_day').length;
                            const indexItem = $('.item_day[data-date="' + today + '"]').index('.item_day');
                            for (var i = indexItem; i < allItems; i++) {
                                const tmpItem = $($('.item_day').get(i));

                                if (tmpItem.hasClass('cal_item')) {
                                    daySetStatus = true;
                                    tmpItem.click();
                                    break;
                                }
                            }

                            if (!daySetStatus) {
                                $('#nav-next-month').click();
                            }
                        }
                    },
                }).fail(function () {
                    alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                });
            }

            function update_doctor_times($part_id) {
                const loader = $('#get_time_loader');
                const table_data = $('#doctor_time_table');
                loader.fadeIn();
                let data = new FormData();
                data.append("doctor_id", '{{ $data['doctor_id'] }}');
                data.append("part_id", '{{ $data['part']->id }}');
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('admin/appointment/ajax/gettimes') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function (data) {
                        table_data.html(data);
                        loader.hide();
                    },
                }).fail(function () {
                    alert('درحال حاضر امکان اتصال به سرور وجود ندارد . لطفا مجددا تلاش نمایید.');
                    loader.hide();
                });
            }

            @if(intval(request()->input('month')) != 0)
            get_month_Days(selp_day);
            // update_day();
            @else
            get_month_Days(0);
            @endif
            //windows load
            update_doctor_times(0);

            update_exception();
        });
    </script>
@endpush
