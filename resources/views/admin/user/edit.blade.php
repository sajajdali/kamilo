@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش کاربر')
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
	</header>
	<!-- End: Topbar -->
	
	<!-- Begin: Content -->
	
	<section id="content" class="table-layout animated fadeIn">
		<div class="tray tray-center p25 va-t posr">
			<div class="alert alert-info alert-dismissable rtl yekan">
				<button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
				<i class="fa fa-info pr10"></i>
				فقط در صورتی که قصد تغییر کلمه عبور را دارید فیلد های کلمه عبور و تکرار کلمه عبور را پرنمایید.
			</div>
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
			<form class="form-horizontal" role="form" action="{{ route('admin.user.update',['id'=>$user->id]) }}" method="post"
			      enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<div class="panel mb25 mt5">
					<div class="panel-heading rtl yekan">
						<span class="panel-title">ویرایش کاربر</span>
						<ul class="nav panel-tabs-border panel-tabs">
							<li class="active">
								<a data-toggle="tab" href="#tab1_1">مشخصات</a>
							</li>
						</ul>
					</div>
					<div class="panel-body p20 pb10">
						<div class="tab-content pn br-n admin-form">
							<div class="tab-pane active" id="tab1_1">
								
								<div class="section row mbn">
									<div class="col-md-8 pl15">
										<div style="display:none;" class="alert alert-info rtl yekan">ایدی کاربر در API</div>
										<div style="display:none;" class="section row mb15">
											<div class="col-xs-12">
												<label class="field prepend-icon" for="name2">
													<input value="{{ old('user_id',$user->user_api_id) }}" type="text"
														   placeholder="ایدی کاربر API"
														   class="event-name gui-input br-light light rtl" id="user_id"
														   name="user_id" tabindex="2">
													<label class="field-icon" for="user_id"><i class="fa fa-user"></i>
													</label>
												</label>
											</div>
										</div>

										<div class="section row mb15">
											<div class="col-xs-6">
												<label class="field prepend-icon" for="name2">
													<input value="{{ old('lastname',$user->last_name) }}" type="text"
													       placeholder="نام خانوادگی"
													       class="event-name gui-input br-light light rtl" id="name2"
													       name="lastname" tabindex="2">
													<label class="field-icon" for="name2"><i class="fa fa-user"></i>
													</label>
												</label>
											</div>
											<div class="col-xs-6">
												<label class="field prepend-icon" for="name1">
													<input value="{{ old('firstname',$user->first_name) }}" type="text" placeholder="نام"
													       class="event-name gui-input br-light light rtl" id="name1"
													       name="firstname" tabindex="1">
													<label class="field-icon" for="name1"><i class="fa fa-user"></i>
													</label>
												</label>
											</div>
										</div>

										<div class="section row mb15">
											<div class="col-xs-6">
												<label class="field prepend-icon" for="password2">
													<input type="password" placeholder="تکرار کلمه عبور"
													       class="event-name gui-input br-light light rtl"
													       id="password2" name="password_confirmation" tabindex="4">
													<label class="field-icon" for="password2"><i
																class="fa fa-unlock"></i>
													</label>
												</label>
											</div>
											<div class="col-xs-6">
												<label class="field prepend-icon" for="password">
													<input type="password" placeholder="کلمه عبور"
													       class="event-name gui-input br-light light rtl" id="password"
													       name="password" tabindex="3">
													<label class="field-icon" for="name2"><i class="fa fa-lock"></i>
													</label>
												</label>
											</div>
										</div>
										
										<div class="section mb15">
											<label class="field prepend-icon" for="email">
												<input type="text" value="{{ old('email',$user->email) }}" disabled placeholder="ادرس ایمیل"
												       class="event-name gui-input br-light bg-light rtl" id="email"
												       name="email" tabindex="5">
												<label class="field-icon" for="email"><i class="fa fa-envelope-o"></i>
												</label>
											</label>
										</div>
										<div class="section mb15">
											<label class="field prepend-icon" for="email">
												<input type="text" value="{{ old('mobile',$user->mobile) }}"
												       disabled
												       placeholder="شماره موبایل"
												       class="event-name gui-input br-light light rtl" id="mobile"
												       name="mobile"
												       style="direction: ltr ! important; text-align: right;"
												       tabindex="6">
												<label class="field-icon" for="mobile"><i class="fa fa-phone"></i>
												</label>
											</label>
										</div>
										<div class="section mb15 rtl yekan">
											@can('add_admin_user_with_role')
												@foreach($roles as $role)
													<div class="admin-form col-sm-3 pull-right">
														<div class="checkbox-custom fill checkbox-primary mb5">
															<input type="checkbox" id="checkboxExample{{ $role->id }}"
															       @if(in_array($role->id,$user->roles()->pluck('id')->toArray()))
																	       CHECKED
															       @endif
															       name="role[]" value="{{ $role->id }}">
															<label for="checkboxExample{{$role->id}}">{{ $role->display_name }}</label>
														</div>
													</div>
												@endforeach
											@else
												@foreach($user->roles as $role)
												<input type="hidden" name="role[]"
												       value="{{ $role->id }}">
												@endforeach
											@endcan
										</div>
										<div class="section mb15">
											<label class="field prepend-icon" for="email">
												<input type="text" placeholder="تاریخ تولد"
												       class="event-name gui-input br-light light rtl" id="bri"
												       name="bri" style="direction: ltr ! important; text-align: right;"
												       tabindex="8">
												<label class="field-icon" for="bri"><i class="fa fa-calendar"></i>
												</label>
											</label>
										</div>
									
									</div>
									
									<div class="col-md-4">
										<div data-provides="fileupload" class="fileupload fileupload-new admin-form">
											<div class="fileupload-preview thumbnail mb20">
												<img alt="100%x140" data-src="{{  $user->avatar }}"
												     style=" width: 100%; display: block;"
												     src="{{  $user->avatar }}"
												     data-holder-rendered="true"/>
											</div>
											<div class="row">
												<div class="col-xs-12 rtl yekan">
                                                        <span class="button btn-system btn-file btn-block">
                                                            <span class="fileupload-new">انتخاب</span>
                                                        <span class="fileupload-exists">تغییر</span>
                                                        <input type="file" name="profile_image">
                                                        </span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr class="short alt">
								<div class="section row mbn">
									<div class="col-sm-4">
										<p class="text-left yekan">
											<button type="submit" class="btn btn-primary yekan">ثبت و ذخیره</button>
										</p>
									</div>
									<div class="col-sm-8">
									
									</div>
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	
	</section>
