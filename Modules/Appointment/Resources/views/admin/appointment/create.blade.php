@extends('admin.layout.layout')
@section('title',$title ?? 'تنظیم وقت')
@section('content')
@inject('appointmentNameSpace', 'Modules\Appointment\Entities\Appointment')
        <!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right">
        <ul class="nav nav-list nav-list-topbar pull-right yekan">
            <li class="active">
                <a href="#">مدیریت نوبت‌دهی</a>
            </li>
        </ul>
    </div>
</header>
@if(Session::has('successful'))
    <div class="alert alert-success alert-dismissable rtl yekan">
        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
        <i class="fa fa-check pr10"></i>
        {{ Session::get('successful') }}
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
<section id="content" class="table-layout animated fadeIn rtl yekan">
    <form method="post" class="form-horizontal" action="{{ route('admin.appointment.store') }}" id="store_all_data">
        @csrf
        <input type="hidden" name="user_id" value="{{ $data['admin_edited']->id }}">
        <input type="hidden" name="appointment_part_id" value="{{ $part->id }}">
        <!-- begin: .tray-center -->
        <div class="tray tray-center pv40 ph30 va-t posr animated-delay animated-long"
             data-animate='["800","fadeIn"]'>

            <h2 class="lh30 mtn text-center">
                برنامه حضور <b class="text-primary">{{ $data['admin_edited']->full_name }}</b> در
                <b class="text-success">{{$part->title}}</b>
            </h2>
            <p class="lead mb40 text-center">لطفا تمام موارد را به دقت کامل نمایید.</p>

            <!-- begin: .admin-form -->
            <div class="admin-form mw1100 center-block rtl yekan">

                <div id="p1" class="panel heading-border">
                    <div class="panel-body">

                        <div class="section row">

                            <!-- Data Pickers - Field Versions -->
                            <div class="col-md-12 text-center">

                                <!-- TimePickers -->
                                <div id="widget1" class="section-divider mb30">
                                    <span>تنظیمات کلی زمان</span>
                                </div>
                                <label class="col-md-3 pull-right text-right" style="margin-top: 16px;">زمان مورد
                                    نیاز ویزیت برای هر بیمار</label>
                                <div class="col-md-9 pull-right ph30">
                                    <div class="form-group">
                                        <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i>
                                                </span>
                                            <input name="time_for_visit" type="text" placeholder="00:00"
                                                   id="time_for_visit" class="form-control time just_minutes"
                                                   value="{{ old('time_for_visit',($data['edit'][$appointmentNameSpace::JSON_PUBLIC_TIME] ?? "00:00")) }}"/>
                                        </div>
                                            <span class="help-block mt5 pull-right"><i class="fa fa-bell"></i>
                                                مدت زمان مورد نیاز برای ویزیت کاربر
                                            </span>
                                    </div>
                                </div>
                                <label class="col-md-3 pull-right text-right" style="margin-top: 20px;">امکان دریافت
                                    نوبت توسط کاربران</label>
                                <div class="col-md-9 pull-right ph30">
                                    <div class="form-group">
                                        <div class="input-group">

                                            <input name="day_before" style="direction: ltr" type="text"
                                                   placeholder="روز قبل" id="day_before" class="form-control money"
                                                   value="{{ old('day_before',$data['edit'][$appointmentNameSpace::JSON_DAY_BEFORE] ?? 0) }}"/><span
                                                    class="input-group-addon"
                                                    style="border-right: none;border-left: 1px solid #dddddd">روز
                                                </span>
                                        </div>
                                            <span class="help-block mt5 pull-right"><i class="fa fa-bell"></i>
                                            در این قسمت می‌توانید تنظیم کنید تا چند روز قبل از حضور شما در مطب امکان نوبت‌گیری برای کاربران سایت فعال باشد.
                                            </span>
                                    </div>
                                </div>
                                <label class="col-md-3 pull-right text-right" style="margin-top: 20px;">حداکثر زمان
                                    دریافت نوبت </label>
                                <div class="col-md-9 pull-right ph30">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="day_after" style="direction: ltr" type="text"
                                                   placeholder="روز بعد" id="day_after" class="form-control money"
                                                   value="{{ old('day_after',$data['edit'][$appointmentNameSpace::JSON_DAY_AFTER] ?? 90) }}">
                                                <span class="input-group-addon"
                                                      style="border-right: none;border-left: 1px solid #dddddd">روز
                                                </span>
                                        </div>
                                            <span class="help-block mt5 pull-right"><i class="fa fa-bell"></i>
                                                کاربر حداکثر برای چند روز بعد بتواند نوبت دریافت کند.
                                            </span>
                                    </div>
                                </div>


                                <!-- end: .well -->
                                <div class="checkbox-custom fill checkbox-system mb5 pull-right">
                                    <input name="interference" value="1" type="checkbox" id="interference"
                                           @if(isset($data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) && $data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) CHECKED @endif>
                                    <label for="interference">امکان نوبت دهی به صورت تداخلی</label>
                                </div>
                                <div class="col-md-12 @unless(isset($data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) && $data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) disabled @endunless"
                                     id="tadakholi_parent"
                                     @unless(isset($data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) && $data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) style="display: none" @endunless>
                                    <hr class="short">
                                    <div class="alert alert-default alert-dismissable mb30 text-right">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            ×
                                        </button>
                                        <h3 class="mt5">راهنما</h3>
                                        <p>
                                            در صورتی که چند بخش با یک زمان برای پزشک تعریف شده باشد این قسمت را
                                            تکمیل کنید .
                                            شما میتوانید در این قسمت چندین بخش رابه یکدیگر متصل کنید و در صورتی که
                                            در یک تاریخ و زمان مشخص برای هر کدام از بخشهای همین پزشک نوبت دریافت شد،
                                            در همان تاریخ و زمان در تمامی بخش هایی که مربوط به همین پزشک است امکان
                                            دریافت نوبت غیر فعال میشود.
                                        </p>
                                        <br>
                                    </div>
                                    <label for="picker_part_tadakholi" class="col-md-2 control-label pull-right">تداخل
                                        با بخش های</label>
                                    <div class="col-md-10 pull-right text-right">
                                        <select id="picker_part_tadakholi" multiple="multiple"
                                                name="interference_parts[]">
                                            <optgroup label="همه بخش ها">
                                                <option value="all">همه بخش ها</option>
                                            </optgroup>
                                            <optgroup label="انتخاب بخش">
                                                @foreach($data['admin_edited']->appointmentParts as $part_temp)
                                                    @if($part_temp->id == $part->id)
                                                        @continue
                                                    @endif
                                                    <option class="other_part_selection"
                                                            value="{{ $part_temp->id }}">{{ $part_temp->title }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- end: .col-md-8 -->

                        </div>
                        <!-- end .section row section -->
                        <!-- end .form-body section -->

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                                <span class="panel-title pull-right">
                                    <span class="glyphicon glyphicon-cog hidden"></span>کنسل کردن نوبت توسط کاربر</span>
                            <div class="widget-menu pull-right mr10 mt10 pull-left">
                                <div class="switch switch-success switch-inline">
                                    <input id="cancel_by_user"
                                           @if($old_data!=null && isset($old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER]) && $old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER] ?? false)
                                           CHECKED
                                           @endif
                                           type="checkbox" value="1" name="cancel_by_user">
                                    <label for="cancel_by_user"></label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body @if( !isset($old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER]) || $old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER] == 0) disabled @endif"
                             id="cancel_by_user_content"
                             @if(!$old_data || !isset($old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER]) || $old_data->setting[$appointmentNameSpace::JSON_CANCEL_BY_USER] == 0) style="display: none"@endif>


                            <div class="form-group" >
                                <label for="pay_type" class="col-md-4 pull-right control-label"
                                       style="margin-top: 10px">کاربر تا چند روز قبل از نوبت خود بتواند نوبت را کنسل کند؟</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input name="day_left_to_cancel"
                                               style="direction: ltr" type="number"
                                               placeholder="روز مانده به نوبت بیمار"
                                               class="form-control user_max"
                                               value="{{(isset($old_data->setting[$appointmentNameSpace::JSON_DAY_LEFT_TO_CANCEL]) ? $old_data->setting[$appointmentNameSpace::JSON_DAY_LEFT_TO_CANCEL] : 0)}}">
                                                        <span class="input-group-addon"
                                                              style="border-right: none;border-left: 1px solid #dddddd">روز قبل از رسیدن نوبت
                                                </span>
                                    </div>
                                </div>

                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">

                    <div class="panel">
                        <div class="panel-heading">
                                <span class="panel-title pull-right">
                                    <span class="glyphicon glyphicon-cog hidden"></span>زمان بندی بخش ها</span>
                            <div class="widget-menu pull-right mr10 mt10 pull-left">
                                <div class="switch switch-purple2 switch-inline">
                                    <input id="parttime_status" type="checkbox" value="1" name="parttime_status"
                                           @if($old_data->time_for_visit[$appointmentNameSpace::JSON_STATUS] ?? false) CHECKED @endif>
                                    <label for="parttime_status"></label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body @unless($old_data->time_for_visit[$appointmentNameSpace::JSON_STATUS] ?? false) disabled @endunless"
                             id="parttime_content"
                             @unless($old_data->time_for_visit[$appointmentNameSpace::JSON_STATUS] ?? false) style="display: none" @endunless>
                            <div class="form-group">
                                <label class="col-md-2 control-label pull-right">نوع انتخاب :</label>
                                <div class="col-md-10 pull-right" style="padding-top: 10px;">
                                    <div class="col-md-2 pull-right">
                                        <div class="radio-custom radio-success mb5">
                                            <input type="radio" id="parttime_multiselect1"
                                                   name="parttime_multiselect" value="multi"
                                                   @if($old_data != null && $old_data->time_for_visit[$appointmentNameSpace::JSON_STATUS] && $old_data->time_for_visit[$appointmentNameSpace::JSON_SELECT_TYPE] == "multi") CHECKED @endif>
                                            <label for="parttime_multiselect1">چند انتخاب</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pull-right">
                                        <div class="radio-custom radio-info mb5">
                                            <input type="radio" id="parttime_multiselect2"
                                                   name="parttime_multiselect" value="one"
                                                   @if($old_data!=null && $old_data->time_for_visit[$appointmentNameSpace::JSON_STATUS] && $old_data->time_for_visit[$appointmentNameSpace::JSON_SELECT_TYPE] == "one") CHECKED @endif>
                                            <label for="parttime_multiselect2">یک انتخاب</label>
                                        </div>
                                    </div>
                                    <div class="pull-left">
                                        <i class="fa fa-volume-up text-info change_vul"></i>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <hr class="">
                                <div class="" id="parttime_time_content">
                                    <div class="form-group">
                                        <label for="inputStandard" class="col-lg-1 control-label pull-right">بخش
                                            :</label>
                                        <div class="col-lg-5 pull-right">
                                            <input class="form-control" placeholder="عنوان بخش" type="text"
                                                   name="add_time_name[]"
                                                   value="@if(isset($old_data->time_for_visit[$appointmentNameSpace::JSON_TIME])) {{ $old_data->time_for_visit[$appointmentNameSpace::JSON_TIME][0][$appointmentNameSpace::JSON_TITLE] }} @endif">
                                        </div>
                                        <div class="col-lg-5 pull-right">
                                            <input class="form-control time" id="part_time_0"
                                                   value="@if(isset($old_data->time_for_visit[$appointmentNameSpace::JSON_TIME])) {{  $data['edit']['part_time'][0][$appointmentNameSpace::JSON_TIME] }} @endif"
                                                   placeholder="زمان مورد نیاز" type="text" name="add_time_time[]">
                                        </div>
                                        <div class="col-lg-1">
                                            <i class="fa fa-plus-square text-success icon_add icon_click_sensetive"></i>
                                        </div>
                                    </div>
                                    @if(isset($old_data->time_for_visit[$appointmentNameSpace::JSON_TIME]))
                                        @for($i=1;$i<sizeof($old_data->time_for_visit[$appointmentNameSpace::JSON_TIME]);$i++)
                                            <div class="form-group add_item">
                                                <label for="inputStandard"
                                                       class="col-lg-1 control-label pull-right">بخش
                                                    :</label>
                                                <div class="col-lg-5 pull-right">
                                                    <input class="form-control"
                                                           value="{{ $old_data->time_for_visit[$appointmentNameSpace::JSON_TIME][$i][$appointmentNameSpace::JSON_TITLE] }}"
                                                           placeholder="عنوان بخش" type="text"
                                                           name="add_time_name[]"
                                                    >
                                                </div>
                                                <div class="col-lg-5 pull-right">
                                                    <input class="form-control time" id="part_time_{{ $i }}"
                                                           placeholder="زمان مورد نیاز" type="text"
                                                           value="{{ $data['edit']['part_time'][$i][$appointmentNameSpace::JSON_TIME] }}"
                                                           name="add_time_time[]">
                                                </div>
                                                <div class="col-lg-1">
                                                    <i class="fa fa-minus-square text-danger icon_remove icon_click_sensetive"></i>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                                <span class="panel-title pull-right">
                                    <span class="glyphicon glyphicon-cog hidden"></span>پرداخت آنلاین</span>
                            <div class="widget-menu pull-right mr10 mt10 pull-left">
                                <div class="switch switch-success switch-inline">
                                    <input id="online_pay_status"
                                           @if($old_data!=null && $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_STATUS])
                                           CHECKED
                                           @endif
                                           type="checkbox" value="1" name="online_pay_status">
                                    <label for="online_pay_status"></label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body @unless($old_data!=null && $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_STATUS]) disabled @endunless"
                             id="online_pay_content"
                             @unless($old_data!=null && $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_STATUS]) style="display: none"@endunless>
                            <div class="form-group">
                                <label for="" class="col-md-3 pull-right control-label" style="margin-top: 10px">وضعیت
                                    در صورت عدم پرداخت</label>
                                <div class="section col-md-9">
                                    <label class="field select">
                                        <select id="unpaid_appointment_status" name="unpaid_appointment_status">
                                            <option value="1">نوبت ثبت و فعال شود</option>
                                            <option value="2">نوبت ثبت و غیرفعال باشد</option>
                                            <option value="3">نوبت ثبت نشود</option>
                                        </select>
                                        <i class="arrow"></i>
                                    </label>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="form-group">
                                <label for="pay_type" class="col-md-3 pull-right control-label"
                                       style="margin-top: 10px">نوع پرداخت</label>
                                <div class="section col-md-9">
                                    <label class="field select">
                                        <select id="pay_type" name="pay_type">
                                            <option value="1">تمام مبلغ</option>
                                            <option value="2">بیعانه</option>
                                        </select>
                                        <i class="arrow"></i>
                                    </label>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="form-group" style="display: none" id="pay_percent">
                                <label for="pay_type" class="col-md-3 pull-right control-label"
                                       style="margin-top: 10px">درصد پرداخت بیعانه</label>
                                <div class="col-md-9">
                                    <input value="{{ $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_PAY_TYPE_PERCENT] ?? 0 }}"
                                           style="direction: ltr" name="online_pay_type_percent" type="text"
                                           id="online_pay_type_percent"
                                           placeholder="درصد" class="form-control money" id="">
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="tab-block mb25">
                        <ul class="nav nav-tabs nav-tabs-right" style="padding-right: 0px;">
                            @for($i=1;$i <= 7;$i++)
                                <li @if($i == 1) class="active" @endif>
                                    <a href="#tab1_{{ $i }}" data-toggle="tab">{{ $dayOfWeek[$i]['name'] }}</a>
                                </li>
                            @endfor
                        </ul>
                        <div class="tab-content rtl yekan">
                            @for($i=1;$i <= 7;$i++)
                                    <!-- {{ $dayOfWeek[$i]['id'] }} -->
                            <div id="tab1_{{ $i }}" class="tab-pane tab_day_of_weeks @if($i == 1) active @endif">
                                <div class="section-divider mb30" id="widget1">
                                    <span>{{ $dayOfWeek[$i]['name'] }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label pull-right">حضور در مطب</label>
                                    <div class="col-md-9 pull-right">
                                        <div class="switch switch-info switch-inline">
                                            <input name="active[{{ $dayOfWeek[$i]['id'] }}]"
                                                   class="activer_swit" id="exampleCheckboxSwitch1_{{ $i }}"
                                                   type="checkbox" checked>
                                            <label for="exampleCheckboxSwitch1_{{ $i }}"></label>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-md-3 pull-right control-label text-right">نوع
                                        ویزیت</label>
                                    <div class="col-md-9 pull-right wraper_type_of_visit">
                                        @foreach($appointmentNameSpace::$VISIT_TYPE as $key => $value)
                                            <div class="checkbox-custom fill checkbox-primary mb5">
                                                <input type="checkbox" id="visit_type_{{ $key }}_{{ $i }}"
                                                       class="type_of_visit"
                                                       value="{{ $key }}"
                                                       @if(($old_data == null && $loop->first) || ($old_data != null && isset($old_data->content[$dayOfWeek[$i]['id']],$old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TYPE]) && in_array($key,$old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TYPE]))) CHECKED
                                                       @endif name="type[{{ $dayOfWeek[$i]['id'] }}][]">
                                                <label for="visit_type_{{ $key }}_{{ $i }}">{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="form-group" style="display: none">
                                    <label for="" class="col-md-3 pull-right control-label">نوع قرارداد با
                                        پزشک</label>
                                    <div class="section col-md-9">
                                        <label class="field select">
                                            <select id="country" name="contract[{{ $dayOfWeek[$i]['id'] }}]">
                                                @foreach($appointmentNameSpace::$AGREEMENT_TYPE as $key => $value)
                                                    <option value="{{ $key }}" SELECTED>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <i class="arrow"></i>
                                        </label>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3  pull-right control-label" for="maskedTime">ساعت حضور
                                        صبح</label>
                                    <div class="col-md-9 pull-right">
                                        <div class="input-group col-md-5 pull-right">
                                                <span class="input-group-addon cursor">از
                                                </span>
                                            <input name="time[{{ $dayOfWeek[$i]['id'] }}][am][from]"
                                                   type="text" placeholder="00:00" autocomplete="off"
                                                   @if($old_data != null && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_STATUS])
                                                   value="{{ $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TIME][$appointmentNameSpace::JSON_AM][$appointmentNameSpace::JSON_FROM_TIME] ?? "00:00" }}"
                                                   @endif
                                                   maxlength="10" class="form-control time am_time_from">
                                        </div>
                                        <div class="input-group col-md-5 pull-right"
                                             style="margin-right: 10px;">
                                                                <span class="input-group-addon cursor">تا
                                                </span>
                                            <input name="time[{{ $dayOfWeek[$i]['id'] }}][am][to]"
                                                   type="text" placeholder="00:00" autocomplete="off"
                                                   @if($old_data != null && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_STATUS])
                                                   value="{{ $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TIME][$appointmentNameSpace::JSON_AM][$appointmentNameSpace::JSON_TO_TIME] ?? "00:00" }}"
                                                   @endif
                                                   maxlength="10" class="form-control time am_time_to">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3  pull-right control-label" for="maskedTime">ساعت حضور
                                        بعدظهر</label>
                                    <div class="col-md-9 pull-right">
                                        <div class="input-group col-md-5 pull-right">
                                                <span class="input-group-addon cursor">از
                                                </span>
                                            <input type="text"
                                                   name="time[{{ $dayOfWeek[$i]['id'] }}][pm][from]"
                                                   @if($old_data != null && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_STATUS])
                                                   value="{{ $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TIME][$appointmentNameSpace::JSON_PM][$appointmentNameSpace::JSON_FROM_TIME] ?? "00:00" }}"
                                                   @endif
                                                   placeholder="00:00" autocomplete="off" maxlength="10"
                                                   class="form-control time pm_time_from">
                                        </div>
                                        <div class="input-group col-md-5 pull-right"
                                             style="margin-right: 10px;">
                                                                <span class="input-group-addon cursor">تا
                                                </span>
                                            <input type="text"
                                                   name="time[{{ $dayOfWeek[$i]['id'] }}][pm][to]"
                                                   @if($old_data != null && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_STATUS])
                                                   value="{{ $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_TIME][$appointmentNameSpace::JSON_PM][$appointmentNameSpace::JSON_TO_TIME] ?? "00:00" }}"
                                                   @endif
                                                   placeholder="00:00" autocomplete="off" maxlength="10"
                                                   class="form-control time pm_time_to">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 pull-right text-right" style="margin-top: 20px;">حداکثر
                                        نوبت قابل دریافت توسط کاربران </label>
                                    <div class="col-md-9 pull-right ph30">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input name="user_max[{{ $dayOfWeek[$i]['id'] }}]"
                                                       style="direction: ltr" type="text"
                                                       placeholder="تعداد نوبت"
                                                       class="form-control user_max"
                                                       value="{{ old("user_max[".$dayOfWeek[$i]['id']."]",$old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_USER_MAX] ?? "") }}">
                                                        <span class="input-group-addon"
                                                              style="border-right: none;border-left: 1px solid #dddddd">نوبت
                                                </span>
                                                <div class="col-md-9 pull-right">
                                                    <div class="switch switch-info switch-inline">
                                                        <input name="USER_MAX_TYPE[{{ $dayOfWeek[$i]['id'] }}]"
                                                               class="" id="user_max_type_{{ $i }}" value="1"
                                                               @if($old_data!=null && isset($old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_USER_MAX_TYPE]) && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_USER_MAX_TYPE]  )
                                                                CHECKED
                                                               @endif
                                                               type="checkbox">
                                                        <label for="user_max_type_{{ $i }}"></label>
                                                        با سایر نوبت های همین روز پزشک محاسبه شود
                                                    </div>
                                                </div>
                                            </div>
                                                    <span class="help-block mt5 pull-right"><i class="fa fa-bell"></i>
                                                حداکثر نوبت قابل دریافت توسط کاربران
                                            </span>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 pull-right text-right" style="margin-top: 20px;">حداکثر
                                        نوبت قابل ثبت توسط منشی </label>
                                    <div class="col-md-9 pull-right ph30">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input name="clerk_max[{{ $dayOfWeek[$i]['id'] }}]"
                                                       style="direction: ltr" type="text"
                                                       placeholder="تعداد نوبت"
                                                       class="form-control clerk_max"
                                                       value="{{ old("clerk_max[".$dayOfWeek[$i]['id']."]",$old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_CLERK_MAX] ?? "") }}"
                                                >
                                                        <span class="input-group-addon"
                                                              style="border-right: none;border-left: 1px solid #dddddd">نوبت
                                                </span>

                                                <div class="col-md-9 pull-right">
                                                    <div class="switch switch-info switch-inline">
                                                        <input name="CLERK_MAX_TYPE[{{ $dayOfWeek[$i]['id'] }}]"
                                                               @if($old_data!=null && isset($old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_CLERK_MAX_TYPE]) && $old_data->content[$dayOfWeek[$i]['id']][$appointmentNameSpace::JSON_CLERK_MAX_TYPE]  )
                                                               CHECKED
                                                               @endif
                                                               class="" id="clerk_max_{{ $i }}"
                                                               value="1"
                                                               type="checkbox">
                                                        <label for="clerk_max_{{ $i }}"></label>
                                                        با سایر نوبت های همین روز پزشک محاسبه شود
                                                    </div>
                                                </div>
                                            </div>
                                                    <span class="help-block mt5 pull-right"><i class="fa fa-bell"></i>
                                                        حداکثر نوبت قابل ثبت توسط منشی
                                            </span>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                            <!-- end -->
                            @endfor
                        </div>
                    </div>
                </div>


                <!-- user roles -->
                <div class="col-md-12">

                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">هزینه</span>
                        </div>
                        <div class="panel-body">
                            <div class="tab-block mb25">
                                <ul class="nav nav-tabs nav-tabs-right" style="padding-right: 0px;">
                                    @foreach($appointmentNameSpace::$AGREEMENT_TYPE as $key => $value)
                                        <li @if($loop->first) class="active" @endif>
                                            <a href="#tab2_1_{{ $key }}" data-toggle="tab">{{ $value }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content rtl yekan">
                                    @foreach($appointmentNameSpace::$AGREEMENT_TYPE as $key => $value)
                                        <div class="tab-pane @if($loop->first) active @endif"
                                             id="tab2_1_{{ $key }}">
                                            <div class="tab-block mb25">
                                                <ul class="nav tabs-right">
                                                    @foreach($all_role as $role)
                                                        <li @if($loop->first) class="active" @endif>
                                                            <a data-toggle="tab"
                                                               href="#{{ $key }}_tab2_1_{{ $role->id }}"><i
                                                                        class="fa fa-user text-purple pr5"></i> {{ $role->display_name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <div class="tab-content">
                                                    @foreach($all_role as $role)
                                                        <div class="tab-pane @if($loop->first) active @endif "
                                                             id="{{ $key }}_tab2_1_{{ $role->id }}">
                                                            <h4 class="text-center">هزینه برای گروه
                                                                کاربری {{ $role->display_name }}</h4>
                                                            <hr class="short alt">
                                                            <div class="form-group">
                                                                <div class="col-lg-9">
                                                                    <input @if($old_data != null) value="{{ $old_data->cost[$key][$appointmentNameSpace::JSON_COST][$role->id] ?? 0 }}"
                                                                           @endif style="direction: ltr"
                                                                           name="cost[{{ $key }}][{{ $role->id }}]"
                                                                           type="text" placeholder="هزینه"
                                                                           class="form-control money"
                                                                           id="con_{{ $key }}_1_{{ $role->id }}">
                                                                        <span class="help-block mt5"><i
                                                                                    class="fa fa-bell"></i> برای رایگان شدن عدد 0 را وارد نمایید.هزینه به تومان می باشد.</span>
                                                                </div>
                                                                <label class="col-lg-3 control-label"
                                                                       for="con_{{ $key }}_1_{{ $role->id }}">هزینه
                                                                    ویزیت</label>
                                                                <div style="clear: both;"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-lg-9">
                                                                    <input style="direction: ltr"
                                                                           @if($old_data != null) value="{{ $old_data->cost[$key][$appointmentNameSpace::JSON_PERCENT][$role->id] ?? 0 }}"
                                                                           @endif
                                                                           name="percent[{{ $key }}][{{ $role->id }}]"
                                                                           type="text" placeholder="درصد پزشک"
                                                                           class="form-control"
                                                                           id="per_{{ $key }}_1_{{ $role->id }}">
                                                                </div>
                                                                <label class="col-lg-3 control-label"
                                                                       for="per_{{ $key }}_1_{{ $role->id }}">درصد
                                                                    پزشک</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="short alt">
                    <div class="section row mbn">
                        <div class="col-sm-4">
                            <p class="text-left yekan">
                                <button class="btn btn-primary yekan" type="submit">ثبت و ذخیره</button>
                            </p>
                        </div>
                        <div class="col-sm-8">

                        </div>
                    </div>
                </div>
            </div>
            <!-- end: .admin-form -->
        </div>
        <!-- end: .tray-center -->
    </form>
</section>
<audio id="remove_sound" style="display: none">
    <source src="{{ asset('sound/remove_sound.mp3') }}">
    Your browser isn't invited for super fun audio time.
</audio>
<audio id="add_sound" style="display: none">
    <source src="{{ asset('sound/add_sound.mp3') }}">
    Your browser isn't invited for super fun audio time.
</audio>

@endsection
@push('scripts')
<style>

    /* demo page styles */
    body {
        min-height: 2300px;
    }

    .affix-pane.affix {
        top: 80px;
    }

    .admin-form .panel.heading-border:before,
    .admin-form .panel .heading-border:before {
        transition: all 0.7s ease;
    }

    .custom-nav-animation li {
        display: none;
    }

    .custom-nav-animation li.animated {
        display: block;
    }

    .time {
        direction: ltr;
    }

    #amount {
        color: #f6931f;
        font-weight: bold;
        background: transparent;
        border: 0;
    }

    .ui-timepicker-rtl {
        font-family: wyekan, Roboto !important;
    }

    div.switch label::after {
        top: 1px;
    }

    .admin-form .checkbox {
        border: none !important;
        background: none !important;
        font-size: 13px !important;
        padding-bottom: 5px;
        width: 100%;

    }

    .admin-form .active .checkbox {
        color: #fff !important;
    }

    .multiselect-group label {
        font-size: 13px !important;
    }

    .multiselect-group {
        text-align: center;
    }

    .icon_click_sensetive {
        font-size: 30px;
        padding-top: 3px;
        cursor: pointer;
    }

    .change_vul {
        font-size: 20px;
        cursor: pointer;
    }

    .checkbox-custom.fill input[type=checkbox]:checked + label:after, .checkbox-custom.fill input[type=radio]:checked + label:after {
        right: 8px;
    }
</style>

<!-- Forms Javascript -->
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery-tcm-month.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery-ui-timepicker.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery-ui-touch-punch.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.spectrum.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.stepper.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>


<script type="text/javascript"
        src="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
<link rel="stylesheet" type="text/css"
      href="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.css') }}">

<script type="text/javascript">
    jQuery(document).ready(function () {
        let sound_status = true;
        $('.change_vul').on('click', function () {
            if ($(this).hasClass('fa-volume-up')) {
                $(this).removeClass('fa-volume-up');
                $(this).removeClass('text-info');
                $(this).addClass('fa-volume-off');
                $(this).addClass('text-danger');
            } else {
                $(this).addClass('fa-volume-up');
                $(this).addClass('text-info');
                $(this).removeClass('fa-volume-off');
                $(this).removeClass('text-danger');
            }
            sound_status = !sound_status;
        });
        $('#parttime_status').on('change', function () {
            var div_content = $('#parttime_content');
            if (div_content.hasClass('disabled')) {
                div_content.slideDown();
                div_content.removeClass('disabled');
            } else {
                div_content.slideUp();
                div_content.addClass('disabled');
            }
        });
        $('#online_pay_status').on('change', function () {
            const div_content = $('#online_pay_content');
            if (div_content.hasClass('disabled')) {
                div_content.slideDown();
                div_content.removeClass('disabled');
            } else {
                div_content.slideUp();
                div_content.addClass('disabled');
            }
        });
        $('#cancel_by_user').on('change', function () {
            const div_content = $('#cancel_by_user_content');
            if (div_content.hasClass('disabled')) {
                div_content.slideDown();
                div_content.removeClass('disabled');
            } else {
                div_content.slideUp();
                div_content.addClass('disabled');
            }
        });
        $('.icon_add').on('click', function () {
            var content = '<div class="form-group add_item">' +
                    '<label for="inputStandard" class="col-lg-1 control-label pull-right">بخش :</label>' +
                    '<div class="col-lg-5 pull-right">' +
                    '<input class="form-control" placeholder="عنوان بخش" type="text" name="add_time_name[]">' +
                    '</div>' +
                    '<div class="col-lg-5 pull-right">' +
                    '<input class="form-control time" placeholder="زمان مورد نیاز" type="text" name="add_time_time[]">' +
                    '</div>' +
                    '<div class="col-lg-1">' +
                    '<i class="fa fa-minus-square text-danger icon_remove icon_click_sensetive"></i>' +
                    '</div>' +
                    '</div>';
            $('#parttime_time_content').append(content);
            if (sound_status) {
                $('#add_sound')[0].play();
            }
            $('.time').mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
        });
        $('body').on('click', '.icon_remove', function () {
            var parent = $(this).parents('.add_item').remove();
            if (sound_status) {
                $('#remove_sound')[0].play();
            }
        });

        $('#pay_type').on('change', function () {
            if ($(this).val() === "1") {
                $("#pay_percent").slideUp();
            } else {
                $("#pay_percent").slideDown();
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.time').mask('99:99').clockpicker({
            donetext: 'تایید',
            autoclose: true,
        });

        $('#interference').on('change', function () {
            const cls = $('#tadakholi_parent');
            if (cls.hasClass('disabled')) {
                cls.removeClass('disabled');
                cls.slideDown();
            } else {
                cls.addClass('disabled');
                cls.slideUp();
                $('#picker_part_tadakholi').multiselect("clearSelection");
            }
        });

        $('#picker_part_tadakholi').multiselect({
            enableFiltering: true,
        });


                @if(isset($data['edit']['interference'][$appointmentNameSpace::JSON_STATUS]) && $data['edit']['interference'][$appointmentNameSpace::JSON_STATUS])
        var data_intefrance = "{{ implode(",",$data['edit']['interference'][$appointmentNameSpace::JSON_ITEMS]) }}";
        $("#picker_part_tadakholi").val(data_intefrance.split(","));
        $("#picker_part_tadakholi").multiselect("refresh");
        @endif


        $('#picker_part_tadakholi').on('change', function () {
            if ($(this).val().indexOf("all") >= 0) {
                $('#picker_part_tadakholi').multiselect("clearSelection");
                $("#picker_part_tadakholi").val('all');
                $("#picker_part_tadakholi").multiselect("refresh");
            }
        });


        @if($old_data!=null && $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_STATUS])
        $('#unpaid_appointment_status').val({{ $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_UNPAID_APPOINTMENT_STATUS] ?? 1 }});
        $('#pay_type').val({{ $old_data->setting[$appointmentNameSpace::JSON_ONLINE_PAY][$appointmentNameSpace::JSON_PAY_TYPE] ?? 1 }}).change();
        @endif
        // Cache some dom elements
        const adminForm = $('.admin-form');
        const options = adminForm.find('.option');
        const switches = adminForm.find('.switch');
        const buttons = adminForm.find('.button');


        const Panel = $('#p1');

        // Form Skin Switcher
        $('#skin-switcher a').on('click', function () {
            const btnData = $(this).data('form-skin');

            $('#skin-switcher a').removeClass('item-active');
            $(this).addClass('item-active')

            adminForm.each(function (i, e) {
                const skins = 'theme-primary theme-info theme-success theme-warning theme-danger theme-alert theme-system theme-dark';
                const panelSkins = 'panel-primary panel-info panel-success panel-warning panel-danger panel-alert panel-system panel-dark'
                $(e).removeClass(skins).addClass('theme-' + btnData);
                Panel.removeClass(panelSkins).addClass('panel-' + btnData);
            });

            $(options).each(function (i, e) {
                if ($(e).hasClass('block')) {
                    $(e).removeClass().addClass('block mt15 option option-' + btnData);
                } else {
                    $(e).removeClass().addClass('option option-' + btnData);
                }
            });


            // var sliders = $('.ui-timepicker-div', adminForm).find('.ui-slider');
            $('body').find('.ui-slider').each(function (i, e) {
                $(e).addClass('slider-primary');
            });

            $(switches).each(function (i, ele) {
                if ($(ele).hasClass('switch-round')) {
                    if ($(ele).hasClass('block')) {
                        $(ele).removeClass().addClass('block mt15 switch switch-round switch-' + btnData);
                    } else {
                        $(ele).removeClass().addClass('switch switch-round switch-' + btnData);
                    }
                } else {
                    if ($(ele).hasClass('block')) {
                        $(ele).removeClass().addClass('block mt15 switch switch-' + btnData);
                    } else {
                        $(ele).removeClass().addClass('switch switch-' + btnData);
                    }
                }

            });
            buttons.removeClass().addClass('button btn-' + btnData);
        });


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

            // var sliders = $('.ui-timepicker-div', adminForm).find('.ui-slider');
            $('body').find('.ui-slider').each(function (i, e) {
                $(e).addClass('slider-primary');
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

        $('.inline-tp').timepicker();


        $('.activer_swit').on('change', function () {
            const parent = $(this).parents('div.tab-pane');
            if (!parent.hasClass('disabled')) {
                parent.addClass('disabled');
                parent.find('input').attr('disabled', 'disabled');
                parent.find('select').attr('disabled', 'disabled');
                parent.find('.checkbox-custom').addClass('checkbox-disabled');
            } else {
                parent.removeClass('disabled');
                parent.find('input').removeAttr('disabled');
                parent.find('select').removeAttr('disabled');
                parent.find('.checkbox-custom').removeClass('checkbox-disabled');
            }
            $(this).removeAttr('disabled', 'enabled');
        });

        $('.money').each(function () {
            var currentVal = $(this).val();
            var testDecimal = testDecimals(currentVal);
            if (testDecimal.length > 1) {
                currentVal = currentVal.slice(0, -1);
            }
            $(this).val(replaceCommas(currentVal));
        })
        $('.money').keyup(function (event) {
            // skip for arrow keys
            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }

            var currentVal = $(this).val();
            var testDecimal = testDecimals(currentVal);
            if (testDecimal.length > 1) {
                console.log("You cannot enter more than one decimal point");
                currentVal = currentVal.slice(0, -1);
            }
            $(this).val(replaceCommas(currentVal));
        });

        @if($old_data!=null)
        <?php
        $counter = 0;
        $day_name = array('sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri');
        ?>
        @foreach($old_data->content as $day)
        @unless($day[$appointmentNameSpace::JSON_STATUS])
        $('input[name="active[<?=$day_name[$counter]?>]"]').removeAttr('checked').change();
        @endunless
        <?php
        $counter++;
        ?>
        @endforeach
        @endif

    });

    function testDecimals(currentVal) {
        let count;
        currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
        return count;
    }

    function replaceCommas(yourNumber) {
        const components = yourNumber.toString().split(".");
        if (components.length === 1)
            components[0] = yourNumber;
        components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " , ");
        if (components.length === 2)
            components[1] = components[1].replace(/\D/g, "");
        return components.join(".");
    }
</script>

<script type="application/javascript">
    $(document).ready(function () {
        @if($old_data == null)
            $('.activer_swit').each(function(){
            $(this).click();
        });
        @endif
        $('#store_all_data').on('submit', function () {
            //start validator
            // ------step1
            if($('#time_for_visit').val() === "00:00"){
                scrollToItem($('#time_for_visit'),function(){
                    $('#time_for_visit').focus()
                });
                displayErrorNotify("زمان مورد نیاز برای هر بیمار را وارد کنید");
                return false;
            }
            // ------step2
            if($('#parttime_status').is(":checked")){
                if($("input[name='parttime_multiselect']:checked").length == 0){
                    scrollToItem($('#parttime_content'),function(){
                        $('#parttime_content').find('div.pull-right').first().fadeOut(300).fadeIn(500);
                    });
                    displayErrorNotify("نوع انتخاب را در بخش‌بندی زمان‌ها انتخاب کنید");
                    return false;
                }
                let everyTitleOk = true;
                $("input[name='add_time_name[]']").each(function(){
                    if($(this).val() === undefined || $(this).val().length == 0){
                        const itemTmp = $(this);
                        scrollToItem($('#parttime_content'),function(){
                            itemTmp.fadeOut(100).fadeIn(300);
                            itemTmp.focus();
                        });
                        displayErrorNotify("وارد کردن عنوان الزامی است");
                        everyTitleOk = false;
                        return false;
                    }
                });
                if(!everyTitleOk) return false;
                let everyTimeOk = true;
                $("input[name='add_time_time[]']").each(function(){
                    if($(this).val() === undefined || $(this).val().length == 0 || $(this).val() === "00:00"){
                        const itemTmp = $(this);
                        scrollToItem($('#parttime_content'),function(){
                            itemTmp.focus();
                        });
                        displayErrorNotify("زمان مورد نیاز به درستی وارد نشده");
                        everyTimeOk = false;
                        return false;
                    }
                });
                if(!everyTimeOk) return false;
            }
            // ------step3
            if ($('#online_pay_status').is(":checked") && $('#pay_type').val() === "2") {
                const inputPercentType = $('#online_pay_type_percent');
                if (inputPercentType.val() === undefined || inputPercentType.val().length === 0 || parseInt(inputPercentType.val().replace(" , ", "")) <= 0 || parseInt(inputPercentType.val().replace(" , ", "")) > 100) {
                    scrollToItem(inputPercentType, function () {
                        inputPercentType.focus();
                        inputPercentType.fadeOut(100).fadeIn(300);
                    });
                    displayErrorNotify("درصد بیعانه به درستی وارد نشده است");
                    return false;
                }
            }
            // ------step4
            let inTimeEveryThingOk = true;
            $('.tab_day_of_weeks').each(function(){
                const tabObject = $(this);
                if(tabObject.find('.activer_swit').first().is(':checked')){
                    if(tabObject.find('.type_of_visit:checked').length == 0){
                        $('a[href="#'  + tabObject.attr('id') + '"]').click();
                        scrollToItem(tabObject, function () {
                            tabObject.find('.wraper_type_of_visit').fadeOut(100).fadeIn(300);
                        });
                        displayErrorNotify("حداقل یک نوع ویزیت را انتخاب کنید");
                        inTimeEveryThingOk = false;
                        return false;
                    }
                    if(tabObject.find('.user_max').first().val().length >= 1 && !isInt(tabObject.find('.user_max').first().val())){
                        $('a[href="#'  + tabObject.attr('id') + '"]').click();
                        scrollToItem(tabObject, function () {
                            tabObject.find('.user_max').first().fadeOut(100).fadeIn(300);
                            tabObject.find('.user_max').first().focus();
                        });
                        displayErrorNotify("حداقل نوبت قابل اخذ توسط کاربر صحیح وارد نشده است");
                        inTimeEveryThingOk = false;
                        return false;
                    }
                    if(tabObject.find('.clerk_max').first().val().length >= 1 && !isInt(tabObject.find('.clerk_max').first().val())){
                        $('a[href="#'  + tabObject.attr('id') + '"]').click();
                        scrollToItem(tabObject, function () {
                            tabObject.find('.clerk_max').first().fadeOut(100).fadeIn(300);
                            tabObject.find('.clerk_max').first().focus();
                        });
                        displayErrorNotify("حداقل نوبت قابل اخذ توسط کاربر صحیح وارد نشده است");
                        inTimeEveryThingOk = false;
                        return false;
                    }
                    let amTimeFrom = getTimeInDay(tabObject.find('.am_time_from').val());
                    let amTimeTo = getTimeInDay(tabObject.find('.am_time_to').val());
                    let pmTimeFrom = getTimeInDay(tabObject.find('.pm_time_from').val());
                    let pmTimeTo = getTimeInDay(tabObject.find('.pm_time_to').val());
                    if(amTimeFrom !== amTimeTo){
                        // if(amTimeFrom > 840 || amTimeTo > 840 || amTimeFrom > amTimeTo){
                        if(amTimeFrom > amTimeTo){
                            $('a[href="#'  + tabObject.attr('id') + '"]').click();
                            scrollToItem(tabObject, function () {
                                tabObject.find('.am_time_from').first().fadeOut(100).fadeIn(300);
                                tabObject.find('.am_time_to').first().fadeOut(100).fadeIn(300);
                            });
                            displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                            inTimeEveryThingOk = false;
                            return false;
                        }
                    }
                    if(pmTimeFrom !== pmTimeTo){
                        // if(pmTimeFrom < 840 || pmTimeTo < 840 || pmTimeFrom > pmTimeTo){
                        if(amTimeTo > pmTimeFrom){
                            $('a[href="#'  + tabObject.attr('id') + '"]').click();
                            scrollToItem(tabObject, function () {
                                tabObject.find('.am_time_from').first().fadeOut(100).fadeIn(300);
                                tabObject.find('.am_time_to').first().fadeOut(100).fadeIn(300);
                            });
                            displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                            inTimeEveryThingOk = false;
                            return false;
                        }
                        if(pmTimeFrom > pmTimeTo){
                            $('a[href="#'  + tabObject.attr('id') + '"]').click();
                            scrollToItem(tabObject, function () {
                                tabObject.find('.pm_time_from').first().fadeOut(100).fadeIn(300);
                                tabObject.find('.pm_time_to').first().fadeOut(100).fadeIn(300);
                            });
                            displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                            inTimeEveryThingOk = false;
                            return false;
                        }
                    }
                }
            });
            if(!inTimeEveryThingOk) return false;
        });

        function scrollToItem(item, onCompleteScroll) {
            $([document.documentElement, document.body]).animate({
                scrollTop: item.offset().top - 164
            }, 800, function () {
                onCompleteScroll();
            });
        }
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
    });
</script>
<!-- END: PAGE SCRIPTS -->
@endpush
