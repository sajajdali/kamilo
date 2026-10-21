@extends('admin.layout.layout')
@section('title','پیامک های استاندارد سیستمی')
@section('content')
<!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right rtl yekan">
        <ol class="breadcrumb">
            <li class="crumb-active">
                <a href="{{ route('admin.support.questions.index') }}">پیامک های استاندارد سیستمی</a>
            </li>
            <li class="crumb-icon">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li class="crumb-link">
                <a href="">مدیریت</a>
            </li>
            <li class="crumb-trail">پیامک های استاندارد</li>
        </ol>
    </div>


</header>
<!-- End: Topbar -->
<section id="content" class="table-layout animated fadeIn">
    <!-- begin: .tray-center -->
    <!-- end: .tray-center -->

    <!-- begin: .tray-right -->
    <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">
        <form method="get">
            <!-- menu quick links -->
            <div class="p20 admin-form rtl yekan">

                <h4 class="mt5 text-muted fw500"> فیلتر کردن </h4>

                <hr class="short">

                <h6 class="fw400">نام کاربر</h6>
                <div class="section mb15">
                    <label for="order-id" class="field prepend-icon">
                        <input type="text" value="{{ ((isset($_GET['name'])) ? $_GET['name'] : '') }}" name="name"
                               id="name" class="gui-input yekan" placeholder="نام کاربر">
                        <label for="name" class="field-icon"><i class="fa fa-user"></i>
                        </label>
                    </label>
                </div>


                <hr class="short">


            </div>
        </form>
    </aside>
    <!-- end: .tray-right -->

    <div class="tray tray-center p25 va-t posr">
        @if(Session::has('successful'))
        <div class="alert alert-success alert-dismissable rtl yekan">
            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
            <i class="fa fa-check pr10"></i>
            {{ Session::get('successful') }}
        </div>
        @endif
        @if(Session::has('sys_error'))
        <div class="alert alert-danger alert-dismissable rtl yekan">
            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
            <i class="fa fa-warning pr10"></i>
            {{ Session::get('sys_error') }}
        </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-warning pr10"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- recent orders table -->
        <div class="panel">
            <div class="panel-body pn">
                <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                    <thead>
                    <tr class="bg-light yekan rtl ">
                        <th class="txt_center">ارسال</th>
                        <th class="txt_center">زمان ارسال به کاربر</th>
                        <th class="txt_center">وضعیت</th>
                        <th class="txt_center">متن</th>
                        <th class="txt_center">عنوان پیامک</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(standardSmsMesages() as $sms => $value)
                    <tr>
                        <td class="text-center">
                            @if (isset($appointment['detail']['STANDARD_SMS'][$sms]) && isset($appointment['detail']['STANDARD_SMS'][$sms]['send_at']))
                                -
                            @else
                                <div class="btn-group text-right yekan rtl txt_right">
                                    <button type="button"
                                            class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                            data-toggle="dropdown" aria-expanded="false"> عملیات
                                        <span class="caret mr5"></span>
                                    </button>
                                    <ul class="dropdown-menu txt_right" role="menu">

                                        <li>
                                            <a class="delete_ticket" data-effect="mfp-rotateLeft" data-time="{{$timeFrom}}"
                                               href="{{$sms}}">ارسال پیامک</a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if (isset($appointment['detail']['STANDARD_SMS'][$sms]) && isset($appointment['detail']['STANDARD_SMS'][$sms]['send_at']))
                                {{ jdate('l d F ساعت H:i:s',strtotime($appointment['detail']['STANDARD_SMS'][$sms]['send_at'])) }}
                            @else
                                -
                            @endif
                        </td>
                        <td> @if (isset($appointment['detail']['STANDARD_SMS'][$sms]))
                                <span class="label label-success">ارسال شده</span>
                            @else
                                <span class="label label-info">ارسال نشده</span>
                            @endif</td>
{{--                        <td class="txt_center yekan">{{ jdate('l d F',strtotime($question->created_at)) }}</td>--}}
                        <td class="txt_center yekan" style="direction: rtl">
                            {!!  nl2br($value)!!}
                        </td>
                        <td class="txt_center yekan rtl">
                            {{$sms}}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

