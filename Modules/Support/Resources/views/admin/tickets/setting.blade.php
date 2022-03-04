@extends('admin.layout.layout')
@section('content')
        <!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right rtl yekan">
        <ol class="breadcrumb">
            <li class="crumb-active">
                <a href="{{ url('admin/consulting/ticket/setting') }}">تنظیمات تیکت ها</a>
            </li>
            <li class="crumb-icon">
                <a href="{{ url('admin/dashbord') }}">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li class="crumb-link">
                <a href="{{ url('/') }}">خانه</a>
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
            <div class="panel rtl yekan">
                <div class="panel-heading">
                    <span class="panel-title">تنظیمات</span>
                </div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-lg-9">
                                <input value="{{ old('email',(isset($setting->email) ? $setting->email : '')) }}" name="email" type="text" placeholder="ایمیل" class="form-control" id="inputStandard">
                            </div>
                            <label class="col-lg-3 control-label" for="inputStandard">ایمیل پاسخ گویی</label>
                        </div>
                        <div class="panel-body bg-light pt20 pbn pl30 rtl yekan">
                            <h3 class="text-center mb30 mt40 fw400">تعیین نرخ</h3>
                            <div class="tab-block mb25">
                            <ul class="nav nav-tabs nav-tabs-right tabs-border" style="padding-right: 0px;">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab1" aria-expanded="false">هزینه تیکت ها</a>
                                </li>
                            </ul>
                            <div class="tab-content">


                                <div class="tab-pane active" id="tab1">
                                    <div class="tab-block mb25">
                                        <ul class="nav tabs-right">
                                            @foreach($all_role as $role)
                                                <li @if($role == $all_role->first()) class="active" @endif>
                                                    <a data-toggle="tab" href="#tab11_{{ $role->id }}"><i class="fa fa-user text-purple pr5"></i> {{ $role->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach($all_role as $role)
                                                <div class="tab-pane @if($role == $all_role->first()) active @endif " id="tab11_{{ $role->id }}">
                                                    <h4 class="text-center">هزینه برای گروه کاربری {{ $role->title }}</h4>
                                                    <hr class="short alt">
                                                    <div class="form-group">
                                                        <div class="col-lg-9">
                                                            <input value="" style="direction: ltr"  name="cost[{{$role->id}}]" type="text" placeholder="هزینه" class="form-control money" id="con_1_0">
                                                            <span class="help-block mt5"><i class="fa fa-bell"></i> برای رایگان شدن عدد 0 را وارد نمایید.هزینه به تومان می باشد.</span>
                                                        </div>
                                                        <label class="col-lg-3 control-label" for="con_1_0">هزینه تیکت</label>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>


                            </div>
                            </div>
                            {{--@foreach($all_role as $role)--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="col-lg-9">--}}
                                        {{--<input value="{{ isset($setting->{$role->id}) ? $setting->{$role->id} : '' }}" style="direction: ltr"  name="cost[]" type="text" placeholder="هزینه" class="form-control money" id="role_{{ $role->id }}">--}}
                                        {{--<span class="help-block mt5"><i class="fa fa-bell"></i> برای رایگان شدن عدد 0 را وارد نمایید.هزینه به تومان می باشد.</span>--}}
                                    {{--</div>--}}
                                    {{--<label class="col-lg-3 control-label" for="role_{{ $role->id }}">هزینه برای گروه {{ $role->title }}</label>--}}
                                {{--</div>--}}
                            {{--@endforeach--}}
                        </div>
                        <div style="clear: both"></div>
                        <div class="form-group">
                        </div>
                        <div class="form-group" style="text-align: right">
                            <div class="checkbox-custom fill checkbox-info mb5" style="direction: ltr">
                                <input name="send_sms" value="1" type="checkbox" id="checkboxExample16" @if(isset($setting->send_sms) && $setting->send_sms) CHECKED @endif>
                                <label for="checkboxExample16">مطلع کردن کاربر با پیامک</label>
                            </div>
                        </div>
                        <div class="form-group" style="text-align: right">
                            <div class="checkbox-custom fill checkbox-info mb5" style="direction: ltr">
                                <input name="send_email" value="1"  type="checkbox" id="checkboxExample17" @if(isset($setting->send_email) && $setting->send_email) CHECKED @endif>
                                <label for="checkboxExample17">مطلع کردن کردن با ایمیل <small>(در صورت وارد شده آدرس ایمیل)</small></label>
                            </div>
                        </div>
                        <hr class="short alt">
                        <div class="section row mbn">
                            <div class="col-sm-4">
                                <p class="text-left yekan">
                                    <button type="submit" class="btn btn-primary yekan">ثبت و ذخیره</button>
                                </p>
                            </div>
                            <div class="col-sm-8">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('.money').each(function(){
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

            @if(isset($setting->cost))
                @foreach($setting->cost as $keuy => $cost)
                    @if(isset($keuy))
                        $('input[name="cost[<?=$keuy?>]"').val('<?=$cost?>').keyup();
                    @endif
                @endforeach
            @endif


        });
        function testDecimals(currentVal) {
            var count;
            currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
            return count;
        }

        function replaceCommas(yourNumber) {
            var components = yourNumber.toString().split(".");
            if (components.length === 1)
                components[0] = yourNumber;
            components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " , ");
            if (components.length === 2)
                components[1] = components[1].replace(/\D/g, "");
            return components.join(".");
        }
    </script>
@endsection
