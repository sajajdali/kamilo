@extends('admin.layout.layout')
@section('title',$title ?? 'دانلود لیست نوبتها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">دانلود لیست نوبتها </a>
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
                    <div class="tray tray-center p25 va-t posr">

                        <!-- create new order panel -->

                        <!-- recent orders table -->
                        <div class="panel">
                            <div class="panel-body pn">
                                <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                                    <thead>
                                    <tr class="bg-light">
                                        <th class="text-center">دانلود</th>

                                        <th class="">پیام</th>
                                        <th class="txt_center">تاریخ</th>
                                        <th class="txt_center">عنوان</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>

                                        <td class="txt_center yekan">
                                            <a href="{{url('uploads/report/'.get_option('REPORT_FILE'))}}" class="btn btn-success ">دانلود</a>
                                        </td>
                                        <td class="rtl yekan">
                                            {!!  nl2br(get_option('REPORT_COMMENT')) ?? '' !!}

                                        </td>
                                        <td class="txt_center rtl yekan">
                                            {{get_option('REPORT_TIME') ? get_option('REPORT_TIME')  :''}}
                                        </td>
                                        <td class="txt_center yekan">
                                            {{get_option('REPORT_TITLE') ?? ''}}
                                        </td>


                                    </tr>

                                    </tbody>
                                </table>
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
