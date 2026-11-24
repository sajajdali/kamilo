@extends('admin.layout.layout')
@section('title', 'اتصال به سامانه ویپ')

@push('style')
<style>
    .voip-conn {
        direction: rtl;
        color: #3b4554;
    }
    .voip-conn-wrap {
        max-width: 520px;
        margin: 0;
    }
    .voip-conn .panel {
        border: 1px solid #e4e9f0;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(36, 50, 72, 0.04);
        overflow: hidden;
        background: #fff;
    }
    .voip-conn .panel-heading {
        background: #f7f9fc;
        border-bottom: 1px solid #e8edf3;
        padding: 15px 20px;
    }
    .voip-conn .panel-title {
        font-size: 16px;
        font-weight: 700;
        color: #2a3342;
        margin: 0;
    }
    .voip-conn .panel-heading .voip-conn-lead {
        display: block;
        margin-top: 5px;
        font-size: 12.5px;
        color: #7a8698;
        line-height: 1.7;
    }
    .voip-conn .panel-body {
        padding: 20px 20px 6px;
    }
    .voip-conn .voip-block {
        margin-bottom: 6px;
    }
    .voip-conn .voip-block + .voip-block {
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #eef2f6;
    }
    .voip-conn .voip-block-title {
        display: block;
        margin: 0 0 12px;
        font-size: 12px;
        font-weight: 700;
        color: #6b788b;
        text-transform: none;
    }
    .voip-conn .voip-field {
        margin-bottom: 16px;
    }
    .voip-conn .voip-label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #394456;
    }
    .voip-conn .voip-hint {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #8a95a5;
        line-height: 1.7;
    }
    .voip-conn .voip-input {
        max-width: 100%;
    }
    .voip-conn .gui-input {
        height: 40px;
        border-color: #d7dee8;
        background: #fff;
    }
    .voip-conn .gui-input:focus {
        border-color: #4a89dc;
        background: #fff;
    }
    .voip-conn .field.prepend-icon .field-icon {
        color: #9aa6b5;
    }
    .voip-conn .option-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 20px;
        margin-top: 2px;
    }
    .voip-conn .option-group .option {
        margin: 0;
    }
    .voip-conn .voip-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin: 2px 0 4px;
    }
    .voip-conn .voip-actions .btn {
        border-radius: 4px;
    }
    .voip-conn .voip-status {
        margin: 4px 0 12px;
        padding: 13px 14px;
        background: #f5f8fb;
        border: 1px solid #e3eaf2;
        border-right: 3px solid #4a89dc;
        border-radius: 4px;
    }
    .voip-conn .voip-status-title {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        color: #394456;
    }
    .voip-conn .voip-status-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .voip-conn .voip-status-list li {
        position: relative;
        padding: 4px 18px 4px 0;
        font-size: 13px;
        color: #526074;
        line-height: 1.7;
    }
    .voip-conn .voip-status-list li:before {
        content: "";
        position: absolute;
        right: 0;
        top: 12px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4a89dc;
    }
    .voip-conn .voip-status-ok {
        color: #2f8f5b;
        font-weight: 700;
    }
    .voip-conn .voip-status-warn {
        color: #b7791f;
        font-weight: 700;
    }
    .voip-conn .panel-footer {
        background: #fafbfc;
        border-top: 1px solid #e8edf3;
        padding: 13px 20px;
        text-align: right;
    }
    .voip-conn .panel-footer .button {
        min-width: 140px;
    }
    @media (max-width: 560px) {
        .voip-conn-wrap {
            max-width: 100%;
        }
        .voip-conn .panel-body,
        .voip-conn .panel-heading,
        .voip-conn .panel-footer {
            padding-left: 14px;
            padding-right: 14px;
        }
    }
</style>
@endpush

@section('content')

<header id="topbar" class="ph10">
    <div class="topbar-right">
        <ul class="nav nav-list nav-list-topbar pull-right yekan">
            <li class="active">
                <a href="{{ route('admin.voip_connection.edit') }}">اتصال به سامانه ویپ</a>
            </li>
        </ul>
    </div>
</header>

