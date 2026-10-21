@extends('admin.layout.layout')
@section('title', $title)
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="">امور مالی</a>
                </li>
                <li class="crumb-link">
                    <a href="{{ route('admin.finance.transactions.index') }}">تراکنش ها</a>
                </li>
                <li class="crumb-trail">افزودن تراکنش</li>
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
                @if(Session::has('sys_error'))
                    <div class="alert alert-danger alert-dismissable rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                        <i class="fa fa-remove pr10"></i>
                        {{ Session::get('sys_error') }}
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
                        <span class="panel-title">اضافه کردن تراکنش</span>
                    </div>
                    <div class="panel-body">
                        <div style="clear: both"></div>
                        <div class="alert alert-system">برای افزایش و یا کاهش شارژ از کاربر حتما وضعیت تراکنش را برروی
                            «موفق» تنظیم نمایید.
                        </div>
                        <form role="form" class="form-horizontal" method="post" id="storeNewTransaction"
                              action="{{ route('admin.finance.transactions.store') }}">
                            <input type="hidden" name="metas[AGENT_ID]" value="{{ \Auth::id() }}">
                            <input type="hidden" name="metas[AGENT_NAME]" value="{{ \Auth::user()->full_name }}">
                            <input type="hidden" name="metas[AGENT_IP]" value="{{ request()->ip() }}">
                            @csrf
                            <div class="form-group">
                                <label for="multiselect2" class="col-md-2 pull-right text-left control-label ">وضعیت
                                    تراکنش</label>
                                <div class="col-md-9 pull-right">
                                    <select id="multiselect2" name="status">
                                        @foreach(Modules\Finance\Entities\Transaction::$STATUS as $key => $value)
                                            <option value="{{ $key }}">{{ $value['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="multiselect1"
                                       class="col-md-2 pull-right text-left control-label ">نوع</label>
                                <div class="col-md-9 pull-right">
                                    <select id="multiselect1" name="type">
                                        @foreach(Modules\Finance\Entities\Transaction::$TYPE as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group rtl yekan">
                                <div class="form-group">
                                    <label for="inputStandard"
                                           class="col-lg-2 control-label text-left pull-right">مبلغ</label>
                                    <div class="col-lg-9 pull-right">
                                        <input type="text" name="price" id="inputStandard" class="form-control money"
                                               placeholder="مبلغ به تومان">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group rtl yekan">
                                <div class="form-group">
                                    <label for="user_id" class="col-lg-2 control-label text-left pull-right">شماره
                                        کاربر</label>
                                    <div class="col-lg-9 pull-right">
                                        <input type="text" id="user_id" name="user_id" class="form-control money"
                                               placeholder="شماره کاربر">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label pull-right text-left" for="textArea1">توضیحات
                                    تراکنش</label>
                                <div class="col-lg-4 pull-right">
                                    <textarea name="metas[DESCRIPTION]" class="form-control textarea-grow col-md-12"
                                              id="textArea1" rows="4"></textarea>
                                </div>
                            </div>
                            <div id="content_transaction_type_1" class="details" style="display: none">
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="au" class="col-lg-2 control-label text-left pull-right">au</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[AU_NUMBER]" id="au" class="form-control"
                                                   placeholder="شماره au">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="ONLINE_TRANSACTION_NUMBER"
                                               class="col-lg-2 control-label text-left pull-right">شماره تراکنش</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[ONLINE_TRANSACTION_NUMBER]"
                                                   id="ONLINE_TRANSACTION_NUMBER" class="form-control"
                                                   placeholder="شماره تراکنش">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="BANK_CODE" class="col-lg-2 control-label text-left pull-right">کد پی
                                            گیری بانک</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[BANK_CODE]" id="BANK_CODE"
                                                   class="form-control" placeholder="شماره تراکنش">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="content_transaction_type_2" class="details" style="display: none">
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="CART_NAME" class="col-lg-2 control-label text-left pull-right">نام
                                            صاحب کارت</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[CART_NAME]" id="CART_NAME"
                                                   class="form-control" placeholder="نام و نام خانوادگی صاحب کارت">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="au" class="col-lg-2 control-label text-left pull-right">۴ رقم اخر
                                            شماره کارت</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[CART_NUMBER]" id="au" class="form-control"
                                                   placeholder="۴ رقم آخر شماره کارت">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="CART_PAY_DATE" class="col-lg-2 control-label text-left pull-right">تاریخ
                                            پرداخت</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[CART_PAY_DATE]" id="CART_PAY_DATE"
                                                   class="form-control" placeholder="تاریخ کارت به کارت">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="content_transaction_type_3" class="details" style="display: none">
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="INVOICE_NUMBER" class="col-lg-2 control-label text-left pull-right">شماره
                                            فیش</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[INVOICE_NUMBER]" id="INVOICE_NUMBER"
                                                   class="form-control" placeholder="شماره فیش">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="INVOICE_NAME" class="col-lg-2 control-label text-left pull-right">نام
                                            و نام‌خانوادگی پرداخت کننده</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[INVOICE_NAME]" id="INVOICE_NAME"
                                                   class="form-control" placeholder="نام و نام خانوادگی پرداخت کننده">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group">
                                        <label for="INVOICE_PAY_DATE"
                                               class="col-lg-2 control-label text-left pull-right">تاریخ پرداخت</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" name="metas[INVOICE_PAY_DATE]" id="INVOICE_PAY_DATE"
                                                   class="form-control" placeholder="تاریخ پرداخت فیش">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="alt short"/>
                            <div class="section row mbn">
                                <div class="col-sm-8">
                                    <p class="text-left yekan">
                                        <button class="btn btn-primary yekan" type="submit">ارسال</button>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="program" class="panel-body pn rtl yekan">
                </div>
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
    <style>
        a.paginate_button {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            color: #4a89dc;
            float: right;
            line-height: 1.5;
            margin-left: -1px;
            padding: 5px 12px;
            position: relative;
            text-decoration: none;
            cursor: pointer;
        }

        a.current {
            background-color: #4a89dc;
            border-color: #4a89dc;
            color: #ffffff;
            cursor: default;
            z-index: 2;
        }

        .money {
            direction: ltr;
        }

        .datepicker-plot-area .datepicker-time-view .divider {
            display: none;
        }

        .rotateIn2 {
            font-size: 16px;
            -webkit-animation: spin 1.5s linear infinite;
            -moz-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }

        table.dataTable thead th.sorting_disabled {
            background: none !important;
        }

        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        /*#program table{*/
        /*width: 100%;*/
        /*margin: 20px 0px;*/
        /*border: 1px solid silver;*/
        /*}*/
        /*#program table td{*/
        /*border-left: 1px solid silver;*/
        /*text-align: center;*/
        /*padding: 10px 0px;*/
        /*}*/
        /*#program table td.success{*/
        /*background: #468847;*/
        /*color: white;*/
        /*}*/
        /*#program table td.danger{*/
        /*background: #ff0000;*/
        /*color: white;*/
        /*}*/
    </style>
    <script type="text/javascript">


        jQuery(document).ready(function () {


            $('#multiselect1').multiselect();
            $('#multiselect2').multiselect();

            $('.money').each(function () {
                var currentVal = $(this).val();
                var testDecimal = testDecimals(currentVal);
                if (testDecimal.length > 1) {
                    currentVal = currentVal.slice(0, -1);
                }
                $(this).val(replaceCommas(currentVal));
            });
            $('.money').keyup(function (event) {
                // skip for arrow keys
                if (event.which >= 37 && event.which <= 40) {
                    event.preventDefault();
                }

                let currentVal = $(this).val();
                const testDecimal = testDecimals(currentVal);
                if (testDecimal.length > 1) {
                    console.log("You cannot enter more than one decimal point");
                    currentVal = currentVal.slice(0, -1);
                }
                $(this).val(replaceCommas(currentVal));
            });

            function testDecimals(currentVal) {
                let count;
                currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
                return count;
            }

            function replaceCommas(yourNumber) {
                var components = yourNumber.toString().split(".");
                if (components.length === 1)
                    components[0] = yourNumber;
                components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                if (components.length === 2)
                    components[1] = components[1].replace(/\D/g, "");
                return components.join(".");
            }

            $('.details').hide();
            $("#content_transaction_type_" + $("#multiselect1").val()).show();
            $("#multiselect1").on('change', function () {
                $('.details').hide();
                $("#content_transaction_type_" + $(this).val()).slideDown();
            });
            $('#storeNewTransaction').on('submit', function () {
                let idData = $("#multiselect1").val();
                $('.details').each(function () {
                    if (!$(this).is("#content_transaction_type_" + idData)) {
                        $(this).remove();
                    }
                });
            })
        });
    </script>
@endpush
