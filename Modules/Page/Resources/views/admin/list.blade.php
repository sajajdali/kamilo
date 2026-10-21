@extends('admin.layout.layout')
@section('title',$title ?? 'مدیریت صفحات')
@section('content')
	
	<!-- Start: Topbar -->
	<header id="topbar" class="ph10">
		@can('add_admin_pages')
			<div class="topbar-left">
				<a href="{{ route('admin.pages.create') }}"
				   class="btn btn-default btn-sm light fw600 ml10 rtl yekan "><span class="fa fa-plus pl5"></span>اضافه
					کردن صفحه جدید</a>
			</div>
		@endcan
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
					
					<h4 class="mt5 text-muted fw500">فیلتر کردن خبرها</h4>
					
					<hr class="short">
					
					<div class="section mb15">
						<label for="title" class="field prepend-icon">
							<input value="{{ ((isset($_GET['title'])) ? $_GET['title'] : '') }}" type="text"
							       name="title" id="title" class="gui-input yekan" placeholder="عنوان صفحه">
							<label for="title" class="field-icon"><i class="fa fa-tag"></i>
							</label>
						</label>
					</div>
					<h6 class="fw400">تاریخ ارسال</h6>
					<div class="section row mb15">
						<div class="col-md-6">
							<label class="field prepend-icon" for="end_at">
								<input type="text" placeholder="تا تاریخ" class="gui-input yekan" id="end_at" name="to">
							
							</label>
						</div>
						<div class="col-md-6">
							<label class="field prepend-icon" for="start_at">
								<input type="text" placeholder="از تاریخ" class="gui-input yekan" id="start_at"
								       name="from">
							
							</label>
						</div>
					</div>
					<h6 class="fw400"></h6>
					<div class="section mb15">
						<label class="field select">
							<select id="filter-categories" name="admin_id" class="yekan">
								<option value="0">فیلتر کردن براساس مدیر</option>
							
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
				@csrf
				{{ method_field('PUT') }}
				<div class="panel">
					<div class="panel-body pn">
						
						<table class="table admin-form theme-warning tc-checkbox-1 fs13">
							<thead>
							<tr class="bg-light yekan rtl ">
								
								<th class="txt_center">عملیات</th>
								<th class="txt_center">تاریح بروزرسانی</th>
								<th class="txt_center">تاریخ ارسال</th>
								<th class="txt_center">دفعات بازدید</th>
								<th class="text-center">عنوان صفحه</th>
							</tr>
							</thead>
							<tbody>
							@if(sizeof($pages))
								@foreach($pages as $page)
									<tr>
										<td class="text-right">
											<div class="btn-group yekan rtl text-center">
												<button type="button"
												        class="btn btn-info br2 btn-xs fs12 dropdown-toggle yekan"
												        data-toggle="dropdown" aria-expanded="false">
													عملیات
													
													<span class="caret mr5"></span>
												</button>
												<ul class="dropdown-menu txt_right" role="menu">
													<li>
														<a target="_blank" href="{{ url($page->link()) }}">مشاهده</a>
													</li>
													@can('edit_admin_pages')
														<li>
															<a href="{{ route('admin.pages.edit',['page'=>$page->id]) }}">ویرایش</a>
														</li>
													@endcan
													@can('delete_admin_pages')
														<li>
															<a class="delete_page" data-effect="mfp-rotateLeft"
															   href="{{ route('admin.pages.destroy',['page'=>$page->id]) }}">حذف</a>
														</li>
													@endcan
												</ul>
											</div>
										</td>
										<td class="txt_center yekan rtl">{{ jdate('d-F-Y',strtotime($page->updated_at)) }}</td>
										<td class="txt_center yekan rtl">{{ jdate('d-F-Y',strtotime($page->created_at)) }}</td>
										<td class="txt_center yekan">{{ number_format($page->counter) }}</td>
										<td class="rtl yekan txt_center">{{ $page->getTitle('fa') }}</td>
									</tr>
								@endforeach
							@endif
							</tbody>
						</table>
						@if(sizeof($pages))
							<center>{{ $pages->links() }}</center>
						@endif
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
				<span class="panel-title">حذف صفحه</span>
			</div>
			<div class="panel-body" style="text-align: right">
				<h3 class="mt5">آیا از حذف این صفحه اطمینان دارید؟</h3>
			</div>
			<div class="panel-footer text-left">
				<form action="" method="POST" id="deleteForm">
					@csrf
					@method("DELETE")
					<button class="btn btn-primary" type="submit">بله حذف کن</button>
				</form>
			</div>
		</div>
	</div>
	<!-- End: Content -->
@endsection
@push('scripts')
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
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
	<link rel="stylesheet" href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
	
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
                    alert_danger('ایتدا یک یا چند خبر را انتخاب نمایید.');
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
                                var Animation = $(".delete_page").attr('data-effect');
                                this.st.mainClass = Animation;
                            }
                        },
                        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                    });
                } else {
                    alert_danger('ابتدا یک عملیات انتخاب نمایید.');
                }
            });
            $('.delete_page').on('click', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                $("#deleteForm").attr('action', link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function (e) {
                            var Animation = $(".delete_page").attr('data-effect');
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
