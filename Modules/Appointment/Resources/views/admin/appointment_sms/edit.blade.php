@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش پیامک')
@section('content')

        <!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right">
        <ul class="nav nav-list nav-list-topbar pull-right yekan">
            <li class="active">
                <a href="#">ویرایش پیامک</a>
            </li>
        </ul>
    </div>
</header>
<!-- End: Topbar -->


<!-- Begin: Content -->
<section id="content" class="table-layout animated fadeIn">

    <!-- begin: .tray-center -->
    <!-- end: .tray-center -->

    <div class="tray tray-center p25 va-t posr">
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
        @if(Session::has('successful'))
            <div class="alert alert-success alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-check pr10"></i>
                {{ Session::get('successful') }}
            </div>
        @endif
        <div class="admin-form theme-primary rtl yekan">

            <div class="panel">

                <form id="admin-form" method="post" novalidate="novalidate"
                      action="{{ route('admin.appointment_sms.update',$sms->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="panel-body">
                        <div class="section">
                            <label class="field select">
                                <select name="part_id" id="part" aria-required="true" aria-invalid="false" disabled="disabled">
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
                        <div class="option-group field section">
                            <label for="other" class="block mt15 option option-danger">
                                <input type="radio" name="status" id="other" value="0" @unless($sms->status) checked @endunless>
                                <span class="radio"></span>وضعیت غیرفعال</label>
                            <label for="checked" class="block mt15 option option-success">
                                <input type="radio" name="status" id="checked" value="1" @if($sms->status) checked @endif>
                                <span class="radio"></span>وضعیت فعال</label>
                        </div>
                        <div class="section-divider mb40 mt20"><span>برای پزشک</span></div>
                        <div class="checkbox-custom fill checkbox-disabled mb10 text-right" style="direction: ltr">
                            <input type="checkbox" checked="" name="users[]" disabled="" id="checkboxDefault11" value="{{ $sms->user->id }}">
                            <label for="checkboxDefault11">{{ $sms->user->full_name }}</label>
                        </div>
                        <!-- .section-divider -->
                        <div class="section" style="text-align: right">
                            <div style="clear: both;"></div>
                        </div><!-- end section -->

                        <div class="section-divider mb40 mt20"><span>متن پیامک</span></div>
                        <table class="table">
                            <thead>
                            <tr class="primary">
                                <th colspan="2">
                                    با استفاده از کلید واژه‌های زیر می‌توانید متن پیامک خود را ایجاد نمایید
                                </th>
                            </tr>
                            <tr class="dark">
                                <th>کلمه</th>
                                <th>عملکرد</th>
                            </tr>
                            <tr></tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{نام}</td>
                                <td>نام بیمار</td>
                            </tr>
                            <tr>
                                <td>{نام-خانوادگی}</td>
                                <td>نام‌خانوادگی بیمار</td>
                            </tr>
                            <tr>
                                <td>{جنسیت}</td>
                                <td>جنسیت بیما (آقا/خانم)</td>
                            </tr>
                            <tr>
                                <td>{بخش}</td>
                                <td>نام بخش نوبت گرفته شده</td>
                            </tr>
                            <tr>
                                <td>{پزشک}</td>
                                <td>نام کامل پزشک معالج</td>
                            </tr>
                            <tr>
                                <td>{ساعت}</td>
                                <td>ساعت نوبت گرفته شده</td>
                            </tr>
                            <tr>
                                <td>{تاریخ}</td>
                                <td>تاریخ نوبت گرفته شده</td>
                            </tr>
                            <tr>
                                <td>{جزئیات-نوبت}</td>
                                <td>لینک جزئیات نوبت</td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <div class="section">
                            <label class="field prepend-icon">
                                <textarea class="gui-textarea" id="comment" name="message" placeholder="متن پیامک">{{ old('message',$sms->message) }}</textarea>
                                <label for="comment" class="field-icon"><i class="fa fa-comments"></i>
                                </label>
                                    <span class="input-footer">
                                                            <strong>راهنما:</strong>از راهنمای جدول بالا استفاده کنید.</span>
                            </label>
                        </div>
                        <br>
                        <div class="section-divider mb40 mt20"><span>زمان ارسال</span></div>
                        <div class="section">
                            <label class="option option-primary">
                                <input type="radio" name="day_before_type" @if($sms->day_before == 0) checked="" @endif value="0">
                                <span class="radio"></span>در روز نوبت</label>
                        </div>
                        <div class="section">
                            <label class="option option-primary pull-right mt5">
                                <input type="radio" name="day_before_type" value="1" @unless($sms->day_before == 0) checked="" @endunless>
                                <span class="radio"></span>
                            </label>
                            <label class="field col-md-5 pull-right">
                                <input type="text" value="{{ old('day_before',($sms->day_before!=0) ? $sms->day_before : null) }}" name="day_before" id="day_before" class="gui-input" placeholder="روز" @if($sms->day_before == 0) disabled="" @endif>
                            </label>
                            <p class="pull-right mt10">
                                روز قبل
                            </p>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="button btn-primary " id="btn_save" type="submit">ویرایش پیامک</button>
                    </div><!-- end .form-footer section -->
                </form>

            </div>

        </div>
    </div>

</section>
@endsection
@push('scripts')
<link rel="stylesheet" type="text/css"
      href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
<!-- Page Plugins -->
<script type="text/javascript"
        src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
<!-- admin Modals CSS -->
<link rel="stylesheet" type="text/css"
      href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
<script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
<script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
<link rel="stylesheet"
      href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
<script type="text/javascript"
        src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $('#part').val('{{ $sms->appointmentPart->id }}');
        $('input[name="day_before_type"]').on('change',function(){
            if($(this).val() === '0'){
                $('#day_before').attr('disabled','disabled');
            }else{
                $('#day_before').removeAttr('disabled');
            }
        });

        $('#admin-form').submit(function(){
            if($("#comment").val().length == 0){
                new PNotify({
                    title: 'خطا!',
                    text: "متن پیامک را وارد کنید",
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
                $("#comment").focus();
                return false;
            }
            if($('input[name="day_before_type"]:checked').val() === '1'){
                if($("#day_before").val().length == 0){
                    new PNotify({
                        title: 'خطا!',
                        text: "عدد روز را وارد کنید",
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
                    $("#day_before").focus();
                    return false;
                }
            }
        });
    });

</script>
@endpush
