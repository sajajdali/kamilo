@extends('admin.layout.layout')
@section('title',$title ?? 'وعده‌ها')

@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ url('admin/blog') }}">مدیریت رژیم ها</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ url('admin/dashbord') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
                <li class="crumb-trail">عناوین</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">

            <!-- menu quick links -->
            <form method="get">
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500">فیلتر کردن رژیم ها</h4>

                    <hr class="short">

                    <div class="section mb15">
                        <label for="order-id" class="field prepend-icon">
                            <input value="{{ (isset($_GET['title']) ? $_GET['title'] : '') }}" type="text" name="title"
                                   id="order-id" class="gui-input yekan" placeholder="عنوان موضوع">
                            <label for="order-id" class="field-icon"><i class="fa fa-tag"></i>
                            </label>
                        </label>
                    </div>

                    <h6 class="fw400">موضوع اصلی</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="group" class="yekan">
                                <option value="0" selected="selected">فیلتر کردن براساس گروه اصلی</option>
                                @foreach($all_cat as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach

                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>


                    <hr class="short">

                    <div class="section row">
                        <div class="col-sm-12">
                            <button class="btn btn-default btn-sm ph25 yekan" type="submit">جستجو</button>
                            <input type="hidden" name="page" value="1">
                            <input type="hidden" name="act" value="search">
                        </div>
                    </div>

                </div>
            </form>
        </aside>
        <!-- end: .tray-right -->

        <div class="tray tray-center p25 va-t posr">
            @if (Session::has('sys_error'))
                <div class="alert alert-danger alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-remove pr10"></i>
                    <strong>خطا!</strong>
                    {{ Session::get('sys_error') }}
                </div>
            @endif
            @if(Session::has('successful'))
                <div class="alert alert-success alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ Session::get('successful') }}
                </div>
            @endif
            @can('add_admin_meal_category')
                <div class="admin-form theme-primary rtl yekan">

                    <div class="panel">

                        <form id="admin-form" method="post" novalidate="novalidate" class="form-horizontal"
                              enctype="multipart/form-data">
                            <div class="panel-body">
                                {{ csrf_field() }}
                                <div class="section-divider mb40 mt20"><span> اضافه کردن الگوی جدید </span></div>
                                <!-- .section-divider -->
                                <div class="section">
                                    <label class="field prepend-icon @if($errors->has('name')) state-error @endif"
                                           for="title">
                                        <input type="text" placeholder="عنوان الگو  " class="gui-input" id="title"
                                               name="name" value="{{old('name')}}" aria-required="true">
                                        <label class="field-icon" for="useremail"><i class="fa fa-globe"></i></label>
                                    </label>
                                    @if($errors->has('name')) <em for="title"
                                                                  class="state-error">{{ $errors->first('name') }}</em> @endif
                                </div>
                                <!-- end section -->
                                <!-- .section-divider -->
                                <div class="section">
                                    <label class="field prepend-icon @if($errors->has('price')) state-error @endif"
                                           for="price">
                                        <input type="text" placeholder="قیمت به تومان" class="gui-input money"
                                               id="price" style="direction: ltr;padding-left: 10px;text-align: right"
                                               name="price" value="{{old('price')}}" aria-required="true">
                                        <label class="field-icon" for="price"><i class="fa fa-usd"></i></label>
                                    </label>
                                    @if($errors->has('price')) <em for="price"
                                                                   class="state-error">{{ $errors->first('price') }}</em> @endif
                                </div>
                                <!-- end section -->
                                <div class="section">
                                    <div class="form-group @if($errors->has('type')) has-error @endif">
                                        <label for="multiselect2" class="col-md-2 control-label pull-right">نوع
                                            رژیم</label>
                                        <div class="col-md-10 pull-right">
                                            <div class="btn-group">
                                                <select name="rejim_type" id="multiselect2">
                                                    <option @if(old('rejim_type') == '0') selected @endif value="0"
                                                            value="cheese">انتخاب کنید
                                                    </option>
                                                    @foreach(\Modules\Diet\Entities\Meal\Meal_category::$REJIMTYPES as $key => $r_type)
                                                        <option @if(old('rejim_type') == $key) selected @endif value="{{ $key }}"
                                                                value="cheese">{{ $r_type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('rejim_type')) <span
                                                    class="help-block">{{ $errors->first('rejim_type') }}</span> @endif

                                        </div>

                                    </div>
                                </div>

                                <div class="section">
                                    <div class="form-group @if($errors->has('type')) has-error @endif">
                                        <label for="multiselect1" class="col-md-2 control-label pull-right">وعده‌های
                                            رژیم</label>
                                        <div class="col-md-10 pull-right">
                                            <div class="btn-group">
                                                <select name="type" id="multiselect1">
                                                    <option @if(old('type') == '0') selected @endif value="0"
                                                            value="cheese">انتخاب کنید
                                                    </option>
                                                    <option @if(old('type') == '1') selected @endif value="1"
                                                            value="tomatoes">رژیم بر مبنای کالری و وعده به وعده (چرخشی)
                                                    </option>
                                                    <option @if(old('type') == '2') selected @endif value="2"
                                                            value="mozarella">رژیم بر مبنای واحدی و روزانه
                                                    </option>
                                                    <option @if(old('type') == '3') selected @endif value="3"
                                                            value="mushrooms">رژیم به صورت متنی و صفحه ای
                                                    </option>
                                                </select>
                                            </div>
                                            @if($errors->has('type')) <span
                                                    class="help-block">{{ $errors->first('type') }}</span> @endif

                                        </div>

                                    </div>
                                </div>

                                <div class="section">
                                    <div class="form-group @if($errors->has('disease')) has-error @endif">
                                        <label for="multiselect3" class="col-md-2 control-label pull-right">بیماری</label>
                                        <div class="col-md-10 pull-right">
                                            <div class="btn-group">
                                                <select multiple name="disease[]" id="multiselect3">
                                                    @foreach(\Modules\Diet\Entities\Disease\Disease::all() as $disease)
                                                        <option @if(old('disease') == $disease->id) selected @endif value="{{ $disease->id }}">
                                                            {{ $disease->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('disease'))
                                                <span class="help-block">{{ $errors->first('disease') }}</span>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <!-- .section-divider -->
                                <div class="section">
                                    <label class="field prepend-icon" for="title">
                                        <input type="text" placeholder="آدرس صفحه راهنما  " class="gui-input" id="title"
                                               name="link" value="{{old('link')}}" aria-required="true">
                                        <label class="field-icon" for="useremail"><i class="fa fa-question-circle"></i></label>
                                    </label>
                                </div><!-- end section -->

                                <div class="section">
                                    <label class="field prepend-icon append-button file @if($errors->has('icon')) state-error @endif">
                                        <span class="button btn-primary">انتخاب فایل</span>
                                        <input class="gui-file" name="icon" id="file3"
                                               onchange="document.getElementById('uploader3').value = this.value;"
                                               type="file">
                                        <input class="gui-input" id="uploader3" placeholder="تصویر رژیم"
                                               type="text">
                                        <label class="field-icon"><i class="fa fa-upload"></i>
                                        </label>
                                    </label>
                                    @if($errors->has('icon')) <em for="title"
                                                                  class="state-error">{{ $errors->first('icon') }}</em> @endif
                                </div>


                                <div class="section">
                                    <label class="field select state-success">
                                        <select name="parent" id="parent" aria-required="true" aria-invalid="false">
                                            <option value="0">بدونه والد</option>
                                            @include('diet::admin.meal._option_edit_cat_item', array('cats' => $all_cat,'under_line_counter'=>0))
                                        </select>
                                        <i class="arrow double"></i>
                                    </label>
                                </div><!-- end section -->
                                <div class="section">
                                    <label class="field prepend-icon">
                                        <textarea class="gui-textarea" id="desc_before" name="desc_before" placeholder="توضیحات قبل از دریافت رژیم">{{ old('desc_before') }}</textarea>
                                        <label for="desc_before" class="field-icon"><i class="fa fa-comments"></i>
                                        </label>
                                        <span class="input-footer">
                                                            <strong>این توضیحات قبل از دریافت رژیم به کاربر نمایش داده می‌شود</strong></span>
                                    </label>
                                </div>
                                <div class="section">
                                    <label class="field prepend-icon">
                                        <textarea class="gui-textarea" id="desc_after" name="desc_after" placeholder="توضیحات بعد از دریافت رژیم">{{ old('desc_after') }}</textarea>
                                        <label for="desc_after" class="field-icon"><i class="fa fa-comments"></i>
                                        </label>
                                        <span class="input-footer">
                                                            <strong>این توضیحات بعد از دریافت رژیم به کاربر نمایش داده می‌شود.</strong></span>
                                    </label>
                                </div>
                                <div class="section">
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <div class="input-group colorpicker-component demo demo-auto cursor">
                                                <span class="input-group-addon"><i></i>
                                                </span>
                                                <input type="text" name="color" value="" class="form-control" />
                                            </div>
                                        </div>
                                        <label class="col-md-2 control-label" for="cp3">انتخاب رنگ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="button btn-primary " type="submit"> ارسال</button>
                            </div><!-- end .form-footer section -->
                        </form>

                    </div>

                </div>
            @endcan
            <form id="del_group" method="post">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="panel">
                    @can('group_delete_admin_meal_category')
                        <div class="panel-menu p12 admin-form theme-primary">
                            <div class="row">
                                <div class="col-md-5 rtl">
                                    <button id="btn_form" class="button btn-primary " type="button">برو</button>
                                </div>
                                <div class="col-md-5 rtl">
                                    <label class="field select">
                                        <select class="yekan empty" name="filter_status" id="filter_status">
                                            <option value="1">حذف همه</option>
                                        </select>
                                        <i class="arrow"></i>
                                    </label>
                                </div>
                                <div class="col-md-2 rtl yekan" style="padding-top: 12px">
                                    عملیات گروهی
                                </div>
                            </div>
                        </div>
                    @endcan
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">عملیات</th>
                                <th class="txt_right">قیمت</th>
                                <th class="txt_right">نوع الگو رژیم</th>
                                <th class="txt_right">عنوان موضوع</th>
                                <th class="text-center">انتخاب</th>
                            </tr>
                            </thead>
                            <tbody>
                            @include('diet::admin.meal._tr_cat_item', array('table_cats' => $table_cats,'under_line_counter'=>0))
                            </tbody>
                        </table>
                        <div class="dt-panelfooter clearfix">
                            <div class="dataTables_paginate paging_simple_numbers" id="datatable2_paginate">
                                <center> {{ $table_cats->links() }}</center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">حذف موضوع</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این موضوع اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">حذف گروهی</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف گروهی اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link2">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection

@push('scripts')
    <style>
        .admin-form .checkbox, .admin-form .radio {
            background: none !important;
            width: auto !important;
            border: none;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Vendor CSS -->
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <link rel="stylesheet" type="text/css"
          href={{ asset('admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('.demo-auto').colorpicker();

            $('#multiselect1').multiselect();
            $('#multiselect2').multiselect();
            $('#multiselect3').multiselect();

            $("#admin-form").validate({


                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",

                rules: {
                    @foreach(Config::get('app.locales')  as $key=>$value)
                    title_{{ $key }}: {
                        required: true
                    },
                    @endforeach
                },


                messages: {
                    @foreach(Config::get('app.locales')  as $key=>$value)
                    title_{{ $key }}: {
                        required: 'عنوان {{ $value }} باید وارد شود.'
                    },
                    @endforeach
                },

                highlight: function (element, errorClass, validClass) {
                    $(element).closest('.field').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest('.field').removeClass(errorClass).addClass(validClass);
                },
                errorPlacement: function (error, element) {
                    if (element.is(":radio") || element.is(":checkbox")) {
                        element.closest('.option-group').after(error);
                    } else {
                        error.insertAfter(element.parent());
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }

            });
            $('.delet_cat').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('#delete_link').attr('href', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#btn_form').on('click', function (e) {
                e.preventDefault();
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#delete_link2').on('click', function (e) {
                $('#del_group').submit();
                e.preventDefault();
            });

        });

        $('.money').each(function () {
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
    <!-- END: PAGE SCRIPTS -->
@endpush