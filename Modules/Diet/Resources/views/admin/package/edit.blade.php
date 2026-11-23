@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش پکیج')

@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ route('admin.diet.package.index') }}">مدیریت پکیج ها</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ url('admin/dashbord') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
                <li class="crumb-trail">افزودن پکیج جدید</li>
            </ol>
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
            <div class="admin-form theme-primary rtl yekan">

                <div class="panel">

                    <form id="admin-form" method="post" novalidate="novalidate"
                          action="{{ route('admin.diet.package.update', ['package' => $package->id]) }}">

                        <div class="panel-body">
                            @csrf
                            @method('put')
                            <div class="section-divider mb40 mt20"><span>ویرایش پکیج</span></div><!-- .section-divider -->
                            <div class="section">
                                <label class="field prepend-icon" for="title">
                                    <input value="{{ old('name',$package->title) }}" type="text" placeholder="نام پکیج" class="gui-input" id="name" name="name" aria-required="true">
                                    <label class="field-icon" for="name"><i class="fa fa-globe"></i></label>
                                </label>
                            </div>
                            <div class="section">
                                <label class="field prepend-icon" for="price">
                                    <input value="{{ old('price',$package->price) }}" type="text" placeholder="قیمت بسته" class="gui-input" id="price" name="price" aria-required="true">
                                    <label class="field-icon" for="price"><i class="fa fa-money"></i></label>
                                </label>
                            </div>
                            <div class="section">
                                <label class="field prepend-icon" for="charge">
                                    <input value="{{ old('charge',$package->charge) }}" type="text" placeholder="میزان شارژ دریافتی کاربره" class="gui-input" id="charge" name="charge" aria-required="true">
                                    <label class="field-icon" for="charge"><i class="fa fa-google-wallet"></i></label>
                                </label>
                            </div>
                            <div class="section">
                                <div class="form-group col-md-12">
                                    <div class="col-md-10">
                                        <input type="text" name="color" class="form-control demo demo-1 demo-auto" id="color_picker" value="{{ old('color',$package->color) }}" />
                                    </div>
                                    <label class="text-right col-md-1 control-label" for="cp2">رنگ</label>
                                </div>
                            </div>
                            <div class="section" id="attribute_packages">
                                <div class="form-group col-md-12">
                                    <div class="col-lg-1">
                                        <span class="text-success" id="add_attribute" style="cursor: pointer">
                                            <i style="font-size: 36px" class="fa fa-plus-circle"></i>
                                        </span>
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="detail[]" id="inputStandard" value="{{ (sizeof($details) >= 1 ? $details[0] : "") }}" class="form-control" placeholder="ویژگی بسته">
                                    </div>
                                    <label for="inputStandard" class="col-lg-2 control-label">ویژگی‌های پکیج</label>
                                </div>
                                @for($i=1;$i<sizeof($details);$i++)
                                    <div class="form-group col-md-12">
                                        <div class="col-lg-1">
                                        <span class="text-danger remove_attribute" style="cursor: pointer">
                                            <i style="font-size: 36px" class="fa fa-remove"></i>
                                        </span>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" name="detail[]" id="inputStandard" value="{{ $details[$i] }}" class="form-control" placeholder="ویژگی بسته">
                                        </div>
                                        <label for="inputStandard" class="col-lg-2 control-label">ویژگی‌های پکیج</label>
                                    </div>
                                @endfor
                            </div>
                            <!-- end section -->
                        </div>
                        <div class="panel-footer text-right">
                            <button class="button btn-primary " type="submit"> دخیره </button>
                        </div>
                        <!-- end .form-footer section -->
                    </form>

                </div>

            </div>
        </div>

    </section>
@endsection
@push('scripts')

    <style>
        .checkbox-custom.fill input[type=checkbox]:checked + label:after {
            right: 7px;
        }
    </style>


    <!-- Vendor CSS -->
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/editors/markitup/sets/default/images/list-numeric.png') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <link rel="stylesheet" type="text/css" href={{ asset('admin/files/assets/admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('.demo-auto').colorpicker();
            $("body").on('click','.remove_attribute',function(){
                $(this).parents('.form-group').remove();
            });
            $('#add_attribute').on('click',function(){
                $('#attribute_packages').append("<div class=\"form-group col-md-12\">" +
                    "<div class=\"col-lg-1\">" +
                    "<span class=\"text-danger remove_attribute\" style=\"cursor: pointer\">" +
                    "<i style=\"font-size: 36px\" class=\"fa fa-remove\"></i></span></div>" +
                    "<div class=\"col-lg-9\">" +
                    "<input type=\"text\" name=\"detail[]\" id=\"inputStandard\" class=\"form-control\" placeholder=\"ویژگی بسته\"></div>" +
                    "<label for=\"inputStandard\" class=\"col-lg-2 control-label\">ویژگی‌های پکیج</label></div>");
            });
            {{--        $("#parent").val("{{ $catagory->parent }}").change();--}}
        });
    </script>
    <!-- END: PAGE SCRIPTS -->
@endpush
