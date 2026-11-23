@extends('admin.layout.layout')
@section('title', 'اتصال به سامانه ویپ')
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

        <div class="admin-form theme-primary rtl yekan">
            <div class="panel">
                <div class="panel-heading">
                    <span class="panel-title">اتصال به سامانه ویپ</span>
                </div>
                <form method="post" action="{{ route('admin.voip_connection.update') }}">
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="section">
                            <label class="field prepend-icon">
                                <span class="field-label">فعال بودن اتصال</span>
                            </label>
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
                            <small class="text-muted">در حالت غیرفعال، API نوبت‌ها برای پنل ویپ پاسخ نمی‌دهد.</small>
                        </div>

                        <div class="section">
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
                            <small class="text-muted">آدرس پایه پنل ویپ (بدون مسیر). مثال: https://192.168.89.50 یا https://voip.s8n.ir</small>
                        </div>

                        <div class="section">
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
                            <small class="text-muted">نام کاربری ورود به پنل ویپ (tenant)</small>
                        </div>

                        <div class="section">
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
                            <small class="text-muted">برای حفظ رمز فعلی، خالی بگذارید.</small>
                        </div>

                        @if(!empty($public['panel_url']))
                            <div class="section">
                                <a class="btn btn-info btn-sm" href="{{ $public['panel_url'] }}" target="_blank" rel="noopener">
                                    <i class="fa fa-external-link"></i> باز کردن پنل ویپ
                                </a>
                            </div>
                        @endif

                        <div class="section">
                            <p class="text-muted mb5">وضعیت API کامیلو برای ویپ:</p>
                            <ul class="pl15">
                                <li>توکن Bearer: {{ !empty($public['has_token']) ? 'تنظیم شده' : 'تنظیم نشده — php artisan voip:token' }}</li>
                                <li>محدوده پزشک: {{ $public['doctor_id'] ? ('doctor_id=' . $public['doctor_id']) : 'همه پزشکان' }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="button btn-primary">ذخیره تنظیمات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
