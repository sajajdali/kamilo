@extends('admin.layout.layout')
@section('title',$title ?? 'پیامک نوبت‌دهی')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">مدیریت پیامک‌های نوبت‌دهی</a>
                </li>
            </ul>
        </div>
        <div class="topbar-left">
            @can('add_admin_appointment_sms')
                <a href="{{ route('admin.appointment_sms.create') }}"
                   class="btn btn-default btn-sm light fw600 ml10 rtl yekan ">
                    <span class="fa fa-plus pl5"></span>اضافه کردن پیامک جدید
                </a>
            @endcan

        </div>
    </header>
    <!-- End: Topbar -->

    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <!-- end: .tray-center -->

        <!-- begin: .tray-right -->
        <aside class="tray tray-right tray290 va-t pn" data-tray-height="match">

            <form>
                <!-- menu quick links -->
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500">فیلتر کردن</h4>

                    <hr class="short">
                    <h6 class="fw400">بخش</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-part" name="part_id" class="yekan">
                                <option value="0">فیلتر براساس بخش</option>
                                @foreach($parts as $part)
                                    <option {{ (request()->part_id == $part->id ? 'selected="selected"' : '') }} value="{{ $part->id }}">
                                        {{ $part->title }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="arrow"></i>
                        </label>
                    </div>
                    <h6 class="fw400">پزشک</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-doctor" name="doctor_id" class="yekan">
                                <option value="0">فیلتر براساس پزشک</option>
                                @foreach($doctors as $doctor)
                                    <option {{ (request()->doctor_id == $doctor->id ? 'selected="selected"' : '') }} value="{{ $doctor->id }}">
                                        {{ $doctor->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="arrow"></i>
                        </label>
                    </div>


                    <hr class="short">

                    <div class="section row">
                        <div class="col-sm-12">
                            <input type="hidden" name="act" value="search"/>
                            <button class="btn btn-default btn-sm ph25 yekan" type="submit">جستجو</button>
                        </div>
                    </div>

                </div>
            </form>
        </aside>
        <!-- end: .tray-right -->

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
            <form method="post" id="change_news">
                <div class="panel">
                    <div class="panel-body pn">

                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">متن</th>
                                <th class="text-center">ارسال در</th>
                                <th class="txt_center">بخش</th>
                                <th class="text-center">پزشک</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($sms))
                                @foreach($sms as $nn)
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group text-right yekan rtl txt_right">
                                                @if($nn->status)
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
                                                                @can('edit_admin_appointment_sms')
                                                                    <li>
                                                                        <a href="{{ route('admin.appointment_sms.edit', $nn->id) }}">ویرایش</a>
                                                                    </li>
                                                                @endcan
                                                                @can('delete_admin_appointment_sms')
                                                                    <li>
                                                                        <a class="delete_blog"
                                                                           data-effect="mfp-rotateLeft"
                                                                           href="{{ route('admin.appointment_sms.destroy', $nn->id) }}">حذف</a>
                                                                    </li>
                                                                @endcan
                                                            </ul>
                                                    </button>

                                            </div>
                                        </td>
                                        <td class="txt_center yekan">{{ $nn->message }}</td>
                                        <td class="txt_center yekan rtl">{{ ($nn->day_before ==0 ) ? "روز نوبت دهی" : number_format($nn->day_before)." روز قبل" }}</td>
                                        <td class="rtl yekan">{{ $nn->appointmentPart->title }}</td>
                                        <td class="rtl yekan">{{ $nn->user->full_name }}</td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        @if(sizeof($sms))
                            <center>{{ $sms->links() }}</center>
                        @endif
                    </div>
                </div>
            </form>
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
                    <span class="panel-title">حذف پیامک</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این پیامک اطمینان دارید؟</h3>
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
