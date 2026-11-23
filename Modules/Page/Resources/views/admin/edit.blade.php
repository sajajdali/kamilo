@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش صفحه')
@section('content')
	<div id="content">
		<div class="row rtl yekan">
			<form method="post" action="{{ route('admin.pages.update', ['page' => $page->id]) }}">
				@csrf
				@method('PUT')
				<div class="col-md-3">
					<div class="panel">
						<div class="panel-heading">
							<span class="panel-title">ایجاد صفحه جدید</span>
						</div>
						<div class="panel-body">
							<div class="pull-right"><i class="fa fa-calendar" style="margin-left: 5px;"></i>
								تاریخ انتشار :
								{{ jdate('d F Y - h:i:s',strtotime($data['page']->created_at)) }}
							</div>
							<div class="pull-right"><i class="fa fa-calendar" style="margin-left: 5px;"></i>
								تاریخ ویرایش :
								{{ jdate('d F Y - h:i:s') }}
							</div>
							<div style="clear:both"></div>
							<hr style="margin: 20px 0px"/>
							<div class="col-xs-6 pull-right">
								<button class="btn btn-primary btn-block" value="published" name="submit" type="submit">ویرایش</button>
							</div>
						</div>
					</div>
					<div class="panel">
						<div class="panel-heading">
							<span class="panel-title">عکس صفحه</span>
						</div>
						<div class="panel-body" style="direction: ltr;text-align: right">
                            <div class="text-center admin-form">
                                {!! make_media_uploader([ 'type' => 'image', 'name' => 'main_image', 'value' => old('main_image',$data['page']->image) ]) !!}
                            </div>
                        </div>
					</div>
				</div>
				<div class="col-md-9">
					@if (Session::has('sys_error'))
						<div class="alert alert-danger alert-dismissable rtl yekan">
							<button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×</button>
							<i class="fa fa-remove pr10"></i>
							<strong>خطا!</strong>
							{{ Session::get('sys_error') }}
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
					<div class="panel">
						<div class="panel-heading">
							<span class="panel-title">محتوای صفحه</span>
							<ul class="nav panel-tabs-border panel-tabs">
								@foreach(Config::get('app.locales')  as $key=>$value)
									<li class="
                            @if($key === key(Config::get('app.locales')))
											active
@endif
											">
										<a data-toggle="tab" href="#tab_{{ $key }}" aria-expanded="false">{{ $value }}</a>
									</li>
								@endforeach
							</ul>
						</div>
						<div class="panel-body">
							<div class="tab-content pn br-n">
								<?php
								$counter = 0;
								?>
								@foreach(Config::get('app.locales')  as $key=>$value)
									<div class="tab-pane
                            @if($key === key(Config::get('app.locales')))
											active
@endif
											" id="tab_{{ $key }}">
										<div class="form-group">
											<div class="col-lg-10">
												<input value="{{ old('title.'.$counter,$page->getTitle($key)) }}" name="title[]" type="text" placeholder="عنوان" class="form-control" id="inputLarge_{{ $key }}">
											</div>
											<label for="inputLarge_{{ $key }}" class="col-lg-2 control-label">عنوان {{ $value }}</label>
											<div style="clear: both"></div>
										</div>
										<div style="clear: both"></div>
										<div class="form-group">
											<div class="col-lg-10">
												<textarea class="summernote" id="hometext_{{ $key }}" name="hometext[]" >{{ old('hometext.'.$counter.'',$page->getContent($key)) }}</textarea>
											</div>
											<label for="hometext_{{ $key }}" class="col-lg-2 control-label">محتوا {{ $value }}</label>
											<div style="clear: both"></div>
										</div>
										<div style="clear: both"></div>
									</div>
									<?php
									$counter++;
									?>
								@endforeach
							</div>
						</div>
					</div>
					<div class="panel rtl yekan">
						<div class="panel-heading">
							<span class="panel-title">توضیحات سئو</span>
						</div>
						<div class="panel-body">
							<div class="form-group mbn">
								<div class="col-md-10">
									<input name="keywords" type="text" id="tagmanager" class="form-control tm-input-seo" placeholder="کلمات کلیدی" value="
                                    @if(old('keywords')== null)
									@if(isset($data['content']->KEYWORD))
									<?=$data['content']->KEYWORD?>
									@endif
									@else
									{{ old('keywords') }}
									@endif
											">
									<span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن برچسب دکمه Enter یا Tab را فشار دهید.</span>
								</div>
								<label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">کلمات کلیدی</label>
							</div>
							<div class="form-group mbn">
								<div class="col-md-10 admin-form">
									<div class="section">
										<label class="field prepend-icon">
                                            <textarea placeholder="توضیحات سئو" name="describtion" id="describtion" class="gui-textarea">@if(old('describtion')== null) @if(isset($data['content']->DESCRIBTION)) <?=$data['content']->DESCRIBTION?> @endif @else <?=old('describtion')?> @endif
                                            </textarea>
											<label class="field-icon" for="describtion"><i class="fa fa-comments"></i>
											</label>
											<span class="input-footer">
                                                            <strong>توجه : </strong>توصیه می شود متن توضیحات بین 120 تا 255 کاراکتر باشد.</span>
										</label>
									</div>
								</div>
								<label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">توضیحات</label>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Panel popup -->
	<div id="modal-panel" class="popup-basic bg-none mfp-with-anim mfp-hide rtl yekan">
		
		<div class="panel">
			<div class="panel-heading" style="text-align: right">
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
				<span class="panel-title">حذف کاربر</span>
			</div>
			<div class="panel-body" style="text-align: right">
				<h3 class="mt5">آیا از حذف این کاربر اطمینان دارید؟</h3>
			</div>
			<div class="panel-footer text-left">
				<a id="delete_link">
					<button class="btn btn-primary" type="button">بله حذف کن</button>
				</a>
			</div>
		</div>
	</div>
	<!-- End: Content -->
