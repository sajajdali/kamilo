@extends('admin.layout.layout')
@section('title',$title ?? 'ارسال پیام جدید')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-link">
                    <a href="{{ url('/') }}">خانه</a>
                </li>
                <li class="crumb-trail">کاربران ها</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

        <div class="tray tray-center p25 va-t posr">
            <div class="pv30 ph40 bg-light dark br-b br-grey posr">
                <div class="table-layout">
                    <div class="w200 text-center pr30 hidden-xs pull-right">
                        <img style="width: 165px;height: 165px;"
                             src="{{ $user->avatar }}"
                             class="responsive">
                    </div>
                    <div class="va-t m30 pull-right text-right rtl yekan">

                        <h2 class="">
                            <small>ارسال پیام به
                            </small> {{ $user->full_name }}
                        </h2>
                        <p class="fs15 mb20">لطفا متن پیام را در زیر تایپ نمایید.</p>
                        <ul class="list-inline list-unstyled" style="padding-right: 0px;">

                            <li class="hidden">
                                <a href="#" title="pinterest link">
                                    <span class="fa fa-pinterest-square fs35 text-danger-light"></span>
                                </a>
                            </li>
                            <li class="hidden">
                                <a href="#" title="linkedin link">
                                    <span class="fa fa-linkedin-square fs35 text-info"></span>
                                </a>
                            </li>
                            <li class="hidden">
                                <a href="#" title="github link">
                                    <span class="fa fa-github-square fs35 text-dark"></span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#" title="phone link">
                                    <span class="fa fa-phone-square fs35 text-system"></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" title="email link">
                                    <span class="fa fa-envelope-square fs35 text-muted"></span>
                                </a>
                            </li>
                            <li class="hidden">
                                <a href="#" title="external link">
                                    <span class="fa fa-external-link-square fs35 text-muted"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
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

                    <form id="admin-form" method="post" novalidate="novalidate" enctype="multipart/form-data" action="{{ route('admin.support.message.store') }}">
                        <div class="panel-body">
                            @csrf
                            <div class="section-divider mb40 mt20"><span>ارسال پیام جدید</span></div>
                            <!-- .section-divider -->
                            <div class="section" style="text-align: right">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <textarea name="content" class="summernote" id="" cols="30" rows="10"></textarea>
                            </div><!-- end section -->


                        </div>
                        <div class="panel-footer text-right">
                            <button class="button btn-primary " type="submit"> ارسال پیام</button>
                        </div><!-- end .form-footer section -->
                    </form>

                </div>

            </div>
        </div>

    </section>
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
    <!-- Summernote CSS  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote-bs3.css') }}">

    <!-- Summernote JS -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote-fa-IR.js') }}"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('.summernote').summernote({
                lang: 'fa-IR', // default: 'en-US'
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function () {
                },
                onChange: function (contents, $editable) {
                },
                onImageUpload: function (files, editor, welEditable) {
                    //sendFile(files[0], editor, welEditable);
                }
            });
        });
    </script>
    <!-- END: PAGE SCRIPTS -->
@endpush
