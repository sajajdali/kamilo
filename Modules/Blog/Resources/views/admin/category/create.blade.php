@extends('admin.layout.layout')
@section('title',$title ?? 'موضوعات')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a>مدیریت موضوعات</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ route('admin.blog.index') }}">اخبار</a>
                </li>
                <li class="crumb-link">
                    <a href="{{ route('admin.blog.category.index') }}"> موضوعات</a>
                </li>
                <li class="crumb-trail">افزودن موضوع</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <div id="content">
        <div class="row">
            <div class="com-md-12">


                <div class="admin-form theme-primary rtl yekan">

                    <div class="panel">

                        <form id="admin-form" method="post" novalidate="novalidate" action="{{ route('admin.blog.category.store') }}">
                            <div class="panel-body">
                                {{ csrf_field() }}
                                <div class="section-divider mb40 mt20"><span> اضافه کردن موضوع جدید </span></div>

                                <!-- .section-divider -->

                                    <div class="section">
                                        <label class="field prepend-icon" for="title">
                                            <input type="text" placeholder="عنوان موضوع" class="gui-input" id="title" name="title" aria-required="true">
                                            <label class="field-icon" for="useremail"><i class="fa fa-globe"></i></label>
                                        </label>
                                    </div>

                                <!-- end section -->

                                <div class="section">
                                    <label class="field select state-success">
                                        <select name="parent" id="parent" aria-required="true" aria-invalid="false">
                                            <option value="0">بدون والد</option>
                                            @include('blog::admin.layouts._option_cat_item', array('cats' => $all_cat,'under_line_counter'=>0))
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
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
@endsection