@endsection
@push('scripts')
	<style>
		.remove{
			background: black none repeat scroll 0 0;
			color: white;
			font-size: 20px;
			left: 21px;
			padding: 5px;
			position: absolute;
			top: 0;
			z-index: 2;
		}
		.remove:hover{
			color:#c0c0c0;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
	<!-- Page Plugins -->
	<script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
	<!-- Admin Modals CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
	<!-- Summernote CSS  -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/editors/summernote/summernote.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/editors/summernote/summernote-bs3.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/jquery.tagit.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/tagit.ui-zendesk.css') }}">
	
	<!-- Summernote JS -->
	<script type="text/javascript" src="{{ asset('admin/files/vendor/editors/summernote/summernote.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('admin/files/vendor/editors/summernote/summernote-fa-IR.js') }}"></script>
	
	<script type="text/javascript" src="{{ asset('admin/files/assets/js/tag-it.js') }}"></script>
	
	<script type="text/javascript" src="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.css') }}">
	<script type="text/javascript">
        jQuery(document).ready(function() {
            // Init Summernote
            var noteStyle = 'success';
            var noteShadow = true;
            var noteOpacity ='1';
            var noteStack = "stack_bottom_left";
            var width = "290px";
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
            }
            function sendFile(file, editor, welEditable) {

                data = new FormData();
                data.append("file", file);
                data.append("_token", "{{ csrf_token() }}");
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ url('admin/pages/upload') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function(url) {
                        new PNotify({
                            title: 'آپلود',
                            text: 'فایل با موفقیت بارگزاری شد.',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[noteStack],
                            width: width,
                            delay: 3000,
                            addclass:'rtl yekan'
                        });
                        editor.insertImage(welEditable, url);
                    },
                }).fail(function(){
                    new PNotify({
                        title: 'آپلود',
                        text: 'متاسفانه فایل آپلود نشد.<br/>توجه داشته باشید فقط فایل تصویری مجاز است',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[noteStack],
                        width: width,
                        delay: 5000,
                        addclass:'rtl yekan'
                    });
                });
            }
            $('.summernote').summernote({
                lang: 'fa-IR', // default: 'en-US'
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function() {},
                onChange: function(contents, $editable) {},
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            });
            $(".tm-input-seo").tagit({
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
            });
            $(".tm-input").tagit({
                singleField: true,
                singleFieldNode: $('#mySingleField'),
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
                tagSource: function( request, response ) {
                    //console.log("1");
                    $.ajax({
                        url: "{{ url('/admin/blog/tag') }}",
                        data: { term:request.term },
                        dataType: "json",
                        success: function( data ) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item.name,
                                    value: item.name
                                }
                            }));
                        }
                    });
                }});
        });
	</script>
@endpush
