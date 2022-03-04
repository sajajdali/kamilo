@extends('admin.layout.layout')
@section('title',$title ?? 'اضافه کردن بیماری')

@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ url('admin.disease.index') }}">مدیریت بیماری ها</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ url('admin/dashbord') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
                <li class="crumb-trail">افزودن بیماری جدید</li>
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
                          action="{{ route('admin.disease.store') }}">

                        <div class="panel-body">
                            {{ csrf_field() }}
                            <div class="section-divider mb40 mt20"><span>افزودن بیماری جدید</span></div><!-- .section-divider -->
                            <div class="section">
                                <label class="field prepend-icon" for="title">
                                    <input value="{{ old('name') }}" type="text" placeholder="نام بیماری" class="gui-input" id="name" name="name" aria-required="true">
                                    <label class="field-icon" for="name"><i class="fa fa-globe"></i></label>
                                </label>
                            </div>
                            <div class="section">
                                <div class="checkbox-custom fill checkbox-primary mb5">
                                    <input type="checkbox" @if(old('is_special') == 1) checked="checked" @endif id="is_special" name="is_special" value="1">
                                    <label for="is_special">بیماری خاص</label>
                                </div>
                            </div>

                            <!-- end section -->
                        </div>
                        <div class="panel-footer text-right">
                            <button class="button btn-primary " type="submit"> ارسال </button>
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
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <link rel="stylesheet" type="text/css" href={{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {

            {{--        $("#parent").val("{{ $catagory->parent }}").change();--}}
            $( "#admin-form" ).validate({


                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",

                rules: {
                    name:{
                        required: true
                    }
                },

                messages:{
                    name: {
                        required: 'نام را وارد نمایید.'
                    }
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

        });
    </script>
    <!-- END: PAGE SCRIPTS -->
@endpush
