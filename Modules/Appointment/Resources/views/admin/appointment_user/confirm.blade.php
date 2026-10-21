@extends('admin.layout.layout')
@section('title', $title)
@section('content')
    @inject('appointmentUserNameSpace', 'Modules\Appointment\Entities\AppointmentUser')
    <section class="table-layout" id="content">
        <div class="tray tray-center p25 va-t posr rtl yekan">
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
            @if($errors->any())
                <div class="alert alert-danger alert-dismissable mb30 rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    {{ $errors->first() }}
                </div>
            @endif

            @php
                $advancedFilterNames = ['patient_name', 'mobile', 'national_code', 'user_id', 'appointment_id', 'identifier_code', 'doctor_id', 'appointment_part_id'];
                $activeFilterCount = 0;
                foreach ($advancedFilterNames as $filterName) {
                    if (request()->filled($filterName)) {
                        $activeFilterCount++;
                    }
                }
            @endphp
            <div class="panel confirmed-search-panel">
                <div class="panel-heading confirmed-search-heading">
                    <div class="confirmed-search-title">
                        <span class="panel-title">{{ $title }}</span>
                        <small>{{ $description }}</small>
                    </div>
                    <div class="confirmed-search-actions">
                        <a href="{{ route('admin.appointment_user.index') }}" class="confirmed-back-button">
                            <i class="fa fa-arrow-right"></i>
                            بازگشت به لیست گزارشات
                        </a>
                        <button type="button" id="toggle_confirmed_filters"
                                class="confirmed-advanced-button {{ $activeFilterCount > 0 ? 'is-open' : '' }}"
                                aria-expanded="{{ $activeFilterCount > 0 ? 'true' : 'false' }}">
                            <i class="fa fa-sliders"></i>
                            جست‌وجوی پیشرفته
                            @if($activeFilterCount > 0)
                                <span class="confirmed-filter-count">{{ $activeFilterCount }}</span>
                            @endif
                            <i class="fa fa-chevron-down confirmed-filter-chevron"></i>
                        </button>
                    </div>
                </div>
                <div id="confirmed_advanced_filters" class="panel-body admin-form theme-primary confirmed-filter-body"
                     style="display: {{ $activeFilterCount > 0 ? 'block' : 'none' }};">
                    <form method="get">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="date" id="confirm_date_picker"
                                           data-selected-date="{{ $selectedDate }}"
                                           autocomplete="off"
                                           class="gui-input" placeholder="تاریخ">
                                    <input type="hidden" name="alt_confirm_date" id="alt_confirm_date">
                                    <label for="confirm_date_picker" class="field-icon"><i class="fa fa-calendar"></i></label>
                                </label>
                            </div>
                            <div class="col-md-3 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="patient_name" value="{{ request('patient_name') }}"
                                           class="gui-input" placeholder="نام یا نام خانوادگی زیباجو">
                                    <label class="field-icon"><i class="fa fa-user"></i></label>
                                </label>
                            </div>
                            <div class="col-md-3 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="mobile" value="{{ request('mobile') }}"
                                           class="gui-input confirmed-number-filter" inputmode="numeric"
                                           placeholder="شماره اصلی یا شماره دوم">
                                    <label class="field-icon"><i class="fa fa-mobile"></i></label>
                                </label>
                            </div>
                            <div class="col-md-3 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="national_code" value="{{ request('national_code') }}"
                                           class="gui-input confirmed-number-filter" inputmode="numeric" placeholder="کد ملی">
                                    <label class="field-icon"><i class="fa fa-id-card"></i></label>
                                </label>
                            </div>
                        </div>
                        <div class="row mt15">
                            <div class="col-md-2 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="user_id" value="{{ request('user_id') }}"
                                           class="gui-input confirmed-number-filter" inputmode="numeric" placeholder="شناسه زیباجو">
                                    <label class="field-icon"><i class="fa fa-hashtag"></i></label>
                                </label>
                            </div>
                            <div class="col-md-2 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="appointment_id" value="{{ request('appointment_id') }}"
                                           class="gui-input confirmed-number-filter" inputmode="numeric" placeholder="کد نوبت">
                                    <label class="field-icon"><i class="fa fa-calendar-check-o"></i></label>
                                </label>
                            </div>
                            <div class="col-md-3 pull-right">
                                <label class="field prepend-icon">
                                    <input type="text" name="identifier_code" value="{{ request('identifier_code') }}"
                                           class="gui-input" placeholder="کد معرف / زیبایار">
                                    <label class="field-icon"><i class="fa fa-gift"></i></label>
                                </label>
                            </div>
                            <div class="col-md-2 pull-right">
                                <label class="field select">
                                    <select name="doctor_id">
                                        <option value="">همه پزشکان</option>
                                        @foreach($confirmedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}" @if(intval(request('doctor_id')) === intval($doctor->id)) selected @endif>
                                                {{ $doctor->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                        </div>
                        <div class="row mt15">
                            <div class="col-md-3 pull-right">
                                <label class="field select">
                                    <select name="appointment_part_id">
                                        <option value="">همه بخش‌ها و زیر‌بخش‌ها</option>
                                        @foreach($confirmedPartOptions as $partOption)
                                            <option value="{{ $partOption['id'] }}"
                                                    @if(intval(request('appointment_part_id')) === intval($partOption['id'])) selected @endif>
                                                {{ $partOption['title'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="arrow"></i>
                                </label>
                                <small class="confirmed-part-hint">انتخاب بخش مادر شامل تمام زیر‌بخش‌های آن است.</small>
                            </div>
                            <div class="col-md-2 pull-right">
                                <button class="button btn-primary btn-block" type="submit">
                                    <i class="fa fa-search ml5"></i> اعمال فیلترها
                                </button>
                            </div>
                            <div class="col-md-2 pull-right">
                                <a class="button btn-default btn-block text-center"
                                   href="{{ route(request()->route()->getName(), ['date' => $selectedDate]) }}">
                                    پاک کردن فیلترها
                                </a>
                            </div>
                            @if(!empty($canSaveSelectedDate))
                                <div class="col-md-1 pull-right">
                                    <button class="button btn-success" type="submit" name="save_date" value="1">ثبت</button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="confirmed-results-summary">
                    <i class="fa fa-list-ul"></i>
                    <strong>{{ number_format($mAppointmentUser->total()) }}</strong>
                    نتیجه پیدا شد
                    @if($activeFilterCount > 0)
                        <span>با {{ $activeFilterCount }} فیلتر فعال</span>
                    @endif
                </div>
            </div>

            <div class="panel">
                <div class="panel-body pn">
                    <table class="table admin-form theme-warning tc-checkbox-1 fs13 rtl yekan">
                        <thead>
                        <tr class="bg-light yekan rtl">
                            <th class="text-center">ردیف</th>
                            <th class="text-center">کد نوبت</th>
                            <th class="text-center">Confirm</th>
                            <th class="text-right">زیباجو</th>
                            <th class="text-center">موبایل</th>
                            <th class="text-center">بخش</th>
                            <th class="text-center">پزشک</th>
                            <th class="text-center">تاریخ</th>
                            <th class="text-center">ساعت</th>
                            <th class="text-center">خدمت / سرویس</th>
                            <th class="text-center">کامنت</th>
                            <th class="text-center">ثبت کننده تایید</th>
                            <th class="text-center">زمان تایید</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($mAppointmentUser as $item)
                            @php
                                $rowNumber = (($mAppointmentUser->currentPage() - 1) * $mAppointmentUser->perPage()) + $loop->iteration;
                                $originalTimeFrom = substr($item->time_from, 0, 5);
                                $confirmedTimeFrom = $item->confirmed_time_from ? substr($item->confirmed_time_from, 0, 5) : $originalTimeFrom;
                                $isTimeChanged = $item->confirmed_time_from && $confirmedTimeFrom !== $originalTimeFrom;
                                $detail = is_array($item->detail) ? $item->detail : [];
                                $originalServiceName = $detail[\App\UserMeta::SERVICE_DETAIL] ?? ($detail[\App\UserMeta::SERVICE] ?? '');
                                $confirmedServiceName = $item->confirmed_service_name ?: $originalServiceName;
                                $isServiceChanged = $item->confirmed_service_name && $item->confirmed_service_name !== $originalServiceName;
                            @endphp
                            <tr class="@if($item->is_confirmed) success @endif">
                                <td class="text-center">{{ $rowNumber }}</td>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">
                                    @can('confirm_admin_appointment_user')
                                        <form method="post" action="{{ route('admin.appointment_user.confirm.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_confirmed" value="0">
                                            <label class="option option-success">
                                                <input type="checkbox" name="is_confirmed" value="1"
                                                       class="confirm-check"
                                                       data-time="{{ $confirmedTimeFrom }}"
                                                       data-primary-mobile="{{ optional($item->user)->mobile }}"
                                                       data-second-mobile="{{ $detail[$appointmentUserNameSpace::SECOND_MOBILE] ?? optional($item->user)->second_mobile }}"
                                                       @if($item->is_confirmed) checked @endif>
                                                <span class="checkbox"></span>
                                            </label>
                                        </form>
                                    @else
                                        @if($item->is_confirmed)
                                            <span class="label bg-success">Confirm شده</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @endcan
                                    @if($item->is_confirmed)
                                        <span class="label bg-success">Confirm شده</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($item->user)
                                        <img src="{{ $item->user->avatar }}" title="user" class="img-responsive mw20 ib">
                                        {{ $item->user->full_name }}
                                        <span class="label bg-info">ID: {{ $item->user->id }}</span>
                                        @if($item->user->national_code)
                                            <br>
                                            <span class="label bg-info">کد ملی: {{ $item->user->national_code }}</span>
                                        @endif
                                        @php
                                            $medicalRecordNumber = $item->user->medical_record_number
                                                ?: ($detail[$appointmentUserNameSpace::MEDICAL_RECORD_NUMBER] ?? '');
                                        @endphp
                                        @if(!empty($medicalRecordNumber))
                                            <br>
                                            <span class="label bg-primary">شماره پرونده: {{ $medicalRecordNumber }}</span>
                                        @endif
                                        @if(!empty($detail[$appointmentUserNameSpace::IDENTIFIERـCODE]))
                                            <br>
                                            <span class="label bg-danger">معرف: {{ $detail[$appointmentUserNameSpace::IDENTIFIERـCODE] }}</span>
                                        @endif
                                    @else
                                        <span class="text-danger">این کاربر حذف شده است</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ optional($item->user)->mobile ?: '-' }}
                                    @if(!empty($detail[$appointmentUserNameSpace::SECOND_MOBILE]))
                                        <br><span class="text-danger">شماره دوم: {{ $detail[$appointmentUserNameSpace::SECOND_MOBILE] }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ optional($item->appointmentPart)->title ?: '-' }}
                                    @if(is_array($item->r_part) && count($item->r_part) > 0 && is_assoc($item->r_part))
                                        - [
                                        @foreach($item->r_part as $partName => $partTime)
                                            {{ $partName }}@unless($loop->last)-@endunless
                                        @endforeach
                                        ]
                                    @endif
                                </td>
                                <td class="text-center">{!! optional($item->doctor)->full_name ?: '<span style="color: red;">یافت نشد</span>' !!}</td>
                                <td class="text-center">{{ jdate('l j F Y', jmktime(0, 0, 0, $item->month, $item->day, $item->year)) }}</td>
                                <td class="text-center">
                                    <span class="confirmed-detail-value">{{ $confirmedTimeFrom }}</span>
                                    @can('confirm_admin_appointment_user')
                                        <a href="javascript:void(0)"
                                           class="confirmed-edit-btn btn-edit-confirmed-list-details"
                                           title="ویرایش"
                                           data-action="{{ route('admin.appointment_user.confirmed_list_details.update', $item->id) }}"
                                           data-time="{{ $confirmedTimeFrom }}"
                                           data-service="{{ e($confirmedServiceName) }}"
                                           data-comment="{{ e($item->confirmed_comment) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endcan
                                    @if($isTimeChanged)
                                        <span class="label bg-warning">ساعت تغییر کرده</span>
                                        <br>
                                        <small class="text-muted">اصلی: {{ $originalTimeFrom }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="confirmed-detail-value">{{ $confirmedServiceName ?: '-' }}</span>
                                    @can('confirm_admin_appointment_user')
                                        <a href="javascript:void(0)"
                                           class="confirmed-edit-btn btn-edit-confirmed-list-details"
                                           title="ویرایش"
                                           data-action="{{ route('admin.appointment_user.confirmed_list_details.update', $item->id) }}"
                                           data-time="{{ $confirmedTimeFrom }}"
                                           data-service="{{ e($confirmedServiceName) }}"
                                           data-comment="{{ e($item->confirmed_comment) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endcan
                                    @if($isServiceChanged)
                                        <span class="label bg-warning">سرویس تغییر کرده</span>
                                        <br>
                                        <small class="text-muted">اصلی: {{ $originalServiceName ?: '-' }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="confirmed-comment-preview">{{ $item->confirmed_comment ?: '-' }}</span>
                                    @can('confirm_admin_appointment_user')
                                        <a href="javascript:void(0)"
                                           class="confirmed-edit-btn btn-edit-confirmed-list-details"
                                           title="ویرایش"
                                           data-action="{{ route('admin.appointment_user.confirmed_list_details.update', $item->id) }}"
                                           data-time="{{ $confirmedTimeFrom }}"
                                           data-service="{{ e($confirmedServiceName) }}"
                                           data-comment="{{ e($item->confirmed_comment) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endcan
                                </td>
                                <td class="text-center">{{ optional($item->confirmedBy)->full_name ?: '-' }}</td>
                                <td class="text-center">
                                    @if($item->confirmed_at)
                                        {{ jdate('H:i | Y/m/d', $item->confirmed_at->timestamp, '', '', 'en') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center p30">موردی برای این تاریخ پیدا نشد.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    @if(sizeof($mAppointmentUser))
                        <center class="rtl">{{ $mAppointmentUser->links() }}</center>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2">
                    <a href="{{ request()->fullUrlWithQuery(['export' => '1']) }}" target="_blank">
                        <button type="button" class="btn btn-alert btn-block">دانلود خروجی اکسل</button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div id="modal-confirmed-list-details" class="popup-basic admin-form mfp-with-anim mfp-hide rtl yekan">
        <div class="panel yekan rtl">
            <div class="panel-heading text-right">
                <span class="panel-title">ویرایش جزئیات لیست تایید شده‌ها</span>
            </div>

            <form method="post" id="confirmed-list-details-form">
                @csrf
                @method('PUT')
                <div class="panel-body p25">
                    <div class="section row">
                        <div class="col-md-6 pull-right">
                            <label class="field prepend-icon">
                                <input type="text" name="confirmed_time_from" id="confirmed_modal_time"
                                       class="gui-input confirmed-time-input" placeholder="ساعت">
                                <label class="field-icon"><i class="fa fa-clock-o"></i></label>
                            </label>
                        </div>
                        <div class="col-md-6 pull-right">
                            <label class="field prepend-icon">
                                <input type="text" name="confirmed_service_name" id="confirmed_modal_service"
                                       class="gui-input" placeholder="نام خدمت / سرویس">
                                <label class="field-icon"><i class="glyphicons glyphicons-tie"></i></label>
                            </label>
                        </div>
                    </div>

                    <div class="section">
                        <label class="field prepend-icon">
                            <textarea name="confirmed_comment" id="confirmed_modal_comment"
                                      class="gui-textarea confirmed-comment-textarea" placeholder="کامنت"></textarea>
                            <label class="field-icon"><i class="fa fa-comment"></i></label>
                        </label>
                    </div>
                </div>

                <div class="panel-footer text-left">
                    <button type="submit" class="btn btn-success">ذخیره</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-confirm-appointment" class="popup-basic admin-form mfp-with-anim mfp-hide rtl yekan">
        <div class="panel yekan rtl">
            <div class="panel-heading text-right">
                <span class="panel-title">تأیید ساعت نوبت و ارسال پیامک</span>
            </div>
            <div class="panel-body p25 text-right">
                <p class="mb20">ساعت نوبت را بررسی کنید؛ در صورت نیاز می‌توانید آن را تغییر دهید.</p>
                <div class="section">
                    <label class="field prepend-icon">
                        <input type="text" id="confirm_appointment_time"
                               class="gui-input confirmed-time-input" placeholder="ساعت نوبت">
                        <label class="field-icon"><i class="fa fa-clock-o"></i></label>
                    </label>
                </div>
                <div class="section mt20">
                    <label class="option option-success">
                        <input type="checkbox" id="confirm_send_sms" checked>
                        <span class="checkbox"></span> ارسال پیامک تأیید نوبت
                    </label>
                </div>
                <div id="confirm_sms_recipients" class="mt15">
                    <label class="option option-primary">
                        <input type="checkbox" id="confirm_sms_primary" checked>
                        <span class="checkbox"></span>
                        شماره اصلی: <span id="confirm_primary_mobile" dir="ltr"></span>
                    </label>
                    <label class="option option-primary mt10" id="confirm_sms_second_row">
                        <input type="checkbox" id="confirm_sms_second">
                        <span class="checkbox"></span>
                        شماره دوم: <span id="confirm_second_mobile" dir="ltr"></span>
                    </label>
                </div>
            </div>
            <div class="panel-footer text-left">
                <button type="button" class="btn btn-default" id="cancel_confirm_appointment">انصراف</button>
                <button type="button" class="btn btn-success" id="submit_confirm_appointment">
                    <i class="fa fa-check ml5"></i> Confirm نوبت
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <style>
        .confirmed-search-panel {
            border: 0;
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(40, 55, 75, .09);
            overflow: hidden;
        }

        .confirmed-search-heading {
            align-items: center;
            background: linear-gradient(135deg, #fff 0%, #f6f9fc 100%);
            border-bottom: 0;
            display: flex;
            justify-content: space-between;
            min-height: 86px;
            padding: 17px 20px !important;
        }

        .confirmed-search-title .panel-title {
            display: block;
            float: none;
            font-size: 18px;
            margin-bottom: 7px;
        }

        .confirmed-search-title small {
            color: #8391a5;
            display: block;
            font-size: 12px;
        }

        .confirmed-search-actions {
            align-items: center;
            display: flex;
            gap: 10px;
        }

        .confirmed-back-button,
        .confirmed-advanced-button {
            align-items: center;
            border: 0;
            border-radius: 7px;
            display: inline-flex;
            font-family: inherit;
            font-size: 13px;
            gap: 8px;
            min-height: 42px;
            padding: 10px 16px;
            transition: all .2s ease;
        }

        .confirmed-back-button {
            background: #eef2f7;
            color: #526174;
        }

        .confirmed-back-button:hover,
        .confirmed-back-button:focus {
            background: #dfe7f0;
            color: #344052;
            transform: translateY(-1px);
        }

        .confirmed-advanced-button {
            background: linear-gradient(135deg, #4a89dc, #3575c7);
            box-shadow: 0 4px 10px rgba(74, 137, 220, .24);
            color: #fff;
            cursor: pointer;
        }

        .confirmed-advanced-button:hover,
        .confirmed-advanced-button:focus {
            box-shadow: 0 6px 14px rgba(74, 137, 220, .32);
            color: #fff;
            transform: translateY(-1px);
        }

        .confirmed-filter-count {
            background: #fff;
            border-radius: 12px;
            color: #3575c7;
            font-size: 11px;
            min-width: 20px;
            padding: 2px 6px;
            text-align: center;
        }

        .confirmed-filter-chevron {
            font-size: 10px;
            transition: transform .25s ease;
        }

        .confirmed-advanced-button.is-open .confirmed-filter-chevron {
            transform: rotate(180deg);
        }

        .confirmed-filter-body {
            background: #fff;
            border-top: 1px solid #edf1f5;
            padding: 22px !important;
        }

        .confirmed-part-hint {
            color: #8a97a8;
            display: block;
            margin-top: 6px;
        }

        .confirmed-results-summary {
            background: #f8fafc;
            border-top: 1px solid #edf1f5;
            color: #6b788b;
            padding: 11px 20px;
            text-align: right;
        }

        .confirmed-results-summary i,
        .confirmed-results-summary strong {
            color: #4a89dc;
            margin-left: 5px;
        }

        .confirmed-results-summary span {
            background: #e8f2ff;
            border-radius: 12px;
            color: #3575c7;
            font-size: 11px;
            margin-right: 8px;
            padding: 3px 9px;
        }

        @media (max-width: 767px) {
            .confirmed-search-heading,
            .confirmed-search-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .confirmed-search-actions {
                margin-top: 15px;
                width: 100%;
            }

            .confirmed-back-button,
            .confirmed-advanced-button {
                justify-content: center;
                width: 100%;
            }
        }

        .confirmed-time-input {
            min-width: 78px;
            text-align: center;
            direction: ltr;
        }

        .confirmed-comment-textarea {
            min-height: 56px;
        }

        .confirmed-detail-value {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 4px;
            background: #f5f7fa;
            color: #444;
            min-width: 58px;
        }

        .confirmed-comment-preview {
            display: inline-block;
            max-width: 190px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
        }

        .confirmed-edit-btn {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            margin-right: 5px;
            border-radius: 50%;
            color: #fff;
            background: #4a89dc;
            text-align: center;
            vertical-align: middle;
        }

        .confirmed-edit-btn:hover,
        .confirmed-edit-btn:focus {
            color: #fff;
            background: #2e6da4;
        }
    </style>
    <script>
        jQuery(document).ready(function () {
            jQuery('#toggle_confirmed_filters').on('click', function () {
                const button = jQuery(this);
                const filters = jQuery('#confirmed_advanced_filters');
                const willOpen = !button.hasClass('is-open');

                button.toggleClass('is-open', willOpen)
                    .attr('aria-expanded', willOpen ? 'true' : 'false');
                filters.stop(true, true).slideToggle(240);
            });

            const confirmDate = jQuery('#confirm_date_picker');
            const selectedDate = confirmDate.data('selected-date') || '';
            const selectedDateParts = String(selectedDate).split('/');

            confirmDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_confirm_date',
                initialValue: false
            });

            if (selectedDateParts.length === 3) {
                confirmDate.pDatepicker('setDate', [
                    parseInt(selectedDateParts[0], 10),
                    parseInt(selectedDateParts[1], 10),
                    parseInt(selectedDateParts[2], 10)
                ]);
                confirmDate.val(selectedDate);
            }
            confirmDate.mask('9999/99/99');
            jQuery('.confirmed-time-input').mask('99:99');
            jQuery('#confirm_appointment_time').mask('99:99');

            jQuery('.confirmed-number-filter').on('input', function () {
                const input = jQuery(this);
                const value = String(input.val() || '')
                    .replace(/[۰-۹]/g, function (digit) {
                        return String(digit.charCodeAt(0) - 1776);
                    })
                    .replace(/[٠-٩]/g, function (digit) {
                        return String(digit.charCodeAt(0) - 1632);
                    });
                input.val(value);
            });

            jQuery(document).on('click', '.btn-edit-confirmed-list-details', function () {
                const button = jQuery(this);
                const form = jQuery('#confirmed-list-details-form');

                form.attr('action', button.data('action'));
                jQuery('#confirmed_modal_time').val(button.attr('data-time') || '');
                jQuery('#confirmed_modal_service').val(button.attr('data-service') || '');
                jQuery('#confirmed_modal_comment').val(button.attr('data-comment') || '');

                jQuery.magnificPopup.open({
                    removalDelay: 300,
                    items: {
                        src: '#modal-confirmed-list-details'
                    },
                    midClick: true
                });
            });

            let pendingConfirmCheckbox = null;

            jQuery('.confirm-check').on('change', function () {
                const checkbox = jQuery(this);

                if (checkbox.is(':checked')) {
                    pendingConfirmCheckbox = checkbox;
                    const primaryMobile = String(checkbox.attr('data-primary-mobile') || '').trim();
                    const secondMobile = String(checkbox.attr('data-second-mobile') || '').trim();

                    jQuery('#confirm_appointment_time').val(checkbox.attr('data-time') || '');
                    jQuery('#confirm_primary_mobile').text(primaryMobile || '-');
                    jQuery('#confirm_second_mobile').text(secondMobile || '-');
                    jQuery('#confirm_sms_primary').prop('checked', primaryMobile.length > 0);
                    jQuery('#confirm_sms_second').prop('checked', false);
                    jQuery('#confirm_sms_second_row').toggle(secondMobile.length > 0);
                    jQuery('#confirm_send_sms').prop('checked', true).trigger('change');

                    jQuery.magnificPopup.open({
                        removalDelay: 300,
                        closeOnBgClick: false,
                        showCloseBtn: false,
                        items: { src: '#modal-confirm-appointment' }
                    });
                    return;
                }

                if (!confirm('آیا از برداشتن Confirm این نوبت مطمئن هستید؟')) {
                    checkbox.prop('checked', true);
                    return;
                }

                checkbox.get(0).form.submit();
            });

            jQuery('#confirm_send_sms').on('change', function () {
                jQuery('#confirm_sms_recipients').toggle(jQuery(this).is(':checked'));
            });

            jQuery('#cancel_confirm_appointment').on('click', function () {
                if (pendingConfirmCheckbox) {
                    pendingConfirmCheckbox.prop('checked', false);
                }
                pendingConfirmCheckbox = null;
                jQuery.magnificPopup.close();
            });

            jQuery('#submit_confirm_appointment').on('click', function () {
                if (!pendingConfirmCheckbox) {
                    return;
                }

                const timeFrom = String(jQuery('#confirm_appointment_time').val() || '').trim();
                if (!/^([01][0-9]|2[0-3]):[0-5][0-9]$/.test(timeFrom)) {
                    alert('لطفاً ساعت نوبت را به‌صورت صحیح، مانند 14:30 وارد کنید.');
                    jQuery('#confirm_appointment_time').focus();
                    return;
                }

                const shouldSendSms = jQuery('#confirm_send_sms').is(':checked');
                if (shouldSendSms && !jQuery('#confirm_sms_primary').is(':checked') && !jQuery('#confirm_sms_second').is(':checked')) {
                    alert('برای ارسال پیامک حداقل یک شماره را انتخاب کنید.');
                    return;
                }

                const form = jQuery(pendingConfirmCheckbox.get(0).form);
                form.append(jQuery('<input>', { type: 'hidden', name: 'confirmed_time_from', value: timeFrom }));
                form.append(jQuery('<input>', { type: 'hidden', name: 'send_confirmation_sms', value: shouldSendSms ? '1' : '0' }));
                form.append(jQuery('<input>', { type: 'hidden', name: 'send_sms_to_primary_mobile', value: jQuery('#confirm_sms_primary').is(':checked') ? '1' : '0' }));
                form.append(jQuery('<input>', { type: 'hidden', name: 'send_sms_to_second_mobile', value: jQuery('#confirm_sms_second').is(':checked') ? '1' : '0' }));
                form.get(0).submit();
            });
        });
    </script>
@endpush
