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

    <form action="{{route('admin.standard_sms.send' , $appointment)}}" method="POST" >
        @csrf
        <div class="panel">
            <input id="delete_link" name="template" type="hidden" value="25">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">ارسال پیامک</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از ارسال پیامک اطمینان دارید</h3>
                <br><br>
                <div class="form-group">
                    <div class="col-lg-8">
                        <input id="time_from" name="time_from" type="text" class="form-control" value="25">
                    </div>
                    <label for="inputStandard" class="col-lg-3 control-label">زمان حضور</label>
                </div>

            </div>
            <div class="panel-footer text-left">

                <button class="btn btn-primary" type="submit">بله ارسال شود کن</button>

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

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('.delete_ticket').on('click', function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            var timeFrom = $(this).attr('data-time');
            $('#delete_link').attr('value', link);
            $('#time_from').attr('value', timeFrom);
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

    });
</script>
<!-- END: PAGE SCRIPTS -->
@endpush
