@extends('admin.layout.layout')
@section('title',$title ?? 'مدیریت نقش‌ها')
@section('content')
	<!-- Start: Topbar -->
	<header id="topbar">
		<div class="topbar-right rtl yekan">
			<ol class="breadcrumb">
				<li class="crumb-active">
					<a href="#">داشبور</a>
				</li>
				<li class="crumb-icon">
					<a href="#">
						<span class="glyphicon glyphicon-home"></span>
					</a>
				</li>
				<li class="crumb-link">
					<a href="{{ url('/') }}">خانه</a>
				</li>
				<li class="crumb-trail">مدیرت نقش ها</li>
			</ol>
		</div>
	</header>
	<!-- End: Topbar -->
	<!-- Begin: Content -->
	<section id="content" class="table-layout animated fadeIn">
		
		<!-- begin: .tray-center -->
		<!-- end: .tray-center -->
		
		
		<div class="tray tray-center p25 va-t posr">
			@if ($errors->any())
				<div class="alert alert-danger rtl">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
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
			<div class="panel mb25 mt5">
				<div class="panel-heading rtl yekan">
					<span class="panel-title">اضافه کردن نقش جدید</span>
					<ul class="nav panel-tabs-border panel-tabs">
						<li>
							<a href="#tab1_2" data-toggle="tab">دسترسی ها</a>
						</li>
						<li class="active">
							<a href="#tab1_1" data-toggle="tab">مشخصات کلی</a>
						</li>
					</ul>
				</div>
				<form method="post" role="form" action="{{ route('admin.role.update', ['role' => $role->id]) }}">
					@csrf
					@method('PUT')
					<div class="panel-body p20 pb10">
						<div class="tab-content pn br-n admin-form">
							<div id="tab1_1" class="tab-pane active">
								<div class="section row mbn">
									<div class="col-md-12 pl15">
										<div class="section mb10">
											<label for="name2" class="field prepend-icon">
												<input type="text" value="{{ old('title',$role->name) }}" name="title"
												       id="name2"
												       class="rtl yekan event-name gui-input br-light light"
												       placeholder="عنوان نقش">
												<label for="name2" class="field-icon right"><i
															class="fa fa-tag"></i>
												</label>
											</label>
										</div>
									</div>
									<div class="col-md-12 pl15">
										<div class="section mb10">
											<label for="name2" class="field prepend-icon">
												<input type="text" value="{{ old('display_name',$role->display_name) }}"
												       name="display_name"
												       id="display_name"
												       class="rtl yekan event-name gui-input br-light light"
												       placeholder="نام نمایشی">
												<label for="display_name" class="field-icon right"><i
															class="fa fa-tag"></i>
												</label>
											</label>
										</div>
									</div>
									<div class="col-md-12 rtl yekan">
										<label class="field select section mb10">
											<select id="product-status" name="is_admin" class="yekan" 												      @if($role->is_default) disabled @endif>
												<option value=" " selected="selected">انتخاب وضعیت نقش</option>
												<option value="1">مدیر</option>
												<option value="2">کاربر</option>
											</select>
											<i class="arrow double"></i>
										</label>
									</div>
									<div id="default" class="col-md-12 rtl yekan"
									     style="margin-top: 10px;display: none">
										<label class="col-md-2 control-label pull-right">گروه پیشفرض</label>
										<div class="col-md-10 pull-right">
											<div class="switch switch-info round switch-inline">
												<input name="is_default" value="1" id="sam_exampleCheckboxSwitch6"
												       @if($role->is_default) disabled @endif
												       type="checkbox">
												<label for="sam_exampleCheckboxSwitch6"></label>
											</div>
										</div>
										</label>
									</div>
								</div>
							
							
							</div>
							<div id="tab1_2" class="tab-pane">
								<div class="panel col-md-12 yekan admin_section" style="display: none;">
									<div class="panel-heading rtl">
										<span class="panel-title">مدیر اصلی</span>
										<div class="switch switch-info switch-inline pull-left"
										     style="margin-top: 7px">
											<input name="administor" value="1" type="checkbox" id="adminestor"
											       class="change_status">
											<label for="adminestor"></label>
										</div>
									</div>
								</div>
								<div class="section row">
									@php
										$counter = 0;
									@endphp
									@foreach(config('permissions') as $basePermissions)
										<div class="panel col-md-12 yekan {{ $basePermissions['group']['section'] }}"
										     style="display: none;">
											<div class="panel-heading rtl">
												<span class="panel-title">{{ $basePermissions['group']['display_name'] }}</span>
												<div class="switch switch-info switch-inline pull-left"
												     style="margin-top: 7px">
													<input name="access[]"
													       value="{{ $basePermissions['group']['name'] }}"
													       name="{{ $basePermissions['group']['name'] }}"
													       type="checkbox"
													       id="exampleCheckboxSwitch{{ $counter }}"
													       class="change_status">
													<label for="exampleCheckboxSwitch{{ $counter }}"></label>
												</div>
											</div>
											<div class="panel-body pl30 pb5" style="display: none">
												<div class="row">
													@foreach($basePermissions['permissions'] as $key => $permissions)
														@if($key == 'Radio') @continue @endif
														<div class="col-sm-4 pull-right" style="text-align: right">
															<label class="control-label mb15">{{ $key }}</label>
															@foreach($permissions as $key2 => $permission)
																<div class="form-group">
																	<div class="checkbox-custom checkbox-info mb5">
																		<input name="access[]" value="{{ $key2 }}"
																		       class="access_ch" type="checkbox"
																		       id="{{ $key2 }}">
																		<label for="{{ $key2 }}">{{ $permission }}</label>
																	</div>
																</div>
															@endforeach
														</div>
													@endforeach
													@if(isset($basePermissions['permissions']['Radio']) && sizeof($basePermissions['permissions']['Radio']) >= 1)
														@foreach($basePermissions['permissions']['Radio'] as $key => $radioItems)
															<div class="col-md-12"
															     style="text-align: right;padding: 35px 0;border-top:1px solid #ddd">
																@foreach($radioItems as $key2 => $radio_item)
																	<div class="form-group">
																		<div class="radio-custom mb5">
																			<input value="{{ $key2 }}"
																			       type="radio"
																			       name="{{ $key }}"
																			       id="{{ $key2 }}"
																			       @if($loop->first) checked @endif>
																			<label for="{{ $key2 }}">
																				{{ $radio_item }}
																			</label>
																		</div>
																	</div>
																@endforeach
															</div>
														@endforeach
													@endif
												</div>
											</div>
										
										</div>
										@php
											$counter++;
										@endphp
									@endforeach
									@foreach(\Module::allEnabled() as $module)
										@php
											$moduleConfig = config($module->getLowerName().".Permission") ?? array();
										@endphp
										@if(sizeof($moduleConfig) == 0) @continue @endif
										<div class="panel col-md-12 yekan {{ $moduleConfig['group']['section'] }}"
										     style="display: none;">
											<div class="panel-heading rtl">
												<span class="panel-title">{{ $moduleConfig['group']['display_name'] }}</span>
												<div class="switch switch-info switch-inline pull-left"
												     style="margin-top: 7px">
													<input name="access[]"
													       value="{{ $moduleConfig['group']['name'] }}"
													       name="{{ $moduleConfig['group']['name'] }}"
													       type="checkbox"
													       id="exampleCheckboxSwitch{{ $counter }}"
													       class="change_status">
													<label for="exampleCheckboxSwitch{{ $counter }}"></label>
												</div>
											</div>
											<div class="panel-body pl30 pb5" style="display: none">
												<div class="row">
													@foreach($moduleConfig['permissions'] as $key => $permissions)
														@if($key == 'Radio') @continue @endif
														<div class="col-sm-4 pull-right" style="text-align: right">
															<label class="control-label mb15">{{ $key }}</label>
															@foreach($permissions as $key2 => $permission)
																<div class="form-group">
																	<div class="checkbox-custom checkbox-info mb5">
																		<input name="access[]" value="{{ $key2 }}"
																		       class="access_ch" type="checkbox"
																		       id="{{ $key2 }}">
																		<label for="{{ $key2 }}">{{ $permission }}</label>
																	</div>
																</div>
															@endforeach
														</div>
													@endforeach
													@if(isset($moduleConfig['permissions']['Radio']) && sizeof($moduleConfig['permissions']['Radio']) >= 1)
														@foreach($moduleConfig['permissions']['Radio'] as $key => $radioItems)
															<div class="col-md-12"
															     style="text-align: right;padding: 35px 0;border-top:1px solid #ddd">
																@foreach($radioItems as $key2 => $radio_item)
																	<div class="form-group">
																		<div class="radio-custom mb5">
																			<input value="{{ $key2 }}"
																			       type="radio"
																			       name="{{ $key }}"
																			       id="{{ $key2 }}"
																			       @if($loop->first) checked @endif>
																			<label for="{{ $key2 }}">
																				{{ $radio_item }}
																			</label>
																		</div>
																	</div>
																@endforeach
															</div>
														@endforeach
													@endif
												</div>
											</div>
										
										</div>
										@php
											$counter++;
										@endphp
									@endforeach
								</div>
								<hr class="short alt">
								
								<div class="section row mbn">
									<div class="col-sm-4">
										<p class="text-left yekan">
											<button class="btn btn-primary yekan" type="submit">ثبت و ذخیره</button>
										</p>
									</div>
									<div class="col-sm-8">
									
									</div>
								</div>
								<!-- end section -->
							
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	
	</section>
	<!-- End: Content -->
	<!-- Panel popup -->
	<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
		
		<div class="panel">
			<div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
				<span class="panel-title">حذف نقش</span>
			</div>
			<div class="panel-body" style="text-align: right">
				<h3 class="mt5">آیا از حذف این نقش دارید؟</h3>
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
	<link rel="stylesheet" type="text/css"
	      href="{{ asset('admin/files/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
	<!-- Page Plugins -->
	<script type="text/javascript"
	        src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
	<!-- Admin Modals CSS -->
	<link rel="stylesheet" type="text/css"
	      href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
	<script>
        $(document).ready(function () {
            $('#product-status').on('change', function () {
                var value = $(this).val();
                if (value == '2') {
                    $('.user_section').show();
                    $('.admin_section').hide().find('.access_ch').removeAttr('checked');
                } else {
                    $('.user_section').show();
                    $('.admin_section').show();
                }
            });
            $('.change_status').on('change', function () {
                var secti = $(this).parent().parent().parent();
                if (this.checked) {
                    secti.find('.access_ch').removeAttr('disabled', 'disabled').parent().addClass('checkbox-info').removeClass('checkbox-disabled').parents('div.panel-body').first().slideToggle();
                    ;
                } else {
                    secti.find('.access_ch').attr('disabled', 'disabled').removeAttr('checked').parent().removeClass('checkbox-info').addClass('checkbox-disabled').parents('div.panel-body').first().slideToggle();
                    ;
                }
            });
            $('#adminestor').on('change', function () {
                if (this.checked) {
                    $(this).parents().find('#tab1_2').find('.panel').hide();
                    $(this).parents().find('#tab1_2').find('.panel').first().show();
                } else {
                    $(this).parents().find('#tab1_2').find('.panel').show();
                }
            });
			
			@if($role->hasPermissionTo("ADMIN"))
            $('#product-status').val(1).change();
			@if($role->hasPermissionTo("SUPERADMIN_ACCESS"))
            $('#adminestor').attr('CHECKED', 'CHECKED').change();
			@endif
			@else
            $('#product-status').val(2).change();
			@if($role->is_default)
            $('#exampleCheckboxSwitch6').attr('CHECKED', 'CHECKED');
			@endif
			@endif
	        @foreach($role->permissions as $permission)
                $('#tab1_2').find('input[value="{{ $permission->name }}"]').attr('CHECKED','CHECKED').change();
	        @endforeach
        });
	
	</script>
	<!-- END: PAGE SCRIPTS -->
	<style>
		div.switch label::after {
			top: 0 !important;
		}
	</style>
@endpush
