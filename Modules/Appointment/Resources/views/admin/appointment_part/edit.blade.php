@extends('admin.layout.layout')
@section('title',$title ?? 'ایجاد بخش جدید')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">مدیریت بخش‌های نوبت‌دهی</a>
                </li>
            </ul>
        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="{{ url('admin/consulting/parts') }}">مدریت بخش ها</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ url('admin/dashbord') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
                <li class="crumb-trail">بخش ها</li>
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
                          action="{{ route('admin.appointment_part.update',$part->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="panel-body">
                            <div class="section">
                                <label class="field prepend-icon" for="api_id">
                                    <input value="{{ old('api_id',$part->api_id) }}" type="text" placeholder="ایدی API"
                                           class="gui-input" id="api_id" name="api_id" aria-required="true">
                                    <label class="field-icon" for="api_id"><i class="fa fa-globe"></i></label>
                                </label>
                            </div><!-- end section -->
                            <div class="section">
                                <label class="field prepend-icon" for="title">
                                    <input value="{{ old('title',$part->title) }}" type="text" placeholder="عنوان بخش"
                                           class="gui-input" id="title" name="title" aria-required="true">
                                    <label class="field-icon" for="useremail"><i class="fa fa-globe"></i></label>
                                </label>
                            </div><!-- end section -->

                            <div class="section">
                                <label class="field select">
                                    <select name="parent_id" id="parent" aria-required="true" aria-invalid="false">
                                        <option value="0">بدون والد</option>
                                        @foreach($parts as $p_item)
                                            @continue($p_item->id == $part->id)
                                            <option value="{{ $p_item->id }}">
                                                {{ $p_item->title }}</option>
                                            @if($p_item->children()->exists())
                                                @include('appointment::admin.appointment_part._part_child_item_edit', array('main_part' => $p_item->children,'part'=>$part,'under_line_counter'=>1))
                                            @endif
                                        @endforeach
                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                            <div class="option-group field section">
                                <label for="other" class="block mt15 option option-danger">
                                    <input type="radio" name="status" id="other" value="0" @unless($part->status) checked @endunless>
                                    <span class="radio"></span>وضعیت غیرفعال</label>
                                <label for="checked" class="block mt15 option option-success">
                                    <input type="radio" name="status" id="checked" value="1" @if($part->status) checked @endif>
                                    <span class="radio"></span>وضعیت فعال</label>
                            </div>

                            <div class="section-divider mb40 mt20"><span>پزشک‌های مرتبط با این بخش</span></div>
                            <!-- .section-divider -->
                            <div class="section" style="text-align: right">
                                @foreach($doctor as $user)
                                    <label class="option option-primary rtl yekan col-md-4 pull-right"
                                           style="margin-bottom: 10px">
                                        <input type="checkbox" value="{{ $user->id }}" name="users[]" @if(in_array($user->id,$partDoctor)) CHECKED @endif>
                                        <span class="checkbox"></span>{{ $user->full_name }}
                                    </label>
                                @endforeach
                            </div><!-- end section -->


                        </div>
                        <div class="panel-footer text-right">
                            <button class="button btn-primary " type="submit"> ایجاد بخش جدید</button>
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
            $("#parent").val("{{ $part->parent_id }}");
            $("#admin-form").validate({
                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",

                rules: {
                    title: {
                        required: true
                    },
                },


                messages: {
                    title: {
                        required: 'وارد کردن عنوان الزامی می‌باشد'
                    },
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
        });
    </script>
@endpush
