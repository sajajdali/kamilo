@extends('admin.layout.layout')
@section('title',$title ?? 'مدیریت کاربران')
@section('content')
	
	<!-- Start: Topbar -->
	<header id="topbar" class="ph10">
		<div class="topbar-right rtl yekan">
			<ol class="breadcrumb">
				<li class="crumb-active">
					<a href="{{ url('admin/users') }}">داشبور</a>
				</li>
				<li class="crumb-icon">
					<a href="{{ url('admin/dashbord') }}">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li class="crumb-link">
					<a href="{{ url('/') }}">خانه</a>
				</li>
				<li class="crumb-trail">مدیریت کاربران</li>
			</ol>
		</div>
		<div class="topbar-left">
			@can('add_admin_user')
				<a href="{{ route('admin.user.create') }}" class="btn btn-default btn-sm light fw600 ml10 rtl yekan "><span
							class="fa fa-plus pl5"></span>اضافه کردن کاربر</a>
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
					
					<h4 class="mt5 text-muted fw500">فیلتر کردن کاربران</h4>
					
					<hr class="short">
					
						<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['user_id']) ? $_GET['user_id'] : '') }}" type="text"
							       name="user_id" id="user_id" class="gui-input yekan" placeholder="ایدی کاربر">
							<label for="user_id" class="field-icon"><i class="fa fa-smile-o"></i>
							</label>
						</label>
					</div>
					
					<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['first_name']) ? $_GET['first_name'] : '') }}" type="text" name="first_name"
							       id="order-id" class="gui-input yekan" placeholder="نام کاربر">
							<label for="order-id" class="field-icon"><i class="fa fa-user"></i>
							</label>
						</label>
					</div>
					
				
					<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['last_name']) ? $_GET['last_name'] : '') }}" type="text" name="last_name"
							       id="order-id" class="gui-input yekan" placeholder="نام ‌خانوادگی کاربر">
							<label for="last_name" class="field-icon"><i class="fa fa-tag"></i>
							</label>
						</label>
					</div>
					
					<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['email']) ? $_GET['email'] : '') }}" type="text" name="email"
							       id="order-id" class="gui-input yekan" placeholder="ایمیل">
							<label for="email" class="field-icon"><i class="fa fa-envelope-o"></i>
							</label>
						</label>
					</div>
					
					<div class="section mb15">
						<label for="order-id" class="field prepend-icon">
							<input value="{{ (isset($_GET['mobile']) ? $_GET['mobile'] : '') }}" type="text" name="mobile"
							       id="order-id" class="gui-input yekan" placeholder="شماره موبایل">
							<label for="mobile" class="field-icon"><i class="fa fa-mobile"></i>
							</label>
						</label>
					</div>
					
					<h6 class="fw400">دسترسی کاربر </h6>
					<div class="section mb15">
                            <label class="field select">
                                <select id="role_id" name="role_id" class="empty">
                                    <option value="0" selected="selected">انتخاب دسترسی</option>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
									<option value="{{ $role->id }}">{{ $role->display_name }}</option>
								@endforeach
                                </select>
                                <i class="arrow"></i>
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
							<th class="txt_center">گروه کاربری</th>
							<th class="txt_center">شماره تماس</th>
							<th class="txt_center">ایمیل</th>
							<th class="txt_right">نام کاربر</th>
							<th class="txt_center">عکس</th>
							<th class="txt_center">#</th>
						</tr>
						</thead>
						<tbody>
						@foreach($users as $us)
							<tr>
								<td class="text-right">
									<div class="btn-group text-right yekan rtl txt_right">
										<button type="button"
										        class="btn btn-success br2 btn-xs fs12 dropdown-toggle yekan"
										        data-toggle="dropdown" aria-expanded="false"> عملیات
											<span class="caret mr5"></span>
										</button>
										<ul class="dropdown-menu txt_right" role="menu">
											@if($us->id != 1)
												@can('edit_admin_user')
													<li>
												<a href="{{ route('admin.user.edit', ['user' => $us->id]) }}">ویرایش</a>
													</li>
												@endcan
												@can('delete_admin_user')
													<li>
														<a href="{{ route('admin.user.destroy', ['user' => $us->id]) }}"
														   class="delete_user" data-effect="mfp-rotateLeft">حذف</a>
													</li>
												@endcan
											@endif
											@can('add_admin_support_message')
												<li class="divider"></li>
													<li>
														<a href="{{ route('admin.support.message.create',['uid'=>$us->id]) }}"
														    >ارسال پیام</a>
													</li>
											@endcan
										</ul>
									</div>
								</td>
								<td class="txt_center yekan">
									@foreach($us->roles as $role)
										<span class="label label-danger label-lg">{{ $role->display_name }}</span>
									@endforeach
								</td>
								<td class="txt_center yekan rtl">{{ $us->mobile }}
								</td>
								<td class="txt_center yekan">{{ $us->email }}</td>
								<td class="rtl yekan">{{ $us->full_name }}</td>
								<td class="w100 txt_center">
									<img class="img-responsive mw40 ib mr10" title="user"
									     src="{{ $us->avatar }}"/>
								</td>
								<td class="rtl yekan">{{ $us->id }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					<center>{{ $users->links() }}</center>
				</div>
			</div>
		
		</div>
	
	</section>
	<!-- Panel popup -->
	<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
		
		<div class="panel">
			<div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
				<span class="panel-title">حذف کاربر</span>
			</div>
			<div class="panel-body" style="text-align: right">
				<h3 class="mt5">آیا از حذف این کاربر دارید؟</h3>
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
	<!-- Vendor CSS -->
	<link rel="stylesheet" type="text/css"
	      href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
	<!-- Page Plugins -->
	<script type="text/javascript"
	        src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
	<!-- Admin Modals CSS -->
	<link rel="stylesheet" type="text/css"
	      href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
	<script type="text/javascript">
        jQuery(document).ready(function () {
            $('.delete_user').on('click', function (e) {
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
                            var Animation = $(".delete_user").attr('data-effect');
                            this.st.mainClass = Animation;
                        }
                    },
                    midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                });
            });
	@if(intval(request()->role_id) != 0)
				$('#role_id').val('{{ request()->role_id }}');
			@endif
        });
	</script>
	<!-- END: PAGE SCRIPTS -->
@endpush
