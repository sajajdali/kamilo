@extends('admin.layout.layout')
@section('title',$title ?? 'بخش‌ها')
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
        <div class="topbar-left">
            @can('add_admin_appointment_part')
                <a href="{{ route('admin.appointment_part.create') }}"
                   class="btn btn-default btn-sm light fw600 ml10 rtl yekan ">
                    <span class="fa fa-plus pl5"></span>اضافه کردن بخش جدید
                </a>
            @endcan

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
            <div class="panel">
                <div class="panel-body pn">

                    <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                        <thead>
                        <tr class="bg-light yekan rtl ">

                            <th class="txt_center">عملیات</th>
                            <th class="txt_center">پزشکان بخش</th>
                            <th class="txt_center">ایدی API</th>
                            <th class="text-center">عنوان</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($parts))
                            @foreach($parts as $part)
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group text-right yekan rtl txt_right">
                                            @if($part->status)
                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    فعال
                                                    @else
                                                        <button type="button"
                                                                class="btn btn-danger br2 btn-xs fs12 dropdown-toggle yekan"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            غیرفعال
                                                            @endif

                                                            <span class="caret mr5"></span>
                                                        </button>
                                                        <ul class="dropdown-menu txt_right" role="menu">
                                                            @can('edit_admin_appointment_part')
                                                                <li>
                                                                    <a href="{{ route('admin.appointment_part.edit', $part->id) }}">ویرایش</a>
                                                                </li>
                                                            @endcan
                                                            @can('delete_admin_appointment_part')
                                                                <li>
                                                                    <a class="delete_blog"
                                                                       data-effect="mfp-rotateLeft"
                                                                       href="{{ route('admin.appointment_part.destroy', $part->id) }}">حذف</a>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                </button>

                                        </div>
                                    </td>
                                    <td class="rtl yekan txt_center">{{ number_format($part->users->count()) }}</td>
                                                                        <td class="rtl yekan txt_center">{{ $part->api_id ?? "-" }}</td>
                                    <td class="rtl yekan">
                                        @if($part->children()->exists())
                                            <span class="fa fa-plus-square text-success open_tress state_cls"
                                                  data-pid="{{ $part->id }}"></span>
                                        @endif
                                        {{ $part->title }}
                                    </td>
                                </tr>
                                @if($part->children()->exists())
                                    @include('appointment::admin.appointment_part._part_tr', array('parts' => $part,'under_line_counter'=>1))
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(sizeof($parts))
                        <center>{{ $parts->links() }}</center>
                    @endif
                </div>
            </div>
        </div>

    </section>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <form action="" method="POST" id="delete_link">
            @method('DELETE')
            @csrf
            <div class="panel">
                <div class="panel-heading" style="text-align: right">
								<span class="panel-icon"><i class="fa fa-pencil"></i>
								</span>
                    <span class="panel-title">حذف بخش</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این بخش اطمینان دارید؟</h3>
                </div>
                <div class="panel-footer text-left">

                    <button class="btn btn-primary" type="submit">بله حذف کن</button>
                </div>
            </div>
        </form>

    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <style>
        .open_tress{
            cursor: pointer;
        }
    </style>
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>


    <script type="text/javascript">
        jQuery(document).ready(function () {
            $('.delete_blog').on('click', function (e) {
                e.preventDefault();
                const link = $(this).attr('href');
                $('#delete_link').attr('action', link);
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-panel'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_blog").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true
                });
            });

            $('.open_tress').on('click',function(){
                const pid = $(this).data('pid');
                if($(this).hasClass('state_cls')){
                    $('.part_children_' + pid).slideDown();
                    $(this).removeClass('state_cls fa-plus-square').addClass('state_opn fa-minus-square');
                }else{
                    $(this).addClass('state_cls fa-plus-square').removeClass('state_opn fa-minus-square');
                    $('.part_children_' + pid).each(function () {
                        $(this).find('.state_opn').click();
                        $(this).hide();
                    })
                }
            });
        });
    </script>
@endpush
