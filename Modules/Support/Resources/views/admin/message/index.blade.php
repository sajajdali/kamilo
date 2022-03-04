@extends('admin.layout.layout')
@section('title',$title ?? 'پیام‌ها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
        </div>
    </header>
    <!-- End: Topbar -->
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
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
    <section id="content" class="table-layout animated fadeIn">
        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->


        <div class="tray tray-center p25 va-t posr">
            @if(Session::has('successful'))
                <div class="alert alert-success alert-dismissable rtl yekan">
                    <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
                    <i class="fa fa-check pr10"></i>
                    {{ Session::get('successful') }}
                </div>
        @endif
        <!-- recent orders table -->
            <div class="panel animated-delay animated-long" data-animate='["1600","fadeInUp"]'>
                <div class="panel-body pn">
                    <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                        <thead>
                        <tr class="bg-light yekan rtl ">

                            <th class="txt_center">عملیات</th>
                            <th class="txt_center">متن</th>
                            <th class="txt_center">کاربر</th>
                            <th class="txt_center">تاریخ</th>
                            <th class="txt_center">#</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($message))
                            @foreach($message as $notifi)
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group text-right yekan rtl txt_right">
                                            @if($notifi->status == Modules\Support\Entities\Message::MASSAGE_STATUS_READ)
                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">مشاهده شده
                                                    <span class="caret mr5"></span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-info br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">مشاهده نشده
                                                    <span class="caret mr5"></span>
                                                </button>
                                            @endif
                                            <ul class="dropdown-menu txt_right" role="menu">
                                                @can('delete_admin_support_message')
                                                    <li>
                                                        <a class="delete_part" data-effect="mfp-rotateLeft"
                                                           href="{{ route('admin.support.message.destroy',$notifi->id) }}">حذف</a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="txt_center yekan rtl">
                                        {!! $notifi->content !!}
                                    </td>
                                    <td class="txt_center yekan">
                                        @if($notifi->user == null )
                                            <span class="text-danger">کاربر حذف شده است</span>
                                        @else
                                            {{ $notifi->user->full_name }}
                                            <img class="img-responsive mw40 ib mr10" title="user"
                                                 src="{{ $notifi->user->avatar }}"
                                                 style="margin-left: 2px;">
                                        @endif
                                    </td>
                                    <td class="txt_center yekan rtl">
                                        {{ $notifi->date  }}
                                    </td>
                                    <td class="rtl yekan txt_center">{{ number2farsi($notifi->id) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(sizeof($message))
                        <center>{{ $message->links() }}</center>
                    @endif
                </div>
            </div>

        </div>

    </section>
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <form action="" method="POST" class="delete_link">
                @csrf
                @method('DELETE')
                <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                    <span class="panel-title">حذف پیام</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این پیام اطمینان دارید؟</h3>
                </div>
                <div class="panel-footer text-left">
                        <button class="btn btn-primary" type="submit">بله حذف کن</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Vendor CSS -->
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
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
    <script type="text/javascript" src="{{ asset('admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
    <style>
        .toggle_part {
            cursor: pointer;
            margin-left: 2px;
        }
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('.toggle_part').on('click', function () {
                var rid = $(this).data('rid');
                var i_object = $(this);
                if (i_object.hasClass('fa-minus-square')) {
                    i_object.removeClass('fa-minus-square');
                    i_object.addClass('fa-plus-square');
                    $('.ref_' + rid).hide();
                } else {
                    i_object.removeClass('fa-plus-square');
                    i_object.addClass('fa-minus-square');
                    $('.ref_' + rid).show();
                    $('.ref_' + rid).each(function () {
                        $(this).find('.fa-plus-square').removeClass('fa-plus-square').addClass('fa-minus-square');
                    })
                }
            });
            {{--        $("#parent").val("{{ $catagory->parent }}").change();--}}
            $("#admin-form").validate({


                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",

                rules: {
                    title: {
                        required: true
                    }
                },


                messages: {
                    title: {
                        required: 'عنوان باید وارد شود.'
                    }
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
            $('.delete_part').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('.delete_link').attr('action', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_part").attr('data-effect');
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
    </script>
    <!-- END: PAGE SCRIPTS -->
@endpush
