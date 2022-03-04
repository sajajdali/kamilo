@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش الگو')
@section('content')

<!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right rtl yekan">
        <ol class="breadcrumb">
            <li class="crumb-active">
                <a href="{{ url('admin/blog') }}">مدیریت بلاگ</a>
            </li>
            <li class="crumb-icon">
                <a href="{{ url('admin/dashbord') }}">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li class="crumb-link">
                <a href="{{ url('/') }}">خانه</a>
            </li>
            <li class="crumb-trail">موضوعات</li>
        </ol>
    </div>
</header>
<!-- End: Topbar -->

<!-- Begin: Content -->
<section id="content" class="table-layout animated fadeIn">

    <!-- begin: .tray-center -->
    <!-- end: .tray-center -->

    <div class="tray tray-center p25 va-t posr">
        @if (Session::has('sys_error'))
            <div class="alert alert-danger alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-remove pr10"></i>
                <strong>خطا!</strong>
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
        @if(Session::has('successful'))
            <div class="alert alert-success alert-dismissable rtl yekan">
                <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                <i class="fa fa-check pr10"></i>
                {{ Session::get('successful') }}
            </div>
        @endif

        <div class="admin-form theme-primary rtl yekan">

            <div class="panel">

                <form id="admin-form" method="post" novalidate="novalidate" class="form-horizontal" enctype="multipart/form-data">
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <div class="section-divider mb40 mt20"><span> اضافه کردن الگوی جدید </span></div>
                        <!-- .section-divider -->
                        <div class="section">
                            <label class="field prepend-icon @if($errors->has('name')) state-error @endif" for="title">
                                <input type="text" placeholder="عنوان الگو  " class="gui-input" id="title"
                                       name="name" value="{{old('name',$catagory->name)}}" aria-required="true">
                                <label class="field-icon" for="useremail"><i class="fa fa-globe"></i></label>
                            </label>
                            @if($errors->has('name')) <em for="title" class="state-error">{{ $errors->first('name') }}</em> @endif
                        </div><!-- end section -->
                        <!-- .section-divider -->
                        <div class="section">
                            <label class="field prepend-icon @if($errors->has('price')) state-error @endif" for="price">
                                <input type="text" placeholder="قیمت به تومان" class="gui-input money" id="price" style="direction: ltr;padding-left: 10px;text-align: right"
                                       name="price" value="{{old('price',$catagory->price)}}" aria-required="true">
                                <label class="field-icon" for="price"><i class="fa fa-usd"></i></label>
                            </label>
                            @if($errors->has('price')) <em for="title" class="state-error">{{ $errors->first('price') }}</em> @endif
                        </div><!-- end section -->
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
                                                <option @if(old('rejim_type',$catagory->rejim_type) == $key) selected @endif value="{{ $key }}"
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
                                <label for="multiselect1" class="col-md-2 control-label pull-right">وعده‌های رژیم</label>
                                <div class="col-md-10 pull-right">
                                    <div class="btn-group">
                                        <select name="type" id="multiselect1">
                                            <option @if(old('type',$catagory->type) == '0') selected @endif value="0"
                                                    value="cheese">انتخاب کنید
                                            </option>
                                            <option @if(old('type',$catagory->type) == '1') selected @endif value="1"
                                                    value="tomatoes">رژیم بر مبنای کالری و وعده به وعده (چرخشی)
                                            </option>
                                            <option @if(old('type',$catagory->type) == '2') selected @endif value="2"
                                                    value="mozarella">رژیم بر مبنای واحدی و روزانه
                                            </option>
                                            <option @if(old('type',$catagory->type) == '3') selected @endif value="3"
                                                    value="mushrooms">رژیم به صورت متنی و صفحه ای
                                            </option>
                                        </select>
                                    </div>
                                    @if($errors->has('type')) <span class="help-block">{{ $errors->first('type') }}</span> @endif

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
                                                <option @if(old('disease') == $disease->id || in_array($disease->id, $catagory->disease->pluck('id')->toArray())) selected @endif value="{{ $disease->id }}">
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
                                       name="link" value="{{old('link',$catagory->link)}}" aria-required="true">
                                <label class="field-icon" for="useremail"><i class="fa fa-question-circle"></i></label>
                            </label>
                        </div><!-- end section -->

                        @if($catagory->img != null)
                            <div class="col-md-2 pull-right">
                                عکس فعلی :
                            </div>
                            <div class="col-md-4 pull-right">
                                <div class="fileupload fileupload-new admin-form" data-provides="fileupload">
                                    <div class="fileupload-preview thumbnail mb20">
                                        <img style="height: 140px; width: 100%; display: block;" src="{{ url('uploads/diet/'.$catagory->img) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <blockquote class="blockquote-primary">
                                    <p>توجه داشته باشید با انتخاب تصویر جدید ، فایل تصویر قدیمی حذف می شود.</p>
                                    <small>در صورتی که قصد تعویض عکس را ندارید فیلد تصویر رژیم را خالی بگذارید.</small>
                                </blockquote>
                            </div>
                        @endif
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
                            @if($errors->has('icon')) <em for="title" class="state-error">{{ $errors->first('icon') }}</em> @endif
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
                                <textarea class="gui-textarea" id="desc_before" name="desc_before" placeholder="توضیحات قبل از دریافت رژیم">{{ old('desc_before',$catagory->desc_before) }}</textarea>
                                <label for="desc_before" class="field-icon"><i class="fa fa-comments"></i>
                                </label>
                                <span class="input-footer">
                                                            <strong>این توضیحات قبل از دریافت رژیم به کاربر نمایش داده می‌شود</strong></span>
                            </label>
                        </div>
                        <div class="section">
                            <label class="field prepend-icon">
                                <textarea class="gui-textarea" id="desc_after" name="desc_after" placeholder="توضیحات بعد از دریافت رژیم">{{ old('desc_after',$catagory->desc_after) }}</textarea>
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
                                        <input type="text" name="color" value="{{ old('desc_after',$catagory->color) }}" class="form-control" />
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
    </div>

</section>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <link rel="stylesheet" type="text/css" href={{ asset('admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('.demo-auto').colorpicker();

            $('#multiselect1').multiselect();
            $('#multiselect2').multiselect();
            $('#multiselect3').multiselect();

            $("#parent").val("{{ $catagory->parent }}").change();
            $( "#admin-form" ).validate({


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


                messages:{
                    @foreach(Config::get('app.locales')  as $key=>$value)
                    title_{{ $key }}: {
                        required: 'عنوان {{ $value }} باید وارد شود.'
                    },
                    @endforeach
                },

                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.field').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.field').removeClass(errorClass).addClass(validClass);
                },
                errorPlacement: function(error, element) {
                    if (element.is(":radio") || element.is(":checkbox")) {
                        element.closest('.option-group').after(error);
                    } else {
                        error.insertAfter(element.parent());
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }

            });
            $('.delet_cat').on('click', function(e) {
                e.preventDefault();
                var link  = $(this).attr('href');
                $('#delete_link').attr('href',link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function(e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#btn_form').on('click', function(e) {
                e.preventDefault();
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function(e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#delete_link2').on('click',function(e){
                $('#del_group').submit();
                e.preventDefault();
            });

        });
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
