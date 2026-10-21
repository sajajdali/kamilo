@extends('admin.layout.layout')
@section('title',$title ?? 'شماره های بسته شده')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right">
            <ul class="nav nav-list nav-list-topbar pull-right yekan">
                <li class="active">
                    <a href="#">تماس های ورودی</a>
                </li>

            </ul>
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

                    <h4 class="mt5 text-muted fw500">فیلتر کردن </h4>

                    <hr class="short">

                    <div class="section mb15">
                        <label for="mobile" class="field prepend-icon">
                            <input value="{{ request()->get('mobile') ?? '' }}"
                                   type="text"
                                   name="mobile" id="mobile"
                                   class="gui-input yekan" placeholder="شماره موبایل">
                            <label for="mobile" class="field-icon"><i class="fa fa-tag"></i>
                            </label>
                        </label>
                    </div>



                    <hr class="short">

                    <div class="section row">
                        <div class="col-sm-12">
                            <input type="hidden" name="act" value="search"/>
                            <button class="btn btn-default btn-sm ph25 yekan"
                                    type="submit">جستجو
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </aside>
        <!-- end: .tray-right -->

        <div class="tray tray-center p25 va-t posr">
            @include('admin.layout.alert')
            <form method="post" id="change_news">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="panel">
                    <div class="panel-body pn">

                        <table class="table admin-form theme-warning tc-checkbox-1 fs13">
                            <thead>
                            <tr class="bg-light yekan rtl">
                                <th class="txt_center">عملیات</th>
                                <th class="txt_center">تاریخ تماس</th>
                                <th class="text-center">شماره</th>
                                <th class="text-center">#</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($blackLists as $blackList)
                                <tr class="text-center rtl yekan">
                                    <td>
                                        <div class="btn-group text-right yekan rtl">
                                            <button type="button"
                                                    class="btn btn-info br2 btn-xs fs12 dropdown-toggle yekan"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                عملیات
                                                <span class="caret mr5"></span>
                                            </button>

                                            <ul class="dropdown-menu txt_right" role="menu">
                                                <li>

                                                    @can('delete_admin_black_list')
                                                        <a class="delete_user_appointment"
                                                           data-effect="mfp-rotateLeft"
                                                           href="{{ route('admin.blackList.destroy', $blackList->id) }}">
                                                            حذف
                                                        </a>
                                                    @endcan
                                                </li>
                                            </ul>

                                        </div>
                                    </td>
                                    <td>{{ jdate('H:i | Y/m/d',$blackList->created_at->timestamp,'','','en') }}</td>
                                    <td>{{ $blackList->mobile }}</td>
                                    <td>{{ $blackList->id }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        @if(count($blackLists))
                            <div style="text-align: center;">{{ $blackLists->links() }}</div>
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
                    <span class="panel-title">حذف تماس</span>
                </div>
                <div class="panel-body" style="text-align: right">
                    <h3 class="mt5">آیا از حذف این شماره اطمینان دارید؟</h3>
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

    <script type="text/javascript">

        jQuery(document).ready(function () {
            //cancel group

            const startDate = $("#start_date");
            const endDate = $("#end_date");
            const startClock = $("#start_clock");
            const endClock = $("#end_clock");
            startDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_start_date',
                initialValue: false
            });
            startDate.val("{{ request()->get('start_date') }}");
            endDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_end_date',
                initialValue: false
            });
            endDate.val("{{ request()->get('end_date') }}");
            startClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            endClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            //validate form

            function getTimeInDay(value) {
                if (value.length !== 0) {
                    let timeBase = value.split(":");
                    const min = parseInt(timeBase[1]);
                    const hr = parseInt(timeBase[0]);
                    return (hr * 60) + min;
                } else {
                    return 0;
                }
            }


        });

    </script>


@endpush

