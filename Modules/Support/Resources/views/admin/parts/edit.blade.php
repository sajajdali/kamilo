@extends('admin.layout.layout')
@section('title', $title ?? 'ویرایش بخش')
@section('content')

<!-- Start: Topbar -->
<header id="topbar" class="ph10">
    <div class="topbar-right rtl yekan">
        <ol class="breadcrumb">
            <li class="crumb-active">
                <a href="{{ route('admin.support.parts.index') }}">مدریت بخش ها</a>
            </li>
            <li class="crumb-icon">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="glyphicon glyphicon-home"></span>
                </a>
            </li>
            <li class="crumb-link">
                <a href="">پشتیبانی</a>
            </li>
            <li class="crumb-link">
                <a href="{{ route('admin.support.parts.index')}}">بخش ها</a>
            </li>
            <li class="crumb-trail">ویرایش بخش</li>
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

                    <form id="admin-form" method="post" novalidate="novalidate" action="{{ route('admin.support.parts.update', $part->id) }}">
                        @method('PUT')
                        <div class="panel-body">
                            {{ csrf_field() }}
                            <div class="section-divider mb40 mt20"><span>ویرایش بخش {{ $part->title }}</span></div><!-- .section-divider -->

                                <div class="section">
                                    <label class="field prepend-icon" for="title">
                                        <input value="{{ old('title',$part->title) }}" type="text" placeholder="عنوان بخش" class="gui-input" id="title" name="title" aria-required="true">
                                        <label class="field-icon" for="useremail"><i class="fa fa-globe"></i></label>
                                    </label>
                                </div><!-- end section -->
                            <div class="section">
                                <label class="field select state-success">
                                    <select name="parent" id="parent" aria-required="true" aria-invalid="false">
                                        <option value="0">بدونه والد</option>
                                        @foreach($main_part as $pp)
                                            <option value="{{ $pp->id }}">{{ $pp->title }}</option>
                                        @endforeach
                                    </select>
                                    <i class="arrow double"></i>
                                </label>
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
{{--<!-- Panel popup -->--}}
{{--<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">--}}

    {{--<div class="panel">--}}
        {{--<div class="panel-heading" style="text-align: right">--}}
                            {{--<span class="panel-icon"><i class="fa fa-pencil"></i>--}}
                            {{--</span>--}}
            {{--<span class="panel-title">حذف بخش</span>--}}
        {{--</div>--}}
        {{--<div class="panel-body" style="text-align: right">--}}
            {{--<h3 class="mt5">آیا از حذف این بخش و زیرمجموعه های آن اطمینان دارید؟</h3>--}}
        {{--</div>--}}
        {{--<div class="panel-footer text-left">--}}
            {{--<a id="delete_link">--}}
                {{--<button class="btn btn-primary" type="button">بله حذف کن</button>--}}
            {{--</a>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<!-- End: Content -->--}}
@endsection

@push('scripts')
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
<script type="text/javascript">
    jQuery(document).ready(function() {

        $("#parent").val("{{ $part->parent_id }}").change();
        $( "#admin-form" ).validate({


            errorClass: "state-error",
            validClass: "state-success",
            errorElement: "em",

            rules: {
                title: {
                    required: true
                }
            },


            messages:{
                title: {
                    required: 'عنوان باید وارد شود.'
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
        $('.delete_part').on('click', function(e) {
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
                        var Animation = $(".delete_part").attr('data-effect');
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
</script>
<!-- END: PAGE SCRIPTS -->
@endpush
