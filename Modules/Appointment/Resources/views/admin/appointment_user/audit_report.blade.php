@extends('admin.layout.layout')
@section('title', 'گزارش جامع نوبت')

@push('style')
<style>
    .audit-page{direction:rtl;color:#34495e}.audit-lock{max-width:480px;margin:65px auto;background:#fff;border-radius:18px;padding:35px;box-shadow:0 15px 45px rgba(31,45,61,.14);text-align:center}.audit-lock-icon{width:78px;height:78px;margin:0 auto 18px;border-radius:50%;line-height:78px;font-size:32px;color:#fff;background:linear-gradient(135deg,#2c7be5,#6658dd)}.audit-lock h2{margin:0 0 8px;color:#263445}.audit-lock p{color:#8795a8;margin-bottom:22px}.audit-lock-message{display:none;margin-top:15px;padding:10px;border-radius:7px}.audit-search,.audit-card{border:0;border-radius:14px;box-shadow:0 7px 25px rgba(31,45,61,.08);overflow:hidden}.audit-search .panel-heading{padding:22px 25px;background:linear-gradient(135deg,#2c7be5,#6658dd);color:#fff}.audit-search .panel-title{color:#fff;font-size:20px}.audit-search small{display:block;margin-top:7px;opacity:.85}.audit-search .panel-body{padding:24px}.audit-hero{padding:22px;color:#fff;border-radius:14px;margin:20px 0;background:linear-gradient(135deg,#263859,#4062bb);box-shadow:0 10px 30px rgba(38,56,89,.2)}.audit-hero h2{color:#fff;margin:0 0 8px}.audit-hero p{margin:0;opacity:.85}.audit-badge{display:inline-block;padding:7px 13px;border-radius:20px;margin:4px;font-size:13px;background:rgba(255,255,255,.16)}.audit-danger{background:#e74c3c}.audit-success{background:#2ecc71}.audit-card{background:#fff;border:1px solid #e8edf3;margin-bottom:18px}.audit-card-head{padding:15px 18px;border-bottom:1px solid #edf1f5;font-size:16px;font-weight:bold;background:#fbfcfe}.audit-card-head i{margin-left:8px;color:#2c7be5}.audit-card-body{padding:18px}.audit-copy{width:100%;min-height:315px;resize:vertical;padding:16px;border:1px solid #dce3ec;border-radius:9px;background:#f8fafc;color:#263445;font:14px/2 Tahoma,Arial,sans-serif;direction:rtl;text-align:right}.audit-copy-result{display:none;margin-right:10px;color:#27ae60;font-weight:bold}.audit-grid{display:flex;flex-wrap:wrap;margin:-7px}.audit-field{width:33.333%;padding:7px;box-sizing:border-box}.audit-field-in{min-height:72px;padding:12px 14px;border-radius:9px;background:#f7f9fc;border:1px solid #edf1f5}.audit-label{color:#8795a8;font-size:12px;margin-bottom:6px}.audit-value{color:#263445;font-size:14px;font-weight:bold;word-break:break-word}.audit-line{padding:9px 0;line-height:1.9;border-bottom:1px dashed #e3e8ef}.audit-line:last-child{border:0}.audit-timeline{position:relative;padding-right:29px}.audit-timeline:before{content:'';position:absolute;top:4px;bottom:5px;right:8px;width:2px;background:#dfe6ef}.audit-log{position:relative;margin-bottom:18px;border:1px solid #e6ebf1;border-radius:11px;background:#fff}.audit-log:before{content:'';position:absolute;right:-27px;top:18px;width:12px;height:12px;border:3px solid #fff;border-radius:50%;background:#2c7be5;box-shadow:0 0 0 2px #2c7be5}.audit-log-head{padding:13px 15px;background:#f8fafc;border-bottom:1px solid #edf1f5}.audit-log-title{font-weight:bold;color:#263445}.audit-log-time{float:left;color:#7d8998;font-size:12px}.audit-log-body{padding:14px 15px}.audit-description{white-space:pre-line;line-height:2}.audit-meta{margin-top:10px;color:#697789;font-size:12px;line-height:1.9}.audit-json{direction:ltr;text-align:left;max-height:350px;overflow:auto;padding:13px;border-radius:7px;background:#1f2937;color:#d1fae5;font:12px/1.75 monospace;white-space:pre-wrap;word-break:break-word}.audit-details{margin-top:10px}.audit-details summary{cursor:pointer;color:#2c7be5;outline:none}.audit-raw{width:100%}.audit-raw td{padding:9px;border-bottom:1px solid #edf1f5;vertical-align:top;word-break:break-word}.audit-key{width:24%;color:#778597;direction:ltr;text-align:left}.audit-empty{padding:30px;text-align:center;color:#8795a8}@media(max-width:991px){.audit-field{width:50%}}@media(max-width:600px){.audit-field{width:100%}.audit-log-time{float:none;display:block;margin-top:6px}}
</style>
@endpush

@section('content')
@inject('appointmentUserClass', 'Modules\Appointment\Entities\AppointmentUser')
<section class="table-layout" id="content"><div class="tray tray-center p25 va-t posr yekan audit-page">
    @if(!$unlocked)
    <div class="audit-lock admin-form">
        <div class="audit-lock-icon"><i class="fa fa-lock"></i></div>
        <h2>ورود به گزارش محرمانه</h2>
        <p>برای مشاهده اطلاعات نوبت‌ها، کد پنج‌رقمی ورود را وارد کنید.</p>
        <form id="audit-unlock-form" method="post" action="{{ route('admin.appointment_user.audit_report.unlock') }}">
            {{ csrf_field() }}
            <label class="field prepend-icon"><input type="password" name="password" class="gui-input text-center" inputmode="numeric" maxlength="5" pattern="[0-9]{5}" autocomplete="off" autofocus placeholder="کد ۵ رقمی"><label class="field-icon"><i class="fa fa-key"></i></label></label>
            <button type="submit" class="button btn-primary btn-block mt15" id="audit-unlock-button"><i class="fa fa-unlock ml5"></i> تأیید و ورود</button>
            <div id="audit-lock-message" class="audit-lock-message"></div>
        </form>
    </div>
    @else
    <div class="panel audit-search">
        <div class="panel-heading"><span class="panel-title"><i class="fa fa-shield ml10"></i> گزارش جامع و محرمانه نوبت</span><small>شماره نوبت را وارد کنید تا اطلاعات کامل و تمام تاریخچه فعالیت‌ها نمایش داده شود.</small></div>
        <div class="panel-body admin-form"><form method="get" action="{{ route('admin.appointment_user.audit_report') }}"><div class="row">
            <div class="col-md-9 pull-right"><label class="field prepend-icon"><input type="text" name="appointment_id" value="{{ request('appointment_id') }}" class="gui-input" inputmode="numeric" autocomplete="off" autofocus placeholder="مثلاً 193525"><label class="field-icon"><i class="fa fa-hashtag"></i></label></label>@if($errors->has('appointment_id'))<p class="text-danger mt5">{{ $errors->first('appointment_id') }}</p>@endif</div>
            <div class="col-md-3 pull-right"><button type="submit" class="button btn-primary btn-block"><i class="fa fa-search ml5"></i> نمایش گزارش کامل</button></div>
        </div></form></div>
    </div>

    @if(request()->filled('appointment_id') && !$appointment)<div class="alert alert-danger text-right"><i class="fa fa-exclamation-triangle ml5"></i> نوبتی با شماره {{ request('appointment_id') }} پیدا نشد.</div>@endif

    @if($appointment)
    @php
        $patient=$appointment->user; $agent=$appointment->agent; $doctor=$appointment->doctor; $part=$appointment->appointmentPart;
        $patientName=$patient?trim($patient->full_name):''; $agentName=$agent?trim($agent->full_name):''; $doctorName=$doctor?trim($doctor->full_name):'';
        $deletedAdmin=$deletionLog?$deletionLog->admin:null; $deletedAdminName=$deletionLog?trim((string)$deletionLog->admin_name):'';
        $createdTs=$appointment->created_at?strtotime((string)$appointment->created_at):null; $updatedTs=$appointment->updated_at?strtotime((string)$appointment->updated_at):null; $deletedTs=$appointment->deleted_at?strtotime((string)$appointment->deleted_at):null;
        $appointmentDate=sprintf('%04d/%02d/%02d',$appointment->year,$appointment->month,$appointment->day);
        $statusMeta=isset($appointmentUserClass::$status[$appointment->status])?$appointmentUserClass::$status[$appointment->status]:null;
        $kindMeta=isset($appointmentUserClass::$kinds[(int)$appointment->kind])?$appointmentUserClass::$kinds[(int)$appointment->kind]:null;
        $visitMeta=isset($appointmentUserClass::$visitStatus[$appointment->visit])?$appointmentUserClass::$visitStatus[$appointment->visit]:null;
        $detail=is_array($appointment->detail)?$appointment->detail:[]; $relatedParts=is_array($appointment->r_part)?$appointment->r_part:[];
        $unknown='نامشخص';
        $relatedPartNames=[];
        foreach($relatedParts as $relatedPartName=>$relatedPartTime){
            if(!is_int($relatedPartName) && trim((string)$relatedPartName)!=='') $relatedPartNames[]=trim((string)$relatedPartName);
        }
        $appointmentPartTitle=!empty($relatedPartNames)?implode(' - ',$relatedPartNames):($part?$part->title:$unknown);
        $copyLines=[
            'نوبت '.$appointment->id.' به نام '.($patientName!==''?$patientName:$unknown).' با شماره موبایل '.($patient&&$patient->mobile?$patient->mobile:$unknown).' بود.',
            'این نوبت توسط '.($agentName!==''?$agentName:$unknown).'، کاربر '.($appointment->agent_id?:$unknown).' با موبایل '.($agent&&$agent->mobile?$agent->mobile:$unknown).' ثبت شده بود.',
            'بخش نوبت: '.$appointmentPartTitle,
            'تاریخ ثبت نوبت: '.($createdTs?jdate('Y/m/d',$createdTs,'','','en'):$unknown),
            'ساعت ثبت نوبت: '.($createdTs?jdate('H:i:s',$createdTs,'','','en'):$unknown),
            'تاریخ نوبت: '.$appointmentDate,
            'ساعت نوبت: '.(substr((string)$appointment->time_from,0,5)?:$unknown),
            'حذف‌کننده: '.($deletedAdminName!==''?$deletedAdminName:$unknown).'، کاربر '.($deletionLog&&$deletionLog->admin_id?$deletionLog->admin_id:$unknown).' با موبایل '.($deletedAdmin&&$deletedAdmin->mobile?$deletedAdmin->mobile:$unknown),
            'تاریخ حذف: '.($deletedTs?jdate('Y/m/d',$deletedTs,'','','en'):'حذف نشده'),
            'ساعت حذف: '.($deletedTs?jdate('H:i:s',$deletedTs,'','','en'):'حذف نشده'),
            'IP حذف‌کننده: '.($deletionLog&&$deletionLog->ip?$deletionLog->ip:$unknown),
            'روش حذف: '.($deletionLog?'پنل مدیریت':$unknown),
        ];
        $copyText=implode("\n",$copyLines);
    @endphp

    <div class="audit-hero"><div class="row"><div class="col-md-7 pull-right"><h2>نوبت شماره {{ $appointment->id }}</h2><p>{{ $patientName!==''?$patientName:'زیباجوی نامشخص' }} — {{ $patient?$patient->mobile:'شماره نامشخص' }}</p></div><div class="col-md-5 text-left"><span class="audit-badge {{ $appointment->deleted_at?'audit-danger':'audit-success' }}">{{ $statusMeta?$statusMeta['title']:'وضعیت نامشخص' }}</span><span class="audit-badge">{{ $kindMeta?$kindMeta['title']:'نوع نامشخص' }}</span><span class="audit-badge">{{ count($activityLogs) }} لاگ</span></div></div></div>

    <div class="audit-card"><div class="audit-card-head"><i class="fa fa-copy"></i> متن آماده کپی</div><div class="audit-card-body">
        <textarea id="appointment-audit-copy" class="audit-copy" readonly>{{ $copyText }}</textarea>
        <button type="button" id="appointment-audit-copy-button" class="button btn-primary mt10"><i class="fa fa-copy ml5"></i> کپی کردن متن</button><span id="appointment-audit-copy-result" class="audit-copy-result">متن کپی شد.</span>
    </div></div>

    <div class="row">
        <div class="col-md-6 pull-right"><div class="audit-card"><div class="audit-card-head"><i class="fa fa-user"></i> زیباجو و ثبت‌کننده</div><div class="audit-card-body"><div class="audit-grid">
        @foreach([['نام زیباجو',$patientName!==''?$patientName:'نامشخص'],['شناسه زیباجو',$appointment->user_id?:'نامشخص'],['موبایل زیباجو',$patient?$patient->mobile:'نامشخص'],['ایمیل زیباجو',$patient?$patient->email:'نامشخص'],['نام ثبت‌کننده',$agentName!==''?$agentName:'نامشخص'],['شناسه ثبت‌کننده',$appointment->agent_id?:'نامشخص'],['موبایل ثبت‌کننده',$agent?$agent->mobile:'نامشخص'],['ایمیل ثبت‌کننده',$agent?$agent->email:'نامشخص']] as $field)
            <div class="audit-field"><div class="audit-field-in"><div class="audit-label">{{ $field[0] }}</div><div class="audit-value">{{ $field[1] }}</div></div></div>
        @endforeach
        </div></div></div></div>
        <div class="col-md-6 pull-right"><div class="audit-card"><div class="audit-card-head"><i class="fa fa-calendar"></i> زمان و محل نوبت</div><div class="audit-card-body"><div class="audit-grid">
        @foreach([['بخش',$appointmentPartTitle],['شناسه بخش',$appointment->appointment_part_id?:'نامشخص'],['پزشک',$doctorName!==''?$doctorName:'نامشخص'],['شناسه پزشک',$appointment->doctor_id?:'نامشخص'],['موبایل پزشک',$doctor?$doctor->mobile:'نامشخص'],['تاریخ نوبت',$appointmentDate],['ساعت شروع',substr((string)$appointment->time_from,0,5)?:'نامشخص'],['ساعت پایان',substr((string)$appointment->time_to,0,5)?:'نامشخص'],['IP ثبت نوبت',$appointment->ip?:'نامشخص']] as $field)
            <div class="audit-field"><div class="audit-field-in"><div class="audit-label">{{ $field[0] }}</div><div class="audit-value">{{ $field[1] }}</div></div></div>
        @endforeach
        </div></div></div></div>
    </div>

    <div class="audit-card"><div class="audit-card-head"><i class="fa fa-info-circle"></i> مشخصات کامل نوبت</div><div class="audit-card-body"><div class="audit-grid">
    @foreach([['کد نوبت',$appointment->code?:'نامشخص'],['وضعیت',$statusMeta?$statusMeta['title']:$appointment->status],['نوع نوبت',$kindMeta?$kindMeta['title']:$appointment->kind],['نوع مراجعه',$appointment->type?:'نامشخص'],['وضعیت مراجعه',$visitMeta?$visitMeta['title']:$appointment->visit],['هزینه',number_format((float)$appointment->cost)],['درصد',$appointment->percent],['شناسه تراکنش',$appointment->transaction_id?:'ندارد'],['تأیید شده',$appointment->is_confirmed?'بله':'خیر'],['تأییدکننده',$appointment->confirmedBy?trim($appointment->confirmedBy->full_name):'ندارد'],['ساعت تأییدشده',$appointment->confirmed_time_from?:'ندارد'],['خدمت تأییدشده',$appointment->confirmed_service_name?:'ندارد']] as $field)
        <div class="audit-field"><div class="audit-field-in"><div class="audit-label">{{ $field[0] }}</div><div class="audit-value">{{ $field[1] }}</div></div></div>
    @endforeach
    </div></div></div>

    <div class="audit-card"><div class="audit-card-head"><i class="fa fa-clock-o"></i> زمان‌های سیستمی و حذف</div><div class="audit-card-body"><div class="audit-grid">
    @foreach([['تاریخ ثبت',$createdTs?jdate('Y/m/d H:i:s',$createdTs,'','','en'):'نامشخص'],['آخرین تغییر',$updatedTs?jdate('Y/m/d H:i:s',$updatedTs,'','','en'):'نامشخص'],['تاریخ حذف',$deletedTs?jdate('Y/m/d',$deletedTs,'','','en'):'حذف نشده'],['ساعت حذف',$deletedTs?jdate('H:i:s',$deletedTs,'','','en'):'حذف نشده'],['حذف‌کننده',$deletedAdminName!==''?$deletedAdminName:'نامشخص'],['شناسه حذف‌کننده',$deletionLog&&$deletionLog->admin_id?$deletionLog->admin_id:'نامشخص'],['موبایل حذف‌کننده',$deletedAdmin?$deletedAdmin->mobile:'نامشخص'],['IP حذف‌کننده',$deletionLog&&$deletionLog->ip?$deletionLog->ip:'نامشخص'],['روش حذف',$deletionLog?($deletionLog->method==='DELETE'?'پنل مدیریت':$deletionLog->method):'نامشخص']] as $field)
        <div class="audit-field"><div class="audit-field-in"><div class="audit-label">{{ $field[0] }}</div><div class="audit-value">{{ $field[1] }}</div></div></div>
    @endforeach
    </div>@if($appointment->deleted_at&&!$deletionLog)<div class="alert alert-warning mt15 mbn">این نوبت پیش از شروع ثبت لاگ مدیریتی حذف شده است؛ هویت و IP حذف‌کننده قابل تشخیص نیست.</div>@endif</div></div>

    <div class="row">
        <div class="col-md-6 pull-right"><div class="audit-card"><div class="audit-card-head"><i class="fa fa-list-alt"></i> جزئیات تکمیلی</div><div class="audit-card-body">
        @forelse($detail as $key=>$value)<div class="audit-line"><strong>{{ $key }}:</strong> {{ is_array($value)?json_encode($value,JSON_UNESCAPED_UNICODE):$value }}</div>@empty<div class="audit-empty">جزئیات تکمیلی ثبت نشده است.</div>@endforelse
        @if(!empty($relatedParts))<div class="audit-line"><strong>بخش‌های مرتبط:</strong> {{ json_encode($relatedParts,JSON_UNESCAPED_UNICODE) }}</div>@endif
        @if($appointment->confirmed_comment)<div class="audit-line"><strong>توضیح تأیید:</strong> {{ $appointment->confirmed_comment }}</div>@endif
        </div></div></div>
        <div class="col-md-6 pull-right"><div class="audit-card"><div class="audit-card-head"><i class="fa fa-history"></i> تاریخچه کد معرف</div><div class="audit-card-body">
        @forelse($appointment->identifierHistories as $history)
            @php
                $identifierAction=['added'=>'افزودن معرف','updated'=>'ویرایش معرف','removed'=>'حذف معرف'];
                $historyActorName=$history->changed_by_name?:($history->actor?trim($history->actor->full_name):'نامشخص');
                $historyActorMobile=$history->changed_by_mobile?:($history->actor?$history->actor->mobile:null);
            @endphp
            <div class="audit-line"><strong>{{ $identifierAction[$history->action]??$history->action }}</strong> — از «{{ $history->old_value?:'خالی' }}» به «{{ $history->new_value?:'خالی' }}»<br><small class="text-muted">توسط {{ $historyActorName }}@if($history->changed_by)، کاربر {{ $history->changed_by }}@endif @if($historyActorMobile)با موبایل {{ $historyActorMobile }}@endif در {{ jdate('Y/m/d H:i:s',strtotime((string)$history->created_at),'','','en') }}@if($history->ip) از IP {{ $history->ip }}@endif</small></div>
        @empty<div class="audit-empty">تاریخچه‌ای ثبت نشده است.</div>@endforelse
        </div></div></div>
    </div>

    <div class="audit-card"><div class="audit-card-head"><i class="fa fa-history"></i> همه لاگ‌های نوبت ({{ count($activityLogs) }})</div><div class="audit-card-body"><div class="audit-timeline">
    @forelse($activityLogs as $log)
        @php $logTs=$log->created_at?strtotime((string)$log->created_at):null; $logAdminName=trim((string)$log->admin_name); @endphp
        <div class="audit-log"><div class="audit-log-head"><span class="audit-log-title">{{ $log->description?:$log->action }}</span><span class="audit-log-time">{{ $logTs?jdate('Y/m/d H:i:s',$logTs,'','','en'):'زمان نامشخص' }}</span><div class="clearfix"></div></div><div class="audit-log-body">
            @if($log->description)<div class="audit-description">{{ $log->description }}</div>@endif
            <div class="audit-meta"><strong>عملیات:</strong> {{ $log->action }} | <strong>کاربر:</strong> {{ $logAdminName!==''?$logAdminName:'نامشخص' }} ({{ $log->admin_id?:'بدون شناسه' }}) | <strong>IP:</strong> {{ $log->ip?:'نامشخص' }} | <strong>متد:</strong> {{ $log->method?:'نامشخص' }} @if($log->route_name)| <strong>Route:</strong> {{ $log->route_name }}@endif</div>
            @if(!empty($log->old_values)||!empty($log->new_values)||!empty($log->request_payload))<details class="audit-details"><summary>نمایش تغییرات و داده‌های این لاگ</summary>@if(!empty($log->old_values))<h5>مقادیر قبل</h5><div class="audit-json">{{ json_encode($log->old_values,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</div>@endif @if(!empty($log->new_values))<h5>مقادیر بعد</h5><div class="audit-json">{{ json_encode($log->new_values,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</div>@endif @if(!empty($log->request_payload))<h5>اطلاعات درخواست</h5><div class="audit-json">{{ json_encode($log->request_payload,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</div>@endif</details>@endif
            @if($log->url)<details class="audit-details"><summary>نشانی و اطلاعات فنی</summary><div class="audit-json">URL: {{ $log->url }}
Controller: {{ $log->controller_action?:'نامشخص' }}
User Agent: {{ $log->user_agent?:'نامشخص' }}
Response: {{ $log->response_status?:'نامشخص' }}</div></details>@endif
        </div></div>
    @empty<div class="audit-empty">برای این نوبت هیچ لاگ مدیریتی ثبت نشده است.</div>@endforelse
    </div></div></div>

    <div class="audit-card"><div class="audit-card-head"><i class="fa fa-database"></i> تمام فیلدهای خام دیتابیس</div><div class="audit-card-body">
        <details class="audit-details"><summary>نمایش تمام ستون‌های رکورد نوبت</summary><table class="audit-raw">@foreach($appointment->getAttributes() as $key=>$value)<tr><td class="audit-key">{{ $key }}</td><td>{{ $value===null?'NULL':$value }}</td></tr>@endforeach</table></details>
        @if($appointment->transaction)<details class="audit-details"><summary>نمایش تمام ستون‌های تراکنش مرتبط</summary><table class="audit-raw">@foreach($appointment->transaction->getAttributes() as $key=>$value)<tr><td class="audit-key">{{ $key }}</td><td>{{ $value===null?'NULL':$value }}</td></tr>@endforeach</table></details>@endif
    </div></div>
    @endif
    @endif
</div></section>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#appointment-audit-copy-button').on('click', function () {
            var field = document.getElementById('appointment-audit-copy');
            field.focus();
            field.select();
            field.setSelectionRange(0, field.value.length);
            var copied = false;
            try { copied = document.execCommand('copy'); } catch (error) { copied = false; }
            if (copied) {
                $('#appointment-audit-copy-result').stop(true, true).fadeIn(150).delay(1800).fadeOut(300);
            }
        });
        $('#audit-unlock-form').on('submit', function (event) {
            event.preventDefault();
            var form = $(this);
            var button = $('#audit-unlock-button');
            var message = $('#audit-lock-message');
            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin ml5"></i> در حال بررسی...');
            message.hide();
            $.ajax({url: form.attr('action'), method: 'POST', data: form.serialize()})
                .done(function () {
                    message.removeClass('alert-danger').addClass('alert-success').text('کد صحیح است؛ در حال ورود...').show();
                    window.location.reload();
                })
                .fail(function (xhr) {
                    var response = xhr.responseJSON || {};
                    var validation = response.errors && response.errors.password ? response.errors.password[0] : null;
                    message.removeClass('alert-success').addClass('alert-danger').text(validation || response.message || 'کد واردشده صحیح نیست.').show();
                    form.find('input[name="password"]').val('').focus();
                    button.prop('disabled', false).html('<i class="fa fa-unlock ml5"></i> تأیید و ورود');
                });
        });
    });
</script>
@endpush
