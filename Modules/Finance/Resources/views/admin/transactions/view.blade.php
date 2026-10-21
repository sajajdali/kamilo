@extends('admin.layout.layout')
@section('title', $title ?? "جزییات تراکنش")
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
                <li class="crumb-trail">جزییات تراکنش</li>
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
                        <div class="alert alert-system">
                            فقط برای سفارش‌های «در انتظار» امکان تغییر وضعیت وجود خواهد داشت.
                        </div>
                        <form role="form" class="form-horizontal" method="post" id="storeNewTransaction"
                              action="{{ route('admin.finance.transactions.update',['id' => $transaction->id]) }}">
                            <input type="hidden" name="metas[AGENT_CHANGER_ID]" value="{{ \Auth::id() }}">
                            <input type="hidden" name="metas[AGENT_CHANGER_NAME]" value="{{ \Auth::user()->full_name }}">
                            <input type="hidden" name="metas[AGENT_CHANGER_IP]" value="{{ request()->ip() }}">
                            @csrf
                            @method('PUT')
                            @if($transaction->status == Modules\Finance\Entities\Transaction::STATUS_PENDING)
                                <div class="form-group">
                                    <label for="multiselect2" class="col-md-2 pull-right text-left control-label ">وضعیت
                                        تراکنش</label>
                                    <div class="col-md-9 pull-right">
                                        <select id="multiselect2" name="status">
                                            @foreach(Modules\Finance\Entities\Transaction::$STATUS as $key => $value)
                                                <option value="{{ $key }}">{{ $value['title'] }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-danger" type="submit">تغییر وضعیت</button>
                                    </div>
                                </div>
                            @endif
                            <div id="content">
                                <div class="form-group rtl yekan">
                                    <div class="form-group disabled">
                                        <label for="status" class="col-lg-2 control-label text-left pull-right">وضعیت
                                            فعلی </label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" id="status" class="form-control"
                                                   value="{{ Modules\Finance\Entities\Transaction::$STATUS[$transaction->status]['title'] }}"
                                                   disabled="disabled"
                                                   placeholder="شماره au">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group rtl yekan">
                                    <div class="form-group disabled">
                                        <label for="status" class="col-lg-2 control-label text-left pull-right">نوع
                                            سفارش</label>
                                        <div class="col-lg-9 pull-right">
                                            <input type="text" id="status" class="form-control"
                                                   value="{{ Modules\Finance\Entities\Transaction::$TYPE[$transaction->type] }}"
                                                   disabled="disabled"
                                                   placeholder="شماره au">
                                        </div>
                                    </div>
                                </div>
                                @foreach($transaction->transactionMetas as $metas)
                                    <div class="form-group rtl yekan">
                                        <div class="form-group disabled">
                                            <label for="metas_{{ $metas->id }}"
                                                   class="col-lg-2 control-label text-left pull-right">{{ Modules\Finance\Entities\TransactionMeta::$metas[$metas->meta_key] }}</label>
                                            <div class="col-lg-9 pull-right">
                                                <input type="text" id="metas_{{ $metas->id }}" class="form-control"
                                                       value="{{ $metas->meta_value }}"
                                                       disabled="disabled"
                                                       placeholder="شماره au">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
