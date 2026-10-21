@extends('admin.layout.layout')
@section('title',$title ?? 'تنظیم وقت')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">تنظیم وقت</a>
                </li>
            </ul>
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
                    <div class="col-sm-3 col-md-2">
                        <div class="panel panel-tile text-center">
                            <div class="panel-heading hidden">
                                <span class="panel-title"><i class="fa fa-pencil"></i> Title</span>
                            </div>
                            <div class="panel-body bg-info light">
                                <i class="fa fa-twitter text-muted fs70 mt10"></i>
                                <h1 class="fs35 mbn" style="font-size: 22px !important;"> {{ jdate('Y/m/d' , $status->expire) }}</h1>
                                <h6 class="text-white">تاریخ اتمام</h6>
                            </div>
                            <div class="panel-footer bg-info br-n p12">
                                <span class="fs11"><i class="fa fa-arrow-up pr5"></i><b> شارژ کنید</b>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3 rtl col-md-2">
                        <div class="panel panel-tile text-center">
                            <div class="panel-heading hidden">
                                <span class="panel-title"><i class="fa fa-pencil"></i> وضعیت شارژ شما</span>
                            </div>
                            <div class="panel-body bg-danger light">
                                <i class="fa fa-envelope text-muted fs70 mt10"></i>
                                <h1 class="fs35 mbn">  <b>{{number_format($status->balance / 10)}} </b></h1>
                                <h6 class="text-white">تومان</h6>
                            </div>
                            <div class="panel-footer bg-danger br-n p12">
                                <span class="fs11"><b>شارژ شما</b>
                                </span>
                            </div>
                        </div>
                    </div>


            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Fancytree CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') }}">
    <style>
        #fancytree-drop-marker, span.fancytree-checkbox, span.fancytree-drag-helper-img, span.fancytree-empty, span.fancytree-expander, span.fancytree-icon, span.fancytree-radio, span.fancytree-vline {
            transform: rotate(90deg);
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>

    <!-- Fancytree Plugin -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/fancytree/jquery.fancytree-all.min.js') }}"></script>

@endpush
