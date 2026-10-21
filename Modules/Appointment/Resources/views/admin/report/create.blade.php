@extends('admin.layout.layout')
@section('title',$title ?? 'تنظیم وقت')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">تنظیم گزارش'</a>
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
                <section id="content" class="table-layout animated fadeIn">

                    <!-- begin: .tray-center -->
                    <div class="tray tray-center p25 va-t posr">

                        <!-- create new order panel -->

                        <!-- recent orders table -->
                        <div class="panel">
                            <div class="panel-heading">
                                <span class="panel-title  rtl yekan right mr10">ویرایش پیام</span>
                            </div>
                            <form action="{{ route('admin.report.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="panel-body ">
                                    <div class="section row ">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <input type="text" value="{{old('title')}}" placeholder="عنوان" name="title" class="form-control rtl yekan"
                                                       id="inputStandard">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea rows="3" placeholder="توضیحات" name="comment" id="textArea2" class="form-control rtl yekan">{{old('comment')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="section row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="section row">
                                        <div class="col-xs-2">
                                            <button class="btn btn-hover btn-info btn-block rtl yekan" type="submit">
                                                ارسال و ثبت
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- end: .tray-center -->

                    <!-- begin: .tray-right -->
                    <!-- end: .tray-right -->

                </section>


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
