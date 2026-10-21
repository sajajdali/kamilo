@extends('admin.layout.layout')
@section('title',$title ?? 'موضوعات')
@section('content')

	<!-- Start: Topbar -->
	<header id="topbar" class="ph10">
		<div class="topbar-right">
			<ul class="nav nav-list nav-list-topbar pull-right yekan">
				<li>
					<a href="{{ route('admin.blog.index') }}">اخبار</a>
				</li>
				<li {{ (Request::is('admin/blog/category') ? 'class=active' : '') }}>
					<a href="{{ route('admin.blog.category.index') }}">موضوعات</a>
				</li>
				@can('view_admin_blog_comments')
					<li>
						<a href="{{ route('admin.blog.comments.index') }}">نظرات</a>
					</li>
				@endcan
			</ul>
		</div>
		<div class="topbar-left">
			@can('view_admin_blog_category')
				<a href="{{ route('admin.blog.category.create') }}" class="btn btn-default btn-sm light fw600 ml10 rtl yekan">
					<span class="fa fa-plus pl5"></span> اضافه کردن موضوع
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

			<!-- menu quick links -->
			<form method="get">
				<div class="p20 admin-form rtl yekan">

					<h4 class="mt5 text-muted fw500">فیلتر کردن موضوعات</h4>

					<hr class="short">

					<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['title']) ? $_GET['title'] : '') }}" type="text" name="title" id="order-id" class="gui-input yekan" placeholder="عنوان موضوع">
							<label for="order-id" class="field-icon"><i class="fa fa-tag"></i>
							</label>
						</label>
					</div>

					<h6 class="fw400">موضوع اصلی</h6>
					<div class="section mb15">
						<label class="field select">
							<select id="filter-categories" name="group" class="yekan">
								<option value="0" selected="selected">فیلتر کردن براساس گروه اصلی</option>
								@foreach($all_cat as $cat)
									<option value="{{ $cat->id }}" >{{ $cat->title }}</option>
								@endforeach

							</select>
							<i class="arrow double"></i>
						</label>
					</div>



					<hr class="short">

					<div class="section row">
						<div class="col-sm-12">
							<button class="btn btn-default btn-sm ph25 yekan" type="submit">جستجو</button>
							<input type="hidden" name="page" value="1">
							<input type="hidden" name="act" value="search">
						</div>
					</div>

				</div>
			</form>
		</aside>
		<!-- end: .tray-right -->

		<div class="tray tray-center p25 va-t posr">
			@if (\Illuminate\Support\Facades\Session::has('sys_error'))
				<div class="alert alert-danger alert-dismissable rtl yekan">
					<button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
					<i class="fa fa-remove pr10"></i>
					<strong>خطا!</strong>
					{{ \Illuminate\Support\Facades\Session::get('sys_error') }}
				</div>
			@endif
			@if(\Illuminate\Support\Facades\Session::has('successful'))
				<div class="alert alert-success alert-dismissable rtl yekan">
					<button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
					<i class="fa fa-check pr10"></i>
					{{ \Illuminate\Support\Facades\Session::get('successful') }}
				</div>
			@endif
			<form id="del_group" method="post">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}
				<div class="panel">

					<div class="panel-body pn">
						<table class="table admin-form theme-warning tc-checkbox-1 fs13">
							<thead>
							<tr class="bg-light yekan rtl ">

								<th class="txt_center">عملیات</th>
								<th class="txt_center">تعداد خبرها</th>
								<th class="txt_right">عنوان موضوع</th>
							</tr>
							</thead>
							<tbody>
							@include('blog::admin.layouts._tr_cat_item', array('table_cats' => $table_cats,'under_line_counter'=>0))
							</tbody>
						</table>
						<div class="dt-panelfooter clearfix">
							<div class="dataTables_paginate paging_simple_numbers" id="datatable2_paginate">
								<center> {{ $table_cats->links() }}</center>
							</div></div>
					</div>
				</div>
			</form>
		</div>

	</section>
	<!-- Panel popup -->
	<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">

		<form action="" method="POST" id="delete_link">
			@method('DELETE')
			@csrf

		<div class="panel">
			<div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
				<span class="panel-title">حذف موضوع</span>
			</div>
			<div class="panel-body" style="text-align: right">
				<h3 class="mt5">آیا از حذف این موضوع اطمینان دارید؟</h3>
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
	<!-- Vendor CSS -->
	<!-- Vendor CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
	<!-- Page Plugins -->
	<script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
	<!-- Admin Modals CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
	<link rel="stylesheet" type="text/css" href={{ asset('admin/files/assets/admin-tools/admin-forms/css/admin-forms.css') }}"">
	<!-- Page Plugins -->
	<script type="text/javascript" src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js') }}"></script>
	<script type="text/javascript" src="{{ asset('admin/files/assets/admin-tools/admin-forms/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('admin-tools/admin-forms/js/additional-methods.min.js') }}"></script>
	<script type="text/javascript">
        jQuery(document).ready(function() {


            $( "#admin-form" ).validate({


                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "em",

                rules: {
                    title: {
                        required: true
                    },
                },


                messages:{
                    title: {
                        required: 'عنوان باید وارد شود.'
                    },
                },

                highlight: function(element, errorClass, validClass) {
                    $(element).closest('.field').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.field').removeClass(errorClass).addClass(validClass);
                },
                errorPlacement: function(error, element) {
                    if (element.is(":radio") || element.is(":checkbox")) {
                        element.closest('.option-group').after(error);
                    } else {
                        error.insertAfter(element.parent());
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }

            });
            $('.delet_cat').on('click', function(e) {
                e.preventDefault();
                var link  = $(this).attr('href');
                $('#delete_link').attr('action',link);
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function(e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#btn_form').on('click', function(e) {
                e.preventDefault();
                // Inline Admin-Form example
                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#modal-panel2'
                    },
                    // overflowY: 'hidden', //
                    callbacks: {
                        beforeOpen: function(e) {
                            var Animation = $(".delet_cat").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
            $('#delete_link2').on('click',function(e){
                $('#del_group').submit();
                e.preventDefault();
            });

        });
	</script>
	<!-- END: PAGE SCRIPTS -->
@endpush