@push('scripts')


    <style>
        .survey_player audio {
            width: 100%;
        }

        @keyframes shadow-pulse {
            0% {
                box-shadow: 0 0 0 5px rgba(56, 172, 56, .7)
            }
            100% {
                box-shadow: 0 0 0 5px rgba(110, 172, 101, .1)
            }
        }

        @keyframes shadow-pulse-big {
            0% {
                box-shadow: 0 0 0 5px rgba(0, 0, 0, 0)
            }
            100% {
                box-shadow: 0 0 0 5px rgba(0, 0, 0, 0)
            }
        }

        .item-online-icon {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            position: relative;
            top: 3px;
            margin-left: 3px;
            display: inline-block;
            background: rgba(56, 172, 56, 1);
            animation: shadow-pulse 1s infinite;
        }

        #fancytree-drop-marker, span.fancytree-checkbox, span.fancytree-drag-helper-img,
        span.fancytree-empty, span.fancytree-expander,
        span.fancytree-icon, span.fancytree-radio, span.fancytree-vline {
            transform: rotate(90deg);
        }

        #panel_header_clickable {
            cursor: pointer;
        }
    </style>

    <style>
        .caption_table {
            padding: 10px 5px;
            text-align: right;
        }

        .caption_table span {
            margin-left: 10px;
            cursor: pointer;
        }

        .caption_table span i {
            margin-left: 2px;
        }

        #choice_all {
            cursor: pointer;
        }
    </style>


    <!-- Page Javascript -->

    <script type="text/javascript">
        jQuery(document).ready(function () {
            //cancel group
            $("#btn-cancel").on('click', function () {
                const smsStatus = $('input[name="sms_status"]:checked').val();

                const groupForm = $("#group_act");
                groupForm.append("<input type='hidden' name='act' value='cancel' />");
                groupForm.append("<input type='hidden' name='sms_status' value='" + smsStatus + "' />");
                groupForm.submit();
            });
            $('#btn-cancel-group').on('click', function (e) {
                e.preventDefault();
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-cancel-group'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });
            //group delete
            $('#delete-group-approve').on('click', function () {
                const groupForm = $("#group_act");
                groupForm.append("<input type='hidden' name='act' value='delete' />");
                groupForm.submit();
            });
            $('#btn-delete-group').on('click', function (e) {
                e.preventDefault();
                $.magnificPopup.open({
                    removalDelay: 500,
                    items: {
                        src: '#modal-delete-group'
                    },
                    callbacks: {
                        beforeOpen: function (e) {
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });
            //header panel
            $('.multi_checked_item').on('change', function () {
                const headerMenu = $('#header_action');
                if ($('.multi_checked_item:checked').length >= 1) {
                    if (headerMenu.is(":hidden")) {
                        headerMenu.slideDown();
                    }
                } else {
                    if (headerMenu.is(":visible")) {
                        headerMenu.slideUp();
                    }
                }
            });
            //panel
            const panelHeader = $("#panel_header_clickable");
            const advanceSearch = $("#advance_search");
            const iTag = panelHeader.find('i');
            panelHeader.on('click', function () {
                if (iTag.hasClass("fa-plus-square")) {
                    iTag.removeClass("fa-plus-square").addClass("fa-minus-square");
                } else {
                    iTag.addClass("fa-plus-square").removeClass("fa-minus-square");
                }
                advanceSearch.slideToggle();
            });
            @if(request()->act != null && request()->act == "search")
            panelHeader.click();
            @endif
            //filter part id
            @if(request()->part_id != null && (int) request()->part_id != 0)
            $('#part_id').val({{ request()->part_id }});
            @endif
            @if(request()->doctor_id != null && (int) request()->doctor_id != 0)
            $("#doctor_id").val({{ request()->doctor_id }});
            @endif
            const filterSpan = $('.caption_table span');
            filterSpan.mouseenter(function () {
                const csl = $(this).data('filter');
                $('.data-visit').css('opacity', '0.3');
                $('tr.' + csl).css('opacity', '1');
            });
            filterSpan.mouseleave(function () {
                $('.data-visit').css('opacity', '1');
            });
            filterSpan.click(function () {
                const csl = $(this).data('filter');
                $('tr.' + csl).find('input[type="checkbox"]').attr("checked", "checked");
            });
            //caleder action
            const startDate = $("#start_date");
            const endDate = $("#end_date");
            const startClock = $("#start_clock");
            const endClock = $("#end_clock");
            startDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_start_date',
                initialValue: false
            });
            startDate.val("{{ request()->get('start_date') }}");
            endDate.pDatepicker({
                format: 'YYYY/MM/DD',
                altField: '#alt_end_date',
                initialValue: false
            });
            endDate.val("{{ request()->get('end_date') }}");
            startClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            endClock.mask('99:99').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });
            //validate form
            $('#admin-form').on('submit', function () {
                let start = getTimeInDay(startClock.val());
                let end = getTimeInDay(endClock.val());
                if (start > end) {
                    scrollToItem(startClock, function () {
                        startClock.focus()
                    });
                    displayErrorNotify("زمان شروع و پایان به درستی وارد نشده است");
                    return false;
                }
                // if (startDate.val().length >= 1 && endDate.val().length >= 1) {
                //     if ($("#alt_start_date").val() > $("#alt_end_date").val()) {
                //         scrollToItem(startClock, function () {
                //             endDate.focus()
                //         });
                //         displayErrorNotify("تاریخ شروع و پایان به درستی انتخاب نشده است");
                //         return false;
                //     }
                // }
            });
            $('.delete_user_appointment').on('click', function (e) {
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
                            this.st.mainClass = $(".delete_user_appointment").attr('data-effect');
                        }
                    },
                    midClick: true
                });
            });

            function getTimeInDay(value) {
                if (value.length !== 0) {
                    let timeBase = value.split(":");
                    const min = parseInt(timeBase[1]);
                    const hr = parseInt(timeBase[0]);
                    return (hr * 60) + min;
                } else {
                    return 0;
                }
            }

            function scrollToItem(item, onCompleteScroll) {
                $([document.documentElement, document.body]).animate({
                    scrollTop: item.offset().top - 164
                }, 800, function () {
                    onCompleteScroll();
                });
            }

            function displayErrorNotify(message) {
                new PNotify({
                    title: 'خطا!',
                    text: message,
                    shadow: true,
                    opacity: '1',
                    addclass: 'stack_bar_top rtl yekan',
                    type: 'warning',
                    stack: {
                        "dir1": "down",
                        "dir2": "right",
                        "push": "top",
                        "spacing1": 0,
                        "spacing2": 0
                    },
                    width: "100%",
                    delay: 3000,
                });
            }

            let statusCheckAll = false;
            $('#choice_all').on('click', function () {
                $('.multi_checked_item').each(function () {
                    if (statusCheckAll) {
                        if ($(this).is(":checked")) {
                            $(this).click();
                        }
                    } else {
                        if (!$(this).is(":checked")) {
                            $(this).click();
                        }
                    }
                });
                statusCheckAll = !statusCheckAll;
            });
        });

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            return !(charCode > 31 && (charCode < 48 || charCode > 57));
        }


        $('body').on('click', '.survey_user', function () {
            var audio = $("#survey_player_src");
            var appointmentSrc = $(this).attr('data-src');
            var dataScore = $(this).attr('data-score');
            $("#score").text(dataScore);
            $("#ogg_src").attr('src', appointmentSrc);
            audio[0].pause();
            audio[0].load();//suspends and restores all audio element
        });

    </script>
@endpush
