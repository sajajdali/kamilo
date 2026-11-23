@extends('admin.layout.layout')
@section('title',$title ?? 'مدیریت وعده‌ها')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li {{ (Request::is('admin/meal') ? 'class=active' : '') }}>
                    <a href="#">داشبورد وعده‌های غذایی</a>
                </li>

            </ul>
        </div>
        <div class="topbar-left">
            @can('add_admin_meal_category')
                <a href="{{ url('admin/meal/category') }}"
                   class="btn btn-default btn-sm light fw600 ml10 rtl yekan"><span class="fa fa-plus pl5"></span> اضافه
                    کردن موضوع</a>
            @endcan
            @can('add_admin_meal')
                <a href="{{ url('admin/meal/add') }}" class="btn btn-default btn-sm light fw600 ml10 rtl yekan "><span
                            class="fa fa-plus pl5"></span> اضافه کردن وعده</a>
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
                <input type="hidden" name="act" value="search"/>
                <div class="p20 admin-form rtl yekan">

                    <h4 class="mt5 text-muted fw500">فیلتر کردن وعده ها</h4>

                    <hr class="short">

                    <h6 class="fw400">برای وعده</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="forMeal" class="yekan">
                                <option value="0">فیلتر براساس وعده</option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==1 ? 'selected="selected"' : '') }} value="1">
                                    صبحانه
                                </option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==2 ? 'selected="selected"' : '') }} value="2">
                                    میان وعده ۱
                                </option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==3 ? 'selected="selected"' : '') }} value="3">
                                    ناهار
                                </option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==4 ? 'selected="selected"' : '') }} value="4">
                                    میان وعده ۲
                                </option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==5 ? 'selected="selected"' : '') }} value="5">
                                    شام
                                </option>
                                <option {{ (isset($_GET['forMeal']) && $_GET['forMeal']==6 ? 'selected="selected"' : '') }} value="6">
                                    میان وعده ۳
                                </option>
                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>
                    <h6 class="fw400">محدودیت کالری</h6>
                    <div class="section row mb15">
                        <div class="col-md-6">
                            <label class="field prepend-icon" for="end_at">
                                <input type="text" placeholder="تا" class="gui-input yekan" id="sa" name="to"
                                       value="{{ ((isset($_GET['to'])) ? $_GET['to'] : '') }}">

                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="field prepend-icon" for="start_at">
                                <input type="text" placeholder="از" class="gui-input yekan" id="start2_at" name="from"
                                       value="{{ ((isset($_GET['from'])) ? $_GET['from'] : '') }}">
                            </label>
                        </div>
                    </div>

                    <h6 class="fw400">نوع وعده</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="mealType" class="yekan">
                                <option value="0">فیلتر براساس نوع وعده</option>
                                <option {{ (isset($_GET['mealType']) && $_GET['mealType']==1 ? 'selected="selected"' : '') }} value="1">
                                    برحسب کالری
                                </option>
                                <option {{ (isset($_GET['mealType']) && $_GET['mealType']==2 ? 'selected="selected"' : '') }} value="2">
                                    واحدی
                                </option>
                                <option {{ (isset($_GET['mealType']) && $_GET['mealType']==3 ? 'selected="selected"' : '') }} value="3">
                                    روزانه
                                </option>
                            </select>
                            <i class="arrow double"></i>
                        </label>
                    </div>

                    <h6 class="fw400">مربوط به موضوع</h6>
                    <div class="section mb15">
                        <label class="field select">
                            <select id="filter-categories" name="mealCategory" class="yekan">
                                <option value="0">فیلتر براساس نوع وعده</option>
                                @foreach($data['category'] as $cat)
                                    <option {{ (isset($_GET['mealCategory']) && $_GET['mealCategory']==$cat->id ? 'selected="selected"' : '') }} value="{{ $cat->id }}">
                                        @if($cat->parent != 0)
                                            {{ \Modules\Diet\Entities\Meal\Meal_category::find($cat->parent)->name }} -
                                        @endif
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="arrow double"></i>
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
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="panel">
                    <div class="panel-body pn">
                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl ">

                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تاریخ ارسال</th>
                                <th class="txt_right">دستور</th>
                                <th class="txt_right">کالری</th>
                                <th class="txt_center">وعده</th>
                                <th class="text-center">نوع</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($meal))
                                @foreach($meal as $nn)
                                    <tr>
                                        <td class="text-right">
                                            <div class="btn-group text-right yekan rtl txt_right">

                                                <button type="button"
                                                        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    عملیات
                                                    <span class="caret mr5"></span>
                                                </button>
                                                <ul class="dropdown-menu txt_right" role="menu">
                                                    @can('edit_admin_meal')
                                                        <li>
                                                            <a href="{{ url('admin/meal/edit/'.($nn->parent > 0 ? $nn->parent : $nn->id)) }}">ویرایش</a>
                                                        </li>
                                                    @endcan
                                                    @can('delete_admin_meal')
                                                        <li>
                                                            <a class="delete_meal" data-effect="mfp-rotateLeft"
                                                               href="{{ url('admin/meal/delete/'.$nn->id) }}">حذف</a>
                                                        </li>
                                                    @endcan

                                                </ul>
                                            </div>
                                        </td>
                                        <td class="txt_center yekan rtl">{{ jdate('d-F-Y',strtotime($nn->created_at)) }}</td>
                                        <td class="rtl yekan">{!!  \Illuminate\Support\Str::limit(strip_tags($nn->content), 50) !!}</td>
                                        <td class="rtl yekan">
                                            @if($nn->meal_type == 2 && $nn->metas()->where('meta_key','total_calorie')->exists())
                                                {{ $nn->metas()->where('meta_key','total_calorie')->first()->meta_value }}
                                            @else
                                                {{ $nn->calorie }}
                                            @endif
                                        </td>
                                        <td class="rtl yekan">
                                            @if($nn->meal_type == 2)
                                                -
                                            @else
                                                {{ currentMeal($nn->meal) }}
                                            @endif
                                        </td>
                                        <td class="rtl yekan">
                                            @if ($nn->meal_type == 1)
                                                بر حسب کالری
                                            @elseif($nn->meal_type == 2)
                                                واحدی
                                            @else
                                                روزانه
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <center>{{ $meal->links() }}</center>
                    </div>
                </div>
            </form>
        </div>

    </section>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">حذف وعده</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این وعده اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a id="delete_link">
                    <button class="btn btn-primary" type="button">بله حذف کن</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
    <!-- Panel popup -->
    <div id="modal-panel2" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

        <div class="panel">
            <div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                <span class="panel-title">عملیات گروهی</span>
            </div>
            <div class="panel-body" style="text-align: right">
                <h3 class="mt5">آیا از حذف این وعده اطمینان دارید؟</h3>
            </div>
            <div class="panel-footer text-left">
                <a>
                    <button id="sub_form" class="btn btn-primary" type="button">بله</button>
                </a>
            </div>
        </div>
    </div>
    <!-- End: Content -->