<section id="content" class="table-layout animated fadeIn">
    <div class="tray tray-center p25 va-t posr">
        @include('admin.layout.alert')
        @if(Session::has('successful'))
            <div class="alert alert-success alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-check pr10"></i>
                {{ Session::get('successful') }}
            </div>
        @endif
        @if (isset($errors) && count($errors) > 0)
            <div class="alert alert-danger alert-dismissable mb30 rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h3 class="mt5">خطا!</h3>
                <ul class="list">
                    @foreach($errors->all() as $err)
                        <li class="liste-item">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-form theme-primary rtl yekan voip-conn">
            <div class="voip-conn-wrap">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">اتصال به سامانه ویپ</span>
                        <span class="voip-conn-lead">تنظیم آدرس و حساب ورود پنل ویپ برای ارتباط با کامیلو.</span>
                    </div>
                    <form method="post" action="{{ route('admin.voip_connection.update') }}">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="voip-block">
                                <span class="voip-block-title">وضعیت اتصال</span>
                                <div class="voip-field">
                                    <span class="voip-label">فعال بودن اتصال</span>
                                    <div class="option-group field">
                                        <label class="option option-success">
                                            <input type="radio" name="voip_panel_enabled" value="1" {{ !empty($settings['enabled']) ? 'checked' : '' }}>
                                            <span class="radio"></span>فعال
                                        </label>
                                        <label class="option option-danger">
                                            <input type="radio" name="voip_panel_enabled" value="0" {{ empty($settings['enabled']) ? 'checked' : '' }}>
                                            <span class="radio"></span>غیرفعال
                                        </label>
                                    </div>
                                    <small class="voip-hint">در حالت غیرفعال، API نوبت‌ها برای پنل ویپ پاسخ نمی‌دهد.</small>
                                </div>
                            </div>

                            <div class="voip-block">
                                <span class="voip-block-title">اطلاعات پنل</span>

                                <div class="voip-field">
                                    <label for="voip_panel_url" class="voip-label">آدرس پایه پنل</label>
                                    <label for="voip_panel_url" class="field prepend-icon">
                                        <input style="direction:ltr;text-align:left"
                                               type="url"
                                               name="voip_panel_url"
                                               id="voip_panel_url"
                                               class="gui-input"
                                               placeholder="https://192.168.89.50"
                                               value="{{ old('voip_panel_url', $settings['panel_url']) }}">
                                        <label for="voip_panel_url" class="field-icon"><i class="fa fa-link"></i></label>
                                    </label>
                                    <small class="voip-hint">آدرس پایه پنل ویپ (بدون مسیر). مثال: https://192.168.89.50 یا https://voip.s8n.ir</small>
                                </div>

                                <div class="voip-field">
                                    <label for="voip_panel_username" class="voip-label">نام کاربری</label>
                                    <label for="voip_panel_username" class="field prepend-icon">
                                        <input style="direction:ltr;text-align:left"
                                               type="text"
                                               name="voip_panel_username"
                                               id="voip_panel_username"
                                               class="gui-input"
                                               placeholder="sina"
                                               autocomplete="username"
                                               value="{{ old('voip_panel_username', $settings['username']) }}">
                                        <label for="voip_panel_username" class="field-icon"><i class="fa fa-user"></i></label>
                                    </label>
                                    <small class="voip-hint">نام کاربری ورود به پنل ویپ (tenant)</small>
                                </div>

                                <div class="voip-field">
                                    <label for="voip_panel_password" class="voip-label">رمز عبور</label>
                                    <label for="voip_panel_password" class="field prepend-icon">
                                        <input style="direction:ltr;text-align:left"
                                               type="password"
                                               name="voip_panel_password"
                                               id="voip_panel_password"
                                               class="gui-input"
                                               placeholder="{{ !empty($settings['password']) ? '•••••••• (خالی = بدون تغییر)' : 'رمز عبور' }}"
                                               autocomplete="new-password"
                                               value="">
                                        <label for="voip_panel_password" class="field-icon"><i class="fa fa-lock"></i></label>
                                    </label>
                                    <small class="voip-hint">برای حفظ رمز فعلی، خالی بگذارید.</small>
                                </div>

                                @if(!empty($public['panel_url']))
                                    <div class="voip-actions">
                                        <a class="btn btn-info btn-sm" href="{{ $public['panel_url'] }}" target="_blank" rel="noopener">
                                            <i class="fa fa-external-link"></i> باز کردن پنل ویپ
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="voip-block">
                                <div class="voip-status">
                                    <p class="voip-status-title">وضعیت API کامیلو برای ویپ</p>
                                    <ul class="voip-status-list">
                                        <li>
                                            توکن Bearer:
                                            @if(!empty($public['has_token']))
                                                <span class="voip-status-ok">تنظیم شده</span>
                                            @else
                                                <span class="voip-status-warn">تنظیم نشده — php artisan voip:token</span>
                                            @endif
                                        </li>
                                        <li>
                                            محدوده پزشک:
                                            {{ $public['doctor_id'] ? ('doctor_id=' . $public['doctor_id']) : 'همه پزشکان' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="button btn-primary">ذخیره تنظیمات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