</section>
<!-- Panel popup -->
<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

    <form id="standard_sms_form" action="{{route('admin.standard_sms.send' , $appointment)}}" method="POST" >
        @csrf
        <div class="panel">
            <input id="delete_link" name="template" type="hidden" value="25">
            <input id="send_sms_to_primary_mobile" name="send_sms_to_primary_mobile" type="hidden" value="1">
            <input id="send_sms_to_second_mobile" name="send_sms_to_second_mobile" type="hidden" value="0">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-envelope-o"></i>
                            </span>
                <span class="panel-title">انتخاب شماره‌های دریافت‌کننده پیامک</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <p class="mb20" style="font-size: 15px;">پیامک به کدام شماره‌های کاربر ارسال شود؟</p>

                <label for="standard_sms_primary_checkbox" class="sms-recipient-row">
                    <input type="checkbox" id="standard_sms_primary_checkbox" checked>
                    <span class="sms-recipient-check"><i class="fa fa-check"></i></span>
                    <span class="sms-recipient-info">
                        <strong>شماره اصلی کاربر</strong>
                        <span dir="ltr">{{ optional($appointment->user)->mobile }}</span>
                    </span>
                </label>

                @if(!empty($appointment->detail[$appointment::SECOND_MOBILE] ?? optional($appointment->user)->second_mobile))
                    <label for="standard_sms_second_checkbox" class="sms-recipient-row">
                        <input type="checkbox" id="standard_sms_second_checkbox">
                        <span class="sms-recipient-check"><i class="fa fa-check"></i></span>
                        <span class="sms-recipient-info">
                            <strong>شماره دوم کاربر</strong>
                            <span dir="ltr">{{ $appointment->detail[$appointment::SECOND_MOBILE] ?? optional($appointment->user)->second_mobile }}</span>
                        </span>
                    </label>
                @endif

                <div class="form-group">
                    <div class="col-lg-8">
                        <input id="time_from" name="time_from" type="text" class="form-control"
                               value="{{$timeFrom}}" required pattern="([01][0-9]|2[0-3]):[0-5][0-9]"
                               placeholder="مثلاً 10:00" dir="ltr">
                    </div>
                    <label for="inputStandard" class="col-lg-3 control-label">زمان حضور</label>
                </div>

            </div>
            <div class="panel-footer text-left">
                <button class="btn btn-success" id="submit_standard_sms" type="submit">
                    <i class="fa fa-paper-plane ml5"></i> ارسال پیامک
                </button>

            </div>
        </div>

    </form>

</div>
<!-- End: Content -->


<!-- Panel popup -->
<div id="modal-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

    <div class="panel">
        <div class="panel-heading" style="text-align: right">
    <span class="panel-icon"><i class="fa fa-pencil"></i>
    </span>
            <span class="panel-title">تیکت</span>
        </div>
        <div class="panel-body" style="text-align: right">
            <h3 class="mt5" id="text_alert">متن پیام</h3>
        </div>
        <div class="panel-footer text-left">
            <a id="delete_link2">
                <button class="btn btn-primary" type="button">بله</button>
            </a>
        </div>
    </div>
</div>
<!-- End: Content -->

@endsection
@push('scripts')
<link rel="stylesheet" type="text/css"
      href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
<!-- Page Plugins -->
<script type="text/javascript"
        src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
<!-- Admin Modals CSS -->
<link rel="stylesheet" type="text/css"
      href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">

<style>
    .sms-recipient-row {
        align-items: center;
        background: #f8fafc;
        border: 1px solid #dfe5eb;
        border-radius: 7px;
        cursor: pointer;
        display: flex;
        margin-bottom: 12px;
        padding: 14px 16px;
        transition: border-color .2s, background-color .2s;
    }

    .sms-recipient-row:hover {
        background: #f1f8f6;
        border-color: #70c7ad;
    }

    .sms-recipient-row input {
        display: none;
    }

    .sms-recipient-check {
        align-items: center;
        border: 2px solid #b8c2cc;
        border-radius: 5px;
        color: transparent;
        display: flex;
        height: 24px;
        justify-content: center;
        margin-left: 13px;
        width: 24px;
    }

    .sms-recipient-row input:checked + .sms-recipient-check {
        background: #2ebc98;
        border-color: #2ebc98;
        color: #fff;
    }

    .sms-recipient-info strong,
    .sms-recipient-info span {
        display: block;
    }

    .sms-recipient-info span {
        color: #687785;
        font-size: 14px;
        margin-top: 4px;
        text-align: right;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var hasSecondMobile = @json(!empty($appointment->detail[$appointment::SECOND_MOBILE] ?? optional($appointment->user)->second_mobile));

        $('.delete_ticket').on('click', function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            var timeFrom = $(this).attr('data-time');
            $('#delete_link').attr('value', link);
            $('#time_from').val(timeFrom);
            $('#send_sms_to_primary_mobile').val('1');
            $('#send_sms_to_second_mobile').val('0');
            $('#standard_sms_primary_checkbox').prop('checked', true);
            $('#standard_sms_second_checkbox').prop('checked', false);

            // Inline Admin-Form example
            $.magnificPopup.open({
                removalDelay: 500, //delay removal by X to allow out-animation,
                items: {
                    src: '#modal-panel'
                },
                // overflowY: 'hidden', //
                callbacks: {
                    beforeOpen: function (e) {
                        var Animation = $(".delete_ticket").attr('data-effect');
                        this.st.mainClass = Animation;
                    }
                },
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
        });

        $('#standard_sms_form').on('submit', function () {
            if (hasSecondMobile) {
                var sendToPrimary = $('#standard_sms_primary_checkbox').prop('checked');
                var sendToSecond = $('#standard_sms_second_checkbox').prop('checked');

                if (!sendToPrimary && !sendToSecond) {
                    alert('لطفاً حداقل یک شماره را برای ارسال پیامک انتخاب کنید.');
                    return false;
                }

                $('#send_sms_to_primary_mobile').val(sendToPrimary ? '1' : '0');
                $('#send_sms_to_second_mobile').val(sendToSecond ? '1' : '0');
            }
        });

    });
</script>
<!-- END: PAGE SCRIPTS -->
@endpush