@endsection
@push('scripts')
	<style>
		div.switch label::after {
			top: 0 !important;
		}
		
		.checkbox-custom.fill input[type="checkbox"]:checked + label::after, .checkbox-custom.fill input[type="radio"]:checked + label::after {
			top: 2px !important;
			right: 7px !important;
		}
	</style>
	<script type="text/javascript"
	        src="{{ asset('admin/files/vendor/plugins/jquerymask/jquery.maskedinput.min.js') }}"></script>
	
	<link rel="stylesheet"
	      href="{{ asset('admin/files/datepicker/css/persian-datepicker-0.4.5.min.css') }}"/>
	<script src="{{ asset('admin/files/datepicker/js/persian-date-0.1.8.min.js') }}"></script>
	<script src="{{ asset('admin/files/datepicker/js/persian-datepicker-0.4.5.min.js') }}"></script>
	<script type="text/javascript">
        jQuery(document).ready(function () {
            var mainDate = $("#bri").persianDatepicker({
                format: 'YYYY/MM/DD'
            });
            var dateTemp2 = new persianDate([{{ $user->birthday_year ?? 1370 }}, {{ $user->birthday_month ?? 12 }}, {{ $user->birthday_day ?? 12 }}, 0, 0, 0]);
            mainDate.setDate(dateTemp.valueOf());
            // Init jQuery masked inputs
            $('#bri').mask('9999/99/99');
            $('#mobile').mask('(9999)  9999999');

        });
	</script>
@endpush
