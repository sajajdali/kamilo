@extends('admin.layout.layout')
@section('title',$title ?? 'نوبت‌ها')
@section('content')
    @inject('appointmentUserNameSpace', 'Modules\Appointment\Entities\AppointmentUser')
    <section class="table-layout" id="content">

        <!-- begin: .tray-center -->
        <div class="tray tray-center p25 va-t posr">
            <!-- create new order panel -->
            <div class="tray tray-center va-t posr rtl yekan">
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
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tray tray-center va-t posr">
                            <div class="admin-form theme-primary rtl yekan">
                                <div class="panel-heading" id="panel_header_clickable">
                                    <span class="panel-title">
                                    <span class="glyphicon glyphicon-cog hidden"></span>جست و جوی پیشرفته</span>
                                    <div class="widget-menu pull-left mr10">
                                        <i class="fa fa-plus-square text-success"></i>
                                    </div>
                                </div>
                                <div class="panel panel-collapse collapse in" id="advance_search"
                                     style="display: none;">

                                    <form id="admin-form" method="get" novalidate="novalidate">
                                        <input type="hidden" name="act" value="search">
                                        <div class="panel-body">
                                            <div class="section-divider mb40 mt20"><span>فیلتر کاربران</span></div>
                                            <!-- .section-divider -->
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="user_id" id="user_id"
                                                                       onkeypress="return isNumberKey(event);"
                                                                       value="{{ request()->user_id }}"
                                                                       class="gui-input" placeholder="شماره کاربر (ID)">
                                                                <label for="user_id" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="mobile" id="mobile"
                                                                       value="{{ request()->mobile }}"
                                                                       class="gui-input"
                                                                       placeholder="شماره موبایل کاربر">
                                                                <label for="mobile" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="firstname" id="firstname"
                                                                       value="{{ request()->firstname }}"
                                                                       class="gui-input" placeholder="نام کاربر">
                                                                <label for="firstname" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="lastname" id="lastname"
                                                                       value="{{ request()->lastname }}"
                                                                       class="gui-input"
                                                                       placeholder="نام‌خانوادگی کاربر">
                                                                <label for="lastname" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="section-divider mb40 mt20"><span>فیلتر کاربر ثبت کننده</span></div>
                                            <!-- .section-divider -->
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="start_date_store_appointment" id="start_date_store_appointment"
                                                                       autocomplete="off"
                                                                       class="gui-input" placeholder="تاریخ شروع"  data-jdp >
                                                                <input type="hidden" name="alt_start_date_store_appointment"
                                                                       id="alt_start_date_store_appointment"/>
                                                                <label for="start_date" class="field-icon"><i
                                                                            class="fa fa-calendar"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="end_date_store_appointment" id="end_date_store_appointment"
                                                                       autocomplete="off"
                                                                       class="gui-input" placeholder="تاریخ پایان"  data-jdp>
                                                                <input type="hidden" name="alt_end_date_store_appointment"
                                                                       id="alt_end_date_store_appointment"/>
                                                                <label for="end_date" class="field-icon"><i
                                                                            class="fa fa-calendar"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="agent_id" id="agent_id"
                                                                       value="{{ request()->agent_id }}"
                                                                       onkeypress="return isNumberKey(event);"
                                                                       class="gui-input" placeholder="شماره کاربر ثبت کننده (ID)">
                                                                <label for="agent_id" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="agent_name" id="agent_name"
                                                                       value="{{ request()->agent_name }}"
                                                                       class="gui-input"
                                                                       placeholder="نام کاریر ثبت کننده">
                                                                <label for="agent_name" class="field-icon"><i
                                                                            class="fa fa-user"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end section -->
                                            <div class="section-divider mb40 mt20"><span>فیلتر بخش</span></div>
                                            <div class="section">
                                                <div class="row">
                                                    <label class="field select">
                                                        <select name="part_id" id="part_id" aria-required="true"
                                                                aria-invalid="false">
                                                            <option value="0">انتخاب بخش</option>
                                                            @foreach($parts as $part)
                                                                <option value="{{ $part->id }}">
                                                                    {{ $part->title }}</option>
                                                                @if($part->children()->exists())
                                                                    @include('appointment::admin.appointment_part._part_child_item', array('main_part' => $part->children,'under_line_counter'=>1))
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <i class="arrow"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="section-divider mb40 mt50"><span>فیلتر پزشک</span></div>
                                            <div class="section">
                                                <div class="row">
                                                        <label class="field select" id="label_doctor">
                                                        <select name="doctor_id" id="doctor_id" aria-required="true"
                                                                aria-invalid="false">
                                                            <option value="0">انتخاب پزشک</option>
                                                            @foreach($doctors as $doctor)
                                                                <option value="{{ $doctor->id }}">
                                                                    {!! $doctor->full_name ?? ' <span style="color: red;">یافت نشد</span> '!!}</option>
                                                            @endforeach
                                                        </select>
                                                        <i class="arrow"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="section-divider mb40 mt40"><span>فیلتر تاریخ</span></div>
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="start_date" id="start_date_picker"
                                                                       autocomplete="off"
                                                                       class="gui-input" placeholder="تاریخ شروع" data-jdp>
                                                                <input type="hidden" name="alt_start_date"
                                                                       id="alt_start_date"/>
                                                                <label for="start_date" class="field-icon"><i
                                                                            class="fa fa-calendar"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="end_date" id="end_date_picker"
                                                                       autocomplete="off"
                                                                       class="gui-input" placeholder="تاریخ پایان" data-jdp>
                                                                <input type="hidden" name="alt_end_date"
                                                                       id="alt_end_date"/>
                                                                <label for="end_date" class="field-icon"><i
                                                                            class="fa fa-calendar"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="start_clock" id="start_clock"
                                                                       autocomplete="off"
                                                                       value="{{ request()->start_clock }}"
                                                                       class="gui-input" placeholder="ساعت شروع">
                                                                <label for="start_clock" class="field-icon"><i
                                                                            class="fa fa-clock-o"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="end_clock" id="end_clock"
                                                                       autocomplete="off"
                                                                       value="{{ request()->end_clock }}"
                                                                       class="gui-input" placeholder="ساعت پایان">
                                                                <label for="end_clock" class="field-icon"><i
                                                                            class="fa fa-clock-o"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="section-divider mb40 mt20"><span>فیلتر نوع نوبت</span></div>
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <label class="field select">
                                                            <select name="kind" id="kind" aria-required="true"
                                                                    aria-invalid="false">
                                                                <option value="-1">انتخاب نوع نوبت</option>
                                                                @foreach($appointmentUserNameSpace::$kinds as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request()->kind !=null && request()->kind == $key) SELECTED @endif>{{ $value["title"] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="arrow"></i>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <label class="field select">
                                                            <select name="status" id="status" aria-required="true"
                                                                    aria-invalid="false">
                                                                <option value="-1">انتخاب وضعیت نوبت</option>
                                                                @foreach($appointmentUserNameSpace::$status as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request()->status !=null && request()->status == $key) SELECTED @endif>{{ $value["title"] }}</option>
                                                                @endforeach

                                                            </select>
                                                            <i class="arrow"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <label class="field select">
                                                            <select name="transaction" id="transaction"
                                                                    aria-required="true"
                                                                    aria-invalid="false">
                                                                <option value="0"
                                                                        @if(request()->transaction == 0) SELECTED @endif>
                                                                    انتخاب نوع پرداخت
                                                                </option>
                                                                <option value="1"
                                                                        @if(request()->transaction == 1) SELECTED @endif>
                                                                    پرداخت شده
                                                                </option>
                                                                <option value="2"
                                                                        @if(request()->transaction == 2) SELECTED @endif>
                                                                    پرداخت نشده
                                                                </option>
                                                            </select>
                                                            <i class="arrow"></i>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="code" id="code"
                                                                       value="{{ request()->code }}"
                                                                       class="gui-input"
                                                                       placeholder="کد پیگیری">
                                                                <label for="code" class="field-icon"><i
                                                                            class="fa fa-cog"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <label class="field select">
                                                            <select name="acquainted" id="transaction"
                                                                    aria-required="true"
                                                                    aria-invalid="false">
                                                                <option value="0"
                                                                        @if(request()->acquainted == 0) SELECTED @endif>
                                                                    طریقه آشنایی
                                                                </option>
                                                                @foreach(\Modules\Appointment\Entities\Appointment::$ACQUAINTED as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request()->acquainted !=null && request()->acquainted == $key) SELECTED @endif>{{$value }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="arrow"></i>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="identifierـcode" id="identifierـcode"
                                                                       value="{{ request()->identifierـcode }}"
                                                                       class="gui-input"
                                                                       placeholder="کد معرف / زیبایار">
                                                                <label for="identifierـcode" class="field-icon"><i
                                                                            class="glyphicons glyphicons-gift"></i>
                                                                </label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">
                                                        <label class="field select">
                                                            <select name="appointment_first_type" id="transaction"
                                                                    aria-required="true"
                                                                    aria-invalid="false">
                                                                <option value="0"
                                                                        @if(request()->appointment_first_type == 0) SELECTED @endif>
                                                                  نوبت اولی؟
                                                                </option>
                                                                @foreach(\Modules\Appointment\Entities\Appointment::$appointmentType as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                            @if(request()->appointment_first_type !=null && request()->appointment_first_type == $key) SELECTED @endif>{{$value }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="arrow"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="section">
                                                <div class="row">
                                                    <div class="col-md-6 pull-right">
                                                        <div class="section">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="city" id="city"
                                                                       value="{{ request()->city }}"
                                                                       class="gui-input"
                                                                       placeholder="شهر / استان">
                                                                <label for="city" class="field-icon"><i
                                                                            class="glyphicons glyphicons-airplane"></i>
                                                                </label>
                                                                اگر میخواهید همه بجز همین شهری که وارد کردید فیلتر شود، درا نتهای اسم شهر علامت + بگذارید
                                                                مثال: (تهران+)
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pull-right">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer text-left">
                                            <button class="button btn-primary " type="submit">جست و جو</button>
                                        </div><!-- end .form-footer section -->
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <!-- recent orders table -->
            <form method="POST"
                  @if($mAppointmentUser->count() >= 1) action="{{ route('admin.appointment_user.update',optional($mAppointmentUser->first())->id) }} @endif"
                  id="group_act">
                @csrf
                @method('PUT')
                <div class="panel">
                    <div class="panel-menu p12 theme-primary" style="display: none" id="header_action">
                        <div class="row">
                            @can("edit_admin_appointment_user")
                                <div class="col-xs-4 pull-right">
                                    <button type="submit" name="act" value="approve"
                                            class="btn btn-rounded btn-success btn-block">تایید نوبت‌های انتخابی
                                    </button>
                                </div>
                                <div class="col-xs-4 pull-right">
                                    <button type="button" class="btn btn-rounded btn-warning btn-block"
                                            id="btn-cancel-group">کنسل کردن نوبت‌های انتخابی
                                    </button>
                                </div>
                            @endcan
                            @can("delete_admin_appointment_user")
                                <div class="col-xs-4 pull-right">
                                    <button type="button" class="btn btn-rounded btn-danger btn-block"
                                            id="btn-delete-group">حذف نوبت‌های انتخابی
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13 rtl yekan">
                            <caption>
                                <div class="caption_table text-center">
                                    <span data-filter="primary"><i
                                                class="fa fa-circle text-info mr20 ml5"
                                                style="position: relative;top: 2px;"></i> نوبت‌های تایید شده بین مریض</span>
                                    <span data-filter="success"><i class="fa fa-circle text-success mr20 ml5"
                                                                   style="position: relative;top: 2px;"></i>نوبت‌های تایید شده اصلی</span>
                                    <span data-filter="danger"><i class="fa fa-circle text-danger mr20 ml5"
                                                                  style="position: relative;top: 2px;"></i>نوبت‌های کنسل شده</span>
                                    <span data-filter="warning"><i class="fa fa-circle text-warning mr20 ml5"
                                                                   style="position: relative;top: 2px;"></i>نوبت‌های در انتظار</span>
                                </div>
                            </caption>
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="text-center">#</th>
                                <th class="text-center" id="choice_all">انتخاب</th>
                                <th class="txt_center">وضعیت</th>
                                <th class="txt_right">ثبت کننده</th>
                                <th class="txt_right">زیبا‌جو</th>
                                <th class="txt_right">شهر</th>
                                <th class="txt_center">موبایل</th>
                                <th class="txt_center">بخش</th>
                                <th class="txt_center">پزشک</th>
                                <th class="txt_center">تاریخ</th>
                                <th class="txt_center">از ساعت</th>
                                <th class="txt_center">تاریخ ثبت</th>
                                <th class="text-center">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($mAppointmentUser))
                                @foreach($mAppointmentUser as $item)
                                    <tr class="data-visit @if($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_SUBSIDIARY_APPOINTMENT) primary @else {{ $appointmentUserNameSpace::$status[$item->status]['class'] }} @endif">
                                        <td class="text-center">
                                            @if($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_SUBSIDIARY_APPOINTMENT)
                                                بین مریض
                                            @else
                                                @if($item->status == $appointmentUserNameSpace::STATUS_OK)
                                                    {{ $item->id }}
                                                @else
                                                    -
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <label class="option @if($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_SUBSIDIARY_APPOINTMENT) option-primary @else option-{{ $appointmentUserNameSpace::$status[$item->status]['class'] }} @endif">
                                                <input type="checkbox" name="appointment_user[]"
                                                       value="{{ $item->id }}" class="multi_checked_item">
                                                <span class="checkbox"></span></label>
                                        </td>
                                        <td class="text-center">

                                            <i class="{{ $appointmentUserNameSpace::$status[$item->status]['icon'] }} text-{{ $appointmentUserNameSpace::$status[$item->status]['class'] }}"></i>
                                        </td>
                                        <td class="text-right">
                                            @if($item->agent != null)

                                                <span>{!! $item->agent->full_name ?? '' !!} ({{$item->agent->id}})</span>


                                            @else
                                                <span class="text-danger">این کاربر حذف شده است</span>
                                            @endif

                                        </td>


                                        <td class="text-right">
                                            @if($item->user != null)
                                                <img src="{{ $item->user->avatar }}" title="user"
                                                     class="img-responsive mw20 ib ">
                                                <span>{{ $item->user->full_name }}</span>
                                                @if(isset($item->detail[$appointmentUserNameSpace::IDENTIFIERـCODE]))
                                                    <span class="sidebar-title-tray">
                                                        <span class="label bg-danger">معرف : {{$item->detail[$appointmentUserNameSpace::IDENTIFIERـCODE]}}</span>
                                                    </span>
                                                @endif
                                                @if($item->user->national_code)
                                                    <a href="{{ route('admin.user.edit',['id'=>$item->user->id]) }}" class="sidebar-title-tray">
                                                        <span class="label bg-info"> کد ملی : {{$item->user->national_code}}</span>
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-danger">این کاربر حذف شده است</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($item->user != null)
                                                <span>{{ $item->user->address }}</span>
                                            @else
                                                <span class="text-danger">-</span>
                                            @endif
                                        </td>
                                        <td class="">
                                            @if($item->user != null)
                                                {{ $item->user->mobile }}
                                                @if(isset($item->detail['SECOND_MOBILE']))
                                                    <br>
                                                <span class="text-danger">
                                                   شماره دوم : {{ $item->detail['SECOND_MOBILE'] ?? '' }}
                                                    </span>
                                                @endif


                                            @else
                                                <span class="text-danger">این کاربر حذف شده است</span>
                                            @endif
                                        </td>
                                        <td class="">{{ $item->appointmentPart->title ?? '' }}
                                            @if(count($item->r_part) > 0 && is_assoc($item->r_part))

                                                - [
                                                @foreach($item->r_part as $partName => $partTime)
                                                    {{$partName}}
                                                    @unless ($loop->last)-@endunless
                                                @endforeach
                                                ]
                                            @endif
                                        </td>
                                        <td class="text-center">{!! $item->doctor->full_name ?? ' <span style="color: red;">یافت نشد</span> ' !!}</td>
                                        <td class="text-center">
                                            {{ jdate('l j F',jmktime(0,0,0,$item->month,$item->day,$item->year)) }}
                                        </td>
                                        <td class="text-center">{{ substr($item->time_from, 0, -3) }}</td>
                                        <td class="text-center">{{ jdate('H:i | Y/m/d',$item->created_at->timestamp,'','','en') }}</td>
                                        <td class="text-right">
                                            <div class="btn-group text-right">
                                                <button aria-expanded="false" data-toggle="dropdown"
                                                        class="btn @if($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_SUBSIDIARY_APPOINTMENT) btn-primary @else btn-{{ $appointmentUserNameSpace::$status[$item->status]['class'] }} @endif br2 btn-xs fs12 dropdown-toggle"
                                                        type="button">{{ $appointmentUserNameSpace::$status[$item->status]['title'] }}
                                                    <span class="caret ml5"></span>
                                                </button>
                                                <ul role="menu" class="dropdown-menu">
                                                    @if ($item->status == $appointmentUserNameSpace::STATUS_PENDING || $item->status == $appointmentUserNameSpace::STATUS_OK )


                                                        @can('change_time_admin_appointment_user')
                                                            @if(Auth::user()->checkPermissionTo('admin_type_limited_appointment_user_access_update_time'))
                                                                @if($item->agent_id == Auth::user()->id)
                                                                    <li>
                                                                        <a href="{{ route('admin.appointment.add',['part_id' => $item->appointment_part_id , 'doctor_id' => $item->doctor_id,'code' => $item->id , 'month' => $item->month , 'year' => $item->year , 'day' => $item->day]) }}">ویرایش زمان نوبت </a>
                                                                    </li>
                                                                @endif
                                                            @else
                                                                <li>
                                                                    <a href="{{ route('admin.appointment.add',['part_id' => $item->appointment_part_id , 'doctor_id' => $item->doctor_id,'code' => $item->id , 'month' => $item->month , 'year' => $item->year , 'day' => $item->day]) }}">ویرایش زمان نوبت </a>
                                                                </li>
                                                            @endif
                                                        @endcan
                                                    @endif
                                                    @can('cancel_admin_appointment_user')
                                                        @if($item->status == $appointmentUserNameSpace::STATUS_OK)

                                                            <li
                                                                    @if(!Auth::user()->can('admin_type_full_appointment_user_access_cancel'))
                                                                    @if($item->agent_id != Auth::id())
                                                                    style="display: none;"
                                                                    @endif
                                                                    @endif
                                                            >
                                                                <a href="{{ route('admin.appointment_user.edit', ['id'=>$item->id,'act'=>'cancel']) }}">کنسل
                                                                    کردن نوبت (معمولی)</a>
                                                                <a href="{{ route('admin.appointment_user.edit', ['id'=>$item->id,'act'=>'cancel','sms'=>'send']) }}">کنسل
                                                                    کردن نوبت (پیامک هم ارسال شود)</a>
                                                            </li>
                                                                <li>
                                                                    <a href="{{ route('admin.standard_sms', ['appointment'=>$item->id]) }}">ارسال پیامک های سیستمی</a>
                                                                </li>
                                                        @elseif($item->status == $appointmentUserNameSpace::STATUS_PENDING)
                                                            <li>
                                                                <a href="{{ route('admin.appointment_user.edit', ['id'=>$item->id,'act'=>'approve']) }}">تایید
                                                                    نوبت</a>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                    @can("edit_admin_appointment_user")

                                                        @if($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_SUBSIDIARY_APPOINTMENT)
                                                            <li>
                                                                <a href="{{ route('admin.appointment_user.edit', ['id'=>$item->id,'act'=>'change_to_main']) }}">تبدیل
                                                                    به نوبت اصلی</a>
                                                            </li>
                                                        @elseif($item->status == $appointmentUserNameSpace::STATUS_OK && $item->kind == $appointmentUserNameSpace::KIND_MAIN_APPOINTMENT)
                                                            <li>
                                                                <a href="{{ route('admin.appointment_user.edit', ['id'=>$item->id,'act'=>'change_to_subsidiary']) }}">تبدیل
                                                                    به نوبت بین مریض</a>
                                                            </li>
                                                        @endif
                                                    @endcan
                                                    @can("delete_admin_appointment_user")
                                                        @if ($item->status != $appointmentUserNameSpace::STATUS_DELETED)


                                                            <li class="divider"></li>
                                                            <li
                                                                    @if(!Auth::user()->can('admin_type_full_appointment_user_access_delete'))
                                                                    @if($item->agent_id != Auth::id())
                                                                    style="display: none;"
                                                                    @endif
                                                                    @endif

                                                            >
                                                                <a class="delete_user_appointment"
                                                                   daشta-effect="mfp-rotateLeft"
                                                                   href="{{ route('admin.appointment_user.destroy', $item->id) }}">حذف
                                                                    نوبت</a>
                                                            </li>
                                                                <li>
                                                                    <a href="{{ route('admin.standard_sms', ['appointment'=>$item->id]) }}">ارسال پیامک های سیستمی</a>
                                                                </li>
                                                        @endif
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($mAppointmentUser))
                            <center class="rtl">{{ $mAppointmentUser->links() }}</center>
                        @endif
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-xs-2">
                    <a href="{{  request()->fullUrlWithQuery(['export'=>'1']) }}" target="_blank">
                        <button type="button" class="btn btn-alert btn-block">دانلود خروجی اکسل</button>
                    </a>
                </div>
            </div>

        </div>
    </section>
    <!-- Panel popup -->
    <!-- Admin Form Popup -->
    <div id="modal-cancel-group" class=" popup-basic admin-form mfp-with-anim mfp-hide">
        <div class="panel yekan rtl">
            <div class="panel-heading text-right">
                <span class="panel-title">کنسل نوبت‌ها</span>
            </div>
            <!-- end .panel-heading section -->

            <form method="post" action="/" id="comment">
                <div class="panel-body p25">
                    <div id="add_step_2">
                        <div class="section-divider mt10 mb25" id="spy5">
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
                        <div class="section row" style="display: none;" id="sms_text_content">
                            <div class="col-md-12">
                                <div class="section">
                                    <label class="field prepend-icon">
                                        <textarea class="gui-textarea" id="custom_sms_text" name="custom_sms_text"
                                                  placeholder="حتما دلیل کنسل کردن را وارد کنید"> کنسل شدن نوبت</textarea>
                                        <label for="custom_sms_text" class="field-icon"><i class="fa fa-comments"></i>
                                        </label>
                                        <span class="input-footer"><strong>راهنما: </strong>دلیل کنسل کردن نوبت ها</span>
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
                    <button type="button" class="btn ladda-button btn-primary" data-style="contract" id="btn-cancel">
                        <span class="ladda-label">کنسل کن</span>
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
    <div id="modal-delete-group" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
        <div class="panel">
            <div class="panel-heading" style="text-align: right">
								<span class="panel-icon"><i class="fa fa-pencil"></i>
								</span>
                <span class="panel-title">حذف نوبت‌ها</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف تمام نوبت‌های انتخابی اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete-group-approve">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

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
    <!-- End: Content -->
@endsection
@push('scripts')
{{--    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">--}}
<script src="{{ asset('admin/files/datepicker/js/jalalidatepicker.min.js') }}"></script>
<style>
    jdp-container {
        animation: jdpOpenAnimation .3s cubic-bezier(.23, 1, .32, 1);
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 6px rgba(0, 0, 0, .12), 0 1px 4px rgba(0, 0, 0, .24);
        direction: rtl;
        display: none;
        max-width: 307.875px;
        min-width: 307.875px;
        overflow: hidden;
        padding: .5rem 0;
        position: absolute;
        touch-action: manipulation;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none
    }

    @media only screen and (max-width: 481px) {
        jdp-container {
            animation: jdpOpenAnimationMobile .3s cubic-bezier(.23, 1, .32, 1);
            border-radius: 4px 4px 0 0;
            bottom: 0 !important;
            left: 50% !important;
            max-width: 100%;
            min-width: 280px;
            top: unset !important;
            transform: translateX(-50%) !important;
            width: 100%
        }
    }

    jdp-container, jdp-container *, jdp-container :after, jdp-container :before {
        box-sizing: border-box
    }

    jdp-container .jdp-icon-minus, jdp-container .jdp-icon-plus {
        border: 1px solid #e6e6e6;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        flex: none;
        overflow: hidden;
        text-align: center;
        text-decoration: none;
        vertical-align: middle
    }

    jdp-container .jdp-icon-minus svg, jdp-container .jdp-icon-plus svg {
        height: 1.5rem;
        padding: .25rem;
        vertical-align: middle;
        width: 1.5rem
    }

    jdp-container .jdp-icon-minus.not-in-range, jdp-container .jdp-icon-plus.not-in-range {
        cursor: not-allowed
    }

    jdp-container .jdp-icon-minus.not-in-range svg, jdp-container .jdp-icon-plus.not-in-range svg {
        opacity: .3
    }

    jdp-container .jdp-months, jdp-container .jdp-years {
        fill: rgba(0, 0, 0, .9);
        color: rgba(0, 0, 0, .9);
        display: inline-flex;
        font-size: 120%;
        margin: 0 2.5%
    }

    jdp-container .jdp-months {
        width: 50%
    }

    jdp-container .jdp-years {
        width: 40%
    }

    jdp-container .jdp-month, jdp-container .jdp-month input, jdp-container .jdp-month select, jdp-container .jdp-time, jdp-container .jdp-time input, jdp-container .jdp-time select, jdp-container .jdp-year, jdp-container .jdp-year input, jdp-container .jdp-year select {
        background: #fff;
        border: none;
        border-radius: 0;
        color: inherit;
        display: inline-block;
        font-family: inherit;
        font-size: inherit;
        font-weight: 300;
        height: auto;
        line-height: inherit;
        margin: 0;
        outline: none;
        padding: 0;
        text-align: center;
        vertical-align: initial;
        width: 100%
    }

    jdp-container .jdp-month:active, jdp-container .jdp-month:focus, jdp-container .jdp-month input:active, jdp-container .jdp-month input:focus, jdp-container .jdp-month select:active, jdp-container .jdp-month select:focus, jdp-container .jdp-time:active, jdp-container .jdp-time:focus, jdp-container .jdp-time input:active, jdp-container .jdp-time input:focus, jdp-container .jdp-time select:active, jdp-container .jdp-time select:focus, jdp-container .jdp-year:active, jdp-container .jdp-year:focus, jdp-container .jdp-year input:active, jdp-container .jdp-year input:focus, jdp-container .jdp-year select:active, jdp-container .jdp-year select:focus {
        outline: none
    }

    jdp-container .jdp-month input option, jdp-container .jdp-month option, jdp-container .jdp-month select option, jdp-container .jdp-time input option, jdp-container .jdp-time option, jdp-container .jdp-time select option, jdp-container .jdp-year input option, jdp-container .jdp-year option, jdp-container .jdp-year select option {
        font-size: 95%;
        min-height: 1.3rem;
        outline: none;
        padding: 0
    }

    jdp-container .jdp-month:hover, jdp-container .jdp-time:hover, jdp-container .jdp-year:hover {
        filter: brightness(.9)
    }

    jdp-container .jdp-month input, jdp-container .jdp-time input, jdp-container .jdp-year input {
        -webkit-appearance: none;
        -moz-appearance: textfield;
        cursor: text
    }

    jdp-container .jdp-month input::-webkit-inner-spin-button, jdp-container .jdp-month input::-webkit-outer-spin-button, jdp-container .jdp-time input::-webkit-inner-spin-button, jdp-container .jdp-time input::-webkit-outer-spin-button, jdp-container .jdp-year input::-webkit-inner-spin-button, jdp-container .jdp-year input::-webkit-outer-spin-button {
        -webkit-appearance: none
    }

    jdp-container .jdp-month select, jdp-container .jdp-time select, jdp-container .jdp-year select {
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
        cursor: pointer;
        position: relative
    }

    jdp-container .jdp-days {
        -ms-flex-pack: justify;
        display: inline-block;
        display: flex;
        flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        justify-content: space-around;
        outline: 0;
        padding: .5rem 0 0;
        text-align: left;
        width: 100%
    }

    jdp-container .jdp-day, jdp-container .jdp-day-name {
        background: none;
        border: 1px solid transparent;
        color: rgba(0, 0, 0, .9);
        display: inline-block;
        flex-basis: 14.2857143%;
        font-weight: 400;
        height: 31px;
        justify-content: center;
        line-height: 31px;
        margin: 2px 0;
        position: relative;
        text-align: center;
        width: 14.2857143%
    }

    jdp-container .jdp-day-name.today, jdp-container .jdp-day.today {
        border-color: rgba(0, 0, 0, .3)
    }

    jdp-container .jdp-day-name.selected, jdp-container .jdp-day.selected {
        background-color: #569ff7 !important;
        color: #fff !important;
        opacity: 1 !important
    }

    jdp-container .jdp-day-name.holly-day, jdp-container .jdp-day-name.last-week, jdp-container .jdp-day.holly-day, jdp-container .jdp-day.last-week {
        color: #f44336
    }

    jdp-container .jdp-day.not-in-month {
        opacity: .4
    }

    jdp-container .jdp-day.disabled-day {
        cursor: not-allowed;
        opacity: .15
    }

    jdp-container .jdp-day:not(.disabled-day) {
        border-radius: 4px;
        cursor: pointer;
        transition: .1s linear
    }

    jdp-container .jdp-day:not(.disabled-day):hover {
        background: rgba(0, 0, 0, .1);
        transform: scale(1.15);
        z-index: 1
    }

    jdp-container .jdp-day-name {
        background-color: rgba(0, 0, 0, .1);
        border-radius: 0;
        cursor: default;
        font-size: 90%;
        font-weight: 900
    }

    jdp-container .jdp-footer {
        -ms-flex-pack: justify;
        display: inline-block;
        display: flex;
        flex-wrap: nowrap;
        -ms-flex-wrap: nowrap;
        justify-content: space-between;
        outline: 0;
        padding: .5rem .5rem 0;
        width: 100%
    }

    @media only screen and (max-width: 481px) {
        jdp-container .jdp-footer {
            margin: .5rem 0
        }
    }

    jdp-container .jdp-btn-close, jdp-container .jdp-btn-empty, jdp-container .jdp-btn-today {
        background: #569ff7;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-size: 90%;
        font-weight: 400;
        padding: .3em .6em;
        text-align: center
    }

    @media only screen and (max-width: 481px) {
        jdp-container .jdp-btn-close, jdp-container .jdp-btn-empty, jdp-container .jdp-btn-today {
            font-size: 100%;
            padding: .5em .8em
        }
    }

    jdp-container .jdp-btn-close.disabled-btn, jdp-container .jdp-btn-empty.disabled-btn, jdp-container .jdp-btn-today.disabled-btn {
        cursor: not-allowed;
        opacity: .2
    }

    @media only screen and (max-width: 481px) {
        jdp-container .jdp-btn-today ~ .jdp-btn-empty {
            margin-left: auto;
            margin-right: 1em
        }
    }

    jdp-container .jdp-time-container {
        display: flex;
        padding: 0
    }

    jdp-container .jdp-time-container .jdp-time {
        flex: auto;
        margin: 0 .5rem;
        position: relative
    }

    jdp-container .jdp-time-container .jdp-time select {
        background: rgba(0, 0, 0, .03);
        border-radius: 5px;
        padding: .5rem 1rem .5rem 7px
    }

    jdp-container .jdp-time-container .jdp-time:after {
        content: ":";
        font-size: 1.5rem;
        height: 100%;
        position: absolute;
        right: -.7rem;
        top: 50%;
        transform: translateY(-50%)
    }

    jdp-container .jdp-time-container .jdp-time:first-child:after {
        display: none
    }

    jdp-container .jdp-time-container.jdp-only-time .jdp-time select {
        font-size: 1.5rem;
        padding: .8rem 1rem .8rem 7px
    }

    jdp-container .jdp-time-container.jdp-only-time .jdp-time:after {
        font-size: 2.3rem;
        position: absolute;
        right: -.8rem
    }

</style>
{{--    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>--}}
    <script>
        jalaliDatepicker.startWatch();
    </script>
    <!-- Fancytree CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') }}">
    <style>
        #fancytree-drop-marker, span.fancytree-checkbox, span.fancytree-drag-helper-img, span.fancytree-empty, span.fancytree-expander, span.fancytree-icon, span.fancytree-radio, span.fancytree-vline {
            transform: rotate(90deg);
        }

        #panel_header_clickable {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>

    <!-- Fancytree Plugin -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/fancytree/jquery.fancytree-all.min.js') }}"></script>

    <script type="text/javascript"
            src="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/plugins/clockpicker/dist/bootstrap-clockpicker.css') }}">

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
        #choice_all {
            cursor: pointer;
        }
    </style>

    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
    <!-- Page Javascript -->

    <script type="text/javascript">
        jQuery(document).ready(function () {
            //cancel group
            $("#btn-cancel").on('click', function () {
                const smsStatus = $('input[name="sms_status"]:checked').val();
                const smsContent = $('#custom_sms_text');
                if (smsStatus === '1') {
                    if (smsContent.val().length === 0) {
                        displayErrorNotify("متن پیامک را وارد کنید");
                        smsContent.focus();
                        return false;
                    }
                }
                const groupForm = $("#group_act");
                groupForm.append("<input type='hidden' name='act' value='cancel' />");
                groupForm.append("<input type='hidden' name='sms_status' value='" + smsStatus + "' />");
                groupForm.append("<input type='hidden' name='sms_content' value='" + smsContent.val() + "' />");
                groupForm.submit();
            });
            $('input[name="sms_status"]').change(function () {
                if (this.value === '0') {
                    $('#sms_text_content').hide();
                } else if (this.value === '1') {
                    $('#sms_text_content').slideDown();
                }
            });
            $('#btn-cancel-group').on('click', function (e) {
                e.preventDefault();
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-cancel-group'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });
            //group delete
            $('#delete-group-approve').on('click', function () {
                const groupForm = $("#group_act");
                groupForm.append("<input type='hidden' name='act' value='delete' />");
                groupForm.submit();
            });
            $('#btn-delete-group').on('click', function (e) {
                e.preventDefault();
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-delete-group'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });
            //header panel
            $('.multi_checked_item').on('change', function () {
                const headerMenu = $('#header_action');
                if ($('.multi_checked_item:checked').length >= 1) {
                    if (headerMenu.is(":hidden")) {
                        headerMenu.slideDown();
                    }
                } else {
                    if (headerMenu.is(":visible")) {
                        headerMenu.slideUp();
                    }
                }
            });
            //panel
            const panelHeader = $("#panel_header_clickable");
            const advanceSearch = $("#advance_search");
            const iTag = panelHeader.find('i');
            panelHeader.on('click', function () {
                if (iTag.hasClass("fa-plus-square")) {
                    iTag.removeClass("fa-plus-square").addClass("fa-minus-square");
                } else {
                    iTag.addClass("fa-plus-square").removeClass("fa-minus-square");
                }
                advanceSearch.slideToggle();
            });
            @if(request()->act != null && request()->act == "search")
            panelHeader.click();
            @endif
            //filter part id
            @if(request()->part_id != null && intval(request()->part_id) != 0)
            $('#part_id').val({{ request()->part_id }});
            @endif
            @if(request()->doctor_id != null && intval(request()->doctor_id) != 0)
            $("#doctor_id").val({{ request()->doctor_id }});
                    @endif
            const filterSpan = $('.caption_table span');
            filterSpan.mouseenter(function () {
                const csl = $(this).data('filter');
                $('.data-visit').css('opacity', '0.3');
                $('tr.' + csl).css('opacity', '1');
            });
            filterSpan.mouseleave(function () {
                $('.data-visit').css('opacity', '1');
            });
            filterSpan.click(function () {
                const csl = $(this).data('filter');
                $('tr.' + csl).find('input[type="checkbox"]').attr("checked", "checked");
            });
            //caleder action
            const startDate = $("#start_date");
            const endDate = $("#end_date");
            const startClock = $("#start_clock");
            const endClock = $("#end_clock");
            startDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_start_date',
                initialValue: false
            });
            startDate.val("{{ request()->start_date }}");
            startDate.mask('9999/99/99');

            const startDateStoreAppointment = $("#start_date_store_appointment");
            const endDateStoreAppointment = $("#end_date_store_appointment");
            startDateStoreAppointment.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_start_date_start_appointment',
                initialValue: false
            });
            startDateStoreAppointment.val("{{ request()->start_date_store_appointment }}");
            startDateStoreAppointment.mask('9999/99/99');


            endDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_end_date',
                initialValue: false
            });
            endDate.val("{{ request()->end_date }}");
            endDate.mask('9999/99/99');
            startClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });

            endDateStoreAppointment.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_end_date_store_appointment',
                initialValue: false
            });
            endDateStoreAppointment.val("{{ request()->end_date_store_appointment }}");
            endDateStoreAppointment.mask('9999/99/99');

            endClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            //validate form
            $('#admin-form').on('submit', function () {
                let start = getTimeInDay(startClock.val());
                let end = getTimeInDay(endClock.val());
                if (start > end) {
                    scrollToItem(startClock, function () {
                        startClock.focus()
                    });
                    displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                    return false;
                }
                if (startDate.val().length >= 1 && endDate.val().length >= 1) {
                    if ($("#alt_start_date").val() > $("#alt_end_date").val()) {
                        scrollToItem(startClock, function () {
                            endDate.focus()
                        });
                        displayErrorNotify("تاریخ شروع و پایان به درستی انتخاب نشده است");
                        return false;
                    }
                }
            });
            $('.delete_user_appointment').on('click', function (e) {
                e.preventDefault();
                const link = $(this).attr('href');
                $('#delete_link').attr('action', link);
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-panel'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });

            function getTimeInDay(value) {
                if (value.length !== 0) {
                    let timeBase = value.split(":");
                    const min = parseInt(timeBase[1]);
                    const hr = parseInt(timeBase[0]);
                    return (hr * 60) + min;
                } else {
                    return 0;
                }
            }

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

            let statusCheckAll = false;
            $('#choice_all').on('click',function () {
                $('.multi_checked_item').each(function(){
                    if(statusCheckAll){
                        if($(this).is(":checked")){
                            $(this).click();
                        }
                    }else{
                        if(!$(this).is(":checked")){
                            $(this).click();
                        }
                    }
                });
                statusCheckAll = !statusCheckAll;
            });
        });

        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        }
    </script>
@endpush
