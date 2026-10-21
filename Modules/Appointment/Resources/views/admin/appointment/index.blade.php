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
                <div class="panel rtl yekan">
                    <div class="panel-heading">
                        <span class="panel-title">انتخاب پزشک</span>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            بخش مورد نظر خود را انتخاب کنید
                        </div>
                        <div id="tree">
                            <ul id="treeData" style="display: none;">
                                @foreach($data['parts'] as $part)
                                    <li id="{{ $part->id }}"
                                        @if($part->children()->exists()) class="folder" @endif>{{ $part->title }}
                                        @if($part->children()->exists())
                                            @include('appointment::admin.appointment.layout._part_child', array('parts' => $part->children()))
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <form role="form" class="form-horizontal" method="post">
                            @csrf
                            <p></p>
                            <div class="alert alert-danger" id="no_doctor_alert" style="display: none;">
                                در ابن بخش هنوز پزشکی اضافه نشده است
                            </div>
                            <div class="form-group rtl yekan" style="display: none;" id="doctor_list">
                                <div class="col-md-11">
                                    <select id="multiselect4" name="user_id">
                                        @foreach($data['admin_list'] as $admin_user)
                                            <option value="{{ $admin_user->id }}"
                                                    class="all-doctor-options @foreach($admin_user->appointmentParts as $part) parts-{{ $part->id }} @endforeach">{{ $admin_user->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label for="multiselect4" class="col-md-1 control-label">انتخاب پزشک</label>
                            </div>
                            <hr class="alt short"/>
                            <div class="section row mbn">
                                <div class="col-sm-8">
                                    <p class="text-left yekan" style="display: none;" id="btn_action">
                                        @can('add_admin_appointment_user')
                                            <button class="btn btn-warning yekan" name="_method" value="PATCH"
                                                    id="add_appointment" type="button">افزودن نوبت
                                            </button>
                                        @endcan
                                        @can('edit_admin_appointment')
                                            <button class="btn btn-success yekan" name="_method" value="PATCH"
                                                    id="exceptionDay" type="button">روز های خاص
                                            </button>
                                        @endcan
                                        @can('add_admin_appointment')
                                            <button class="btn btn-primary yekan" type="button" id="createOrUpdate">
                                                ویرایش
                                            </button>
                                        @endcan
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('add_admin_appointment')
        <form action="{{ route('admin.appointment.create') }}" id="edit_update_form">
            <input type="hidden" name="part_id" value="" id="part_id">
            <input type="hidden" name="doctor_id" value="" id="doctor_id">
        </form>
    @endcan
    @can('edit_admin_appointment')
        <form action="{{ route('admin.appointment.edit',1) }}" id="edit_update_form2">
            <input type="hidden" name="part_id" value="" id="part_id2">
            <input type="hidden" name="doctor_id" value="" id="doctor_id2">
        </form>
    @endcan
    @can('add_admin_appointment_user')
        <form action="{{ route('admin.appointment.add') }}" id="edit_update_form3">
            <input type="hidden" name="part_id" value="" id="part_id3">
            <input type="hidden" name="doctor_id" value="" id="doctor_id3">
        </form>
    @endcan
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
    <script>
        jQuery(document).ready(function () {
            let part_id = 0;
            // Init FancyTree - Default
            $("#tree").fancytree({
                icons: false, // Display node icons.
                clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
                click: function (event, data) {
                    const node = data.node;
                    part_id = node.key;
                    if ($('.parts-' + part_id).length >= 1) {
                        $('.all-doctor-options').hide();
                        $('.parts-' + part_id).show();
                        $('#multiselect4').val($('.parts-' + part_id).first().attr('value'));
                        $('#no_doctor_alert').hide();
                        if ($('#btn_action').is(":hidden")) {
                            $('#btn_action').slideDown();
                            $('#doctor_list').slideDown();
                        }
                    } else {
                        $('#no_doctor_alert').slideDown();
                        $('#btn_action').hide();
                        $('#doctor_list').hide();
                    }
                },
            });
            @can('add_admin_appointment')
                $('#createOrUpdate').on('click', function () {
                    $('#doctor_id').val($('#multiselect4').val());
                    $('#part_id').val(part_id);
                    $('#edit_update_form').submit();
                });
            @endcan
            @can('edit_admin_appointment')
                $('#exceptionDay').on('click', function () {
                    $('#doctor_id2').val($('#multiselect4').val());
                    $('#part_id2').val(part_id);
                    $('#edit_update_form2').submit();
                });
            @endcan
            @can('add_admin_appointment_user')
                $('#add_appointment').on('click', function () {
                    $('#doctor_id3').val($('#multiselect4').val());
                    $('#part_id3').val(part_id);
                    $('#edit_update_form3').submit();
                });
            @endcan
        });
    </script>
@endpush