@endsection
@push('scripts')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
    <script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
    <link rel="stylesheet"
          href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("#start_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#start_at").val("{{ ((isset($_GET['from'])) ? $_GET['from'] : '') }}");
            $("#end_at").pDatepicker({
                format: 'YYYY/MM/DD',
            });
            $("#end_at").val("{{ ((isset($_GET['to'])) ? $_GET['to'] : '') }}");
            // Init jQuery masked inputs
            $('#start_at').mask('9999/99/99');
            $('#end_at').mask('9999/99/99');
            $('#sub_form').on('click', function (e) {
                $('#change_news').submit();
            });
            $('#group_change').on('click', function (e) {
                e.preventDefault();
                var selected_item = $('#change_news').find('input[name="blog_id[]"]:checked');
                if (selected_item.length == 0) {
                    alert_danger('ایتدا یک یا چند وعده را انتخاب نمایید.');
                    return false;
                }
                var val = $('#filter-category').val();
                var text = $('#filter-category').find('option[value="' + val + '"]').text();
                $('#modal-panel2').find('.mt5').text('آیا از ' + text + ' همه اطمینان دارید؟');
                if (val != 0) {
                    $.magnificPopup.open({
                        removalDelay: 500, //delay removal by X to allow out-animation,
                        items: {
                            src: '#modal-panel2'
                        },
                        // overflowY: 'hidden', //
                        callbacks: {
                            beforeOpen: function (e) {
                                var Animation = $(".delete_blog").attr('data-effect');
                                this.st.mainClass = Animation;
                            }
                        },
                        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                    });
                } else {
                    alert_danger('ابتدا یک عملیات انتخاب نمایید.');
                }
            });
            $('.delete_blog').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $('#delete_link').attr('href', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_blog").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });

            function alert_danger(text) {
                var noteStyle = 'danger';
                var noteShadow = true;
                var noteOpacity = '1';
                var noteStack = "stack_bar_bottom";
                var width = "70%";
                var Stacks = {
                    stack_top_right: {
                        "dir1": "down",
                        "dir2": "left",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_top_left: {
                        "dir1": "down",
                        "dir2": "right",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bottom_left: {
                        "dir1": "right",
                        "dir2": "up",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bottom_right: {
                        "dir1": "left",
                        "dir2": "up",
                        "push": "top",
                        "spacing1": 10,
                        "spacing2": 10
                    },
                    stack_bar_top: {
                        "dir1": "down",
                        "dir2": "right",
                        "push": "top",
                        "spacing1": 0,
                        "spacing2": 0
                    },
                    stack_bar_bottom: {
                        "dir1": "up",
                        "dir2": "right",
                        "spacing1": 0,
                        "spacing2": 0
                    },
                    stack_context: {
                        "dir1": "down",
                        "dir2": "left",
                        "context": $("#stack-context")
                    },
                };
                new PNotify({
                    title: 'خطا',
                    text: text,
                    shadow: noteShadow,
                    opacity: noteOpacity,
                    addclass: noteStack,
                    type: noteStyle,
                    stack: Stacks[noteStack],
                    width: width,
                    delay: 3000,
                });
            }
        });
    </script>
@endpush
