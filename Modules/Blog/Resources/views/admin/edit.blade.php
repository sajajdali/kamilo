@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش خبر')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a>مدیریت اخبار</a>
                </li>
                <li class="crumb-icon">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="{{ route('admin.blog.index') }}">اخبار</a>
                </li>
                <li class="crumb-trail">ویرایش خبر</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->
    
    <div id="content">
        <div class="row rtl yekan">
            <form method="post" action="{{ route('admin.blog.update', $blog->id) }}">
                <input name="_method" type="hidden" value="PUT">
                {{ csrf_field() }}
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">انتشار خبر</span>
                        </div>
                        <div class="panel-body admin-form">
                            <div class="pull-right" style="margin-bottom: 10px;">
                                <i class="fa fa-calendar"
                                   style="margin-left: 5px;"></i>
                                تاریخ انتشار :
                                {{ jdate('d F Y - h:i:s',strtotime($blog->created_at)) }}
                            </div>
                            <div class="col-lg-12 pull-right" style="margin:20px 0px">
                                <label class="field select">
                                    <select id="type" name="type" class="yekan empty">
                                        <option value="{{ \Modules\Blog\Entities\Blog::TYPE_NORMAL }}"
                                                @if($blog->type == \Modules\Blog\Entities\Blog::TYPE_NORMAL) selected @endif>
                                            خبر ساده
                                        </option>
                                    </select>
                                    <i class="arrow"></i>
                                </label>
                            </div>
                            <div class="pull-right" style="margin-bottom: 10px;">
                                <i
                                        class="glyphicons glyphicons-history" style="margin-left: 5px;"></i>
                                تاریخ بروزرسانی :
                                {{ jdate('d F Y - h:i:s',strtotime($blog->updated_at)) }}
                            </div>
                            <div class="pull-right" style="margin-bottom: 10px;">
                                <i class="glyphicons glyphicons-pencil"
                                   style="margin-left: 5px;"></i>
                                وضعیت :
                                @if($blog->published == 1)
                                    <span style="color: #70ca63">منتشر شده</span>
                                @else
                                    <span style="color: #4a89dc">پیش نویس</span>
                                @endif
                            </div>
                            <div style="clear:both"></div>
                            <hr class="short" />
                            @if($blog->published == 1)
                                <div class="col-xs-5 pull-right">
                                    <button class="btn btn-primary btn-block" name="submit" value="save" type="submit">
                                        ذخیره
                                    </button>
                                </div>
                            @else
                                <div class="col-xs-7 pull-right">
                                    <button class="btn btn-primary btn-block" name="submit" value="save_public"
                                            type="submit">ذخیره و انتشار
                                    </button>
                                </div>
                            @endif
                            @unless($blog->published == 1)
                                <div class="col-xs-5 pull-right">
                                    <button class="btn btn-default btn-block" value="save" name="submit" type="submit">
                                        ذخیره
                                    </button>
                                </div>
                            @else
                                <div class="col-xs-7 pull-right">
                                    <a href="{{ route('admin.blog.changeStatus', $blog->id) }}">
                                        <button class="btn btn-default btn-block" type="button">تغییر وضعیت</button>
                                    </a>
                                </div>
                            @endunless
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">موضوعات</span>
                        </div>
                        <div class="panel-body">
                            <div class="admin-form option-group field section"
                                 style="height: 200px;overflow-y: scroll;border: 1px solid #ddd">
                                @include('blog::admin.layouts.__select_edit_blog', array('cats' => $categories,'under_line_counter'=>-1,'categories'=>$blog->categories))
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">عکس خبر</span>
                        </div>
                        <div class="panel-body" style="direction: ltr;text-align: right">
                            {{--<input type="hidden" value="{{ old('main_image',$blog->image->value('meta_value'))}}"--}}
                            {{--name="main_image" id="inputImageNews">--}}
                            <div class="text-center admin-form">
                                {{--‌--}}
                                {{--<div id="image_blog" class="col-md-12 text-center"--}}
                                {{--style="@unless(old('main_image',$blog->image->value('meta_value'))) display:none @endunless">--}}
                                {{--<a id="inputRemoveImage" class="remove" href="#"><i class="fa fa-remove"></i></a>--}}
                                
                                {{--<img src="@if(old('main_image',$blog->image->value('meta_value'))){{old('main_image',url('').'/uploads/blog'.$blog->image->value('meta_value'))}}@endif"--}}
                                {{--class="col-md-12"/>--}}
                                {{--</div>--}}
                                {{--<input style="display:none" class="gui-file" id="imageNews" name="image_news" id="file1"--}}
                                {{--onchange="document.getElementById('uploader1').value = this.value;" type="file">--}}
                                {{--<button type="button" class="btn ladda-button btn-danger" data-style="zoom-in"--}}
                                {{--style="@if(old('main_image',$blog->image->value('meta_value'))) display:none @endif">--}}
                                {{--<span class="ladda-label">آپلود عکس خبر</span>--}}
                                {{--<span class="ladda-spinner"></span></button>--}}
                                {!! make_media_uploader([ 'type' => 'image', 'name' => 'main_image', 'value' => old('main_image',$blog->metas()->value('meta_value')) ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">تنظیمات</span>
                        </div>
                        <div class="panel-body" style="direction: ltr;text-align: right">
                            <div class="admin-form">
                                <div class="admin-form">
                                    <div class="switch switch-info round switch-inline pull-left">
                                        <input name="show_at_home_top" type="checkbox"
                                               @if($blog->show_at_home_top)
                                               CHECKED
                                               @endif
                                               id="exampleCheckboxSwitch8" value="1">
                                        <label for="exampleCheckboxSwitch8"></label>
                                    </div>
                                    <span class="yekan">نمایش در بالای صفحه اصلی</span>
                                    <hr class="short" />
                                    <div class="switch switch-info round switch-inline pull-left">
                                        <input name="show_at_home_bottom" type="checkbox"
                                               @if($blog->show_at_home_bottom)
                                               CHECKED
                                               @endif
                                               id="exampleCheckboxSwitch9" value="1">
                                        <label for="exampleCheckboxSwitch9"></label>
                                    </div>
                                    <span class="yekan">نمایش در پایین صفحه اصلی</span>
                                    <hr class="short" />
                                    <div class="switch switch-info round switch-inline pull-left">
                                        <input name="comment" type="checkbox"
                                               @if(number_format($blog->comment))
                                               CHECKED
                                               @endif
                                               id="exampleCheckboxSwitch6" value="1">
                                        <label for="exampleCheckboxSwitch6"></label>
                                    </div>
                                    <span class="yekan">فعال سازی نظرات</span>
                                    <hr class="short" />
                                    <div class="switch switch-info round switch-inline pull-left">
                                        <input name="main_page" type="checkbox"
                                               @if($blog->main_page)
                                               CHECKED
                                               @endif id="exampleCheckboxSwitch7" value="1">
                                        <label for="exampleCheckboxSwitch7" style="margin-top: -2px;"></label>
                                    </div>
                                    <span class="yekan">نمایش خبر در صفحه اصلی</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    @if (Session::has('sys_error'))
                        <div class="alert alert-danger alert-dismissable rtl yekan">
                            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×
                            </button>
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
                            <span class="panel-title">متن خبر</span>
                            <ul class="nav panel-tabs-border panel-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab"
                                       aria-expanded="false"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content pn br-n">
                                <div class="tab-pane active" id="tab">
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <input value="{{ old('title', $blog->title) }}"
                                                   name="title" type="text" placeholder="عنوان"
                                                   class="form-control" id="inputLarge">
                                        </div>
                                        <label for="inputLarge"
                                               class="col-lg-2 control-label">عنوان
                                        </label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                        <textarea class="editor-tinymce" id="hometext" name="hometext">
                                            {{ old('hometext', $blog->home_text) }}
                                        </textarea>
                                        </div>
                                        <label for="hometext"
                                               class="col-lg-2 control-label">چکیده
                                        </label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                        <textarea class="editor-tinymce" id="bodytext" name="bodytext">
                                            {{ old('bodytext', $blog->body_text) }}
                                        </textarea>
                                        </div>
                                        <label for="bodytext" class="col-lg-2 control-label">متن اصلی</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SEO MANAGER -->
                    <div class="panel rtl yekan">
                        <div class="panel-heading">
                            <span class="panel-title">توضیحات سئو</span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group mbn">
                                <div class="col-md-10">
                                    <input name="keywords" value="
                                    @if(old('keywords')== NULL)
                                    @foreach($blog->metas()->where('meta_key','KEYWORD')->get() as $tag)
                                    {{ $tag->meta_value }},
                                        @endforeach
                                    @else
                                    {{ old('keywords') }}
                                    @endif
                                            " type="text" id="tagmanager" class="form-control tm-input-seo"
                                           placeholder="کلمات کلیدی">
                                    <span class="help-block mt5"><i class="fa fa-bell"></i> برای اضافه شدن برچسب دکمه Enter یا Tab را فشار دهید.</span>
                                </div>
                                <label for="tagmanager" class="col-md-2 control-label" style="padding-top: 5px;">کلمات
                                                                                                                 کلیدی
                                </label>
                            </div>
                            <div class="form-group mbn">
                                <div class="col-md-10 admin-form">
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            <textarea placeholder="توضیحات سئو" name="describtion" id="describtion"
                                                      class="gui-textarea">{{ old('describtion',$blog->description->value('meta_value')) }}</textarea>
                                            <label class="field-icon" for="describtion">
                                                <i class="fa fa-comments"></i>
                                            </label>
                                            <span class="input-footer">
                                                            <strong>توجه : </strong>توصیه می شود متن توضیحات بین 120 تا 255 کاراکتر باشد.</span>
                                        </label>
                                    </div>
                                </div>
                                <label for="tagmanager" class="col-md-2 control-label"
                                       style="padding-top: 5px;">توضیحات
                                </label>
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
    
    
    
    <script src="{{ asset('admin/plugins/tinymce/tinymce.min.js')  }}"></script>
    
    
    <script>
        $( function () {
            
            tinymce.init( {
                selector: '.editor-tinymce',
                language: 'fa',
                directionality: 'rtl',
                plugins: ' print preview code fullpage  searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount   imagetools contextmenu colorpicker textpattern help',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat'
            } );
            
            $( '#type' ).change( function () {
                $( '.hidec' ).hide();
                
                switch ( $( this ).val() )
                {
                    case '{{ \Modules\Blog\Entities\Blog::TYPE_NORMAL }}' :
                        $( '.normal' ).show();
                        break;
                }
            } );
            
            $( '#type' ).trigger( 'change' );
            
        } );
    </script>
    
    
    
    
    <style>
        .admin-form .switch > input:checked + label::after {
            margin-top : -1px;
        }
        
        .remove {
            background : black none repeat scroll 0 0;
            color      : white;
            font-size  : 20px;
            left       : 21px;
            padding    : 5px;
            position   : absolute;
            top        : 0;
            z-index    : 2;
        }
        
        .admin-form .switch > label::after {
            margin-top : -1px;
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- admin Modals CSS -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css') }}">
    <!-- Summernote CSS  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote-bs3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/files/assets/css/jquery.tagit.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/assets/css/tagit.ui-zendesk.css') }}">
    
    <!-- Summernote JS -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote-fa-IR.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('admin/files/assets/js/tag-it.js') }}"></script>
    
    {{--<script type="text/javascript"--}}
{{--            src="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.js') }}"></script>--}}
    {{--<link rel="stylesheet" type="text/css"--}}
{{--          href="{{ asset('admin/files/vendor/plugins/ladda/ladda.min.css') }}">--}}
    
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            
            
            /******* -- upload image -------*/
            /*
            $( '#imageNews' ).css( 'display', 'none' );
            $( '.ladda-button' ).on( 'click', function () {
                $( '#imageNews' ).click();
            } );
            
            $( "#imageNews" ).on( 'change', (function () {
                ladda_btn.start();
                upload( this.files[ 0 ] );
            }) );
            var ladda_btn = Ladda.create( document.querySelector( '.ladda-button' ) );
            
            $( "#inputRemoveImage" ).on( 'click', function ( e ) {
                removeImage();
                return false;
            } );
            
            ladda_btn.stop(); // loading button
            function removeImage()
            {
                data = new FormData();
                var file = $( "#inputImageNews" ).val();
                data.append( "file", file );
                data.append( "_token", "{{ csrf_token() }}" );
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.blog.removeImage') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function ( url ) {
                        new PNotify( {
                            title: 'حذف عکس',
                            text: 'عکس با موفقیت حذف شد',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[ noteStack ],
                            width: width,
                            delay: 3000,
                            addclass: 'rtl yekan'
                        } );
                        $( '.ladda-button' ).show();
                        $( "#image_blog" ).hide();
                        $( "#inputImageNews" ).val( '' );
                        ladda_btn.stop();
                        
                    }
                } ).fail( function () {
                    new PNotify( {
                        title: 'حذف عکس',
                        text: 'امکان حذف عکس فعلا مقدور نمیباشد . ‌<br /> لطفا مجدد تلاش کنید',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[ noteStack ],
                        width: width,
                        delay: 5000,
                        addclass: 'rtl yekan'
                    } );
                } );
            }
            
            function upload( file )
            {
                data = new FormData();
                data.append( "file", file );
                data.append( "_token", "{{ csrf_token() }}" );
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.blog.upload') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function ( url ) {
                        new PNotify( {
                            title: 'آپلود',
                            text: 'فایل با موفقیت بارگزاری شد.',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[ noteStack ],
                            width: width,
                            delay: 3000,
                            addclass: 'rtl yekan'
                        } );
                        $( '.ladda-button' ).hide();
                        $( "#image_blog" ).fadeIn( 'fast' ).find( 'img' ).attr( 'src', url );
                        $( "#inputImageNews" ).val( url );
                        
                    }
                } ).fail( function () {
                    new PNotify( {
                        title: 'آپلود',
                        text: 'متاسفانه فایل آپلود نشد.<br/>توجه داشته باشید فقط فایل تصویری مجاز است',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[ noteStack ],
                        width: width,
                        delay: 5000,
                        addclass: 'rtl yekan'
                    } );
                    ladda_btn.stop();
                } );
            }
            */
            
            /******* -- upload image -------*/
                
                // Init Summernote
            var noteStyle = 'success';
            var noteShadow = true;
            var noteOpacity = '1';
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
                    "context": $( "#stack-context" )
                }
            };
            
            function removeImage()
            {
                data = new FormData();
                var file = $( "#inputImageNews" ).val();
                data.append( "file", file );
                data.append( "_token", "{{ csrf_token() }}" );
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.blog.removeImage') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function ( url ) {
                        new PNotify( {
                            title: 'حذف عکس',
                            text: 'عکس با موفقیت حذف شد',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[ noteStack ],
                            width: width,
                            delay: 3000,
                            addclass: 'rtl yekan'
                        } );
                        $( '.ladda-button' ).show();
                        $( "#image_blog" ).hide();
                        $( "#inputImageNews" ).val( '' );
                        ladda_btn.stop();
                        
                    }
                } ).fail( function () {
                    new PNotify( {
                        title: 'حذف عکس',
                        text: 'امکان حذف عکس فعلا مقدور نمیباشد . ‌<br /> لطفا مجدد تلاش کنید',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[ noteStack ],
                        width: width,
                        delay: 5000,
                        addclass: 'rtl yekan'
                    } );
                } );
            }
            
            function upload( file )
            {
                data = new FormData();
                data.append( "file", file );
                data.append( "_token", "{{ csrf_token() }}" );
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.blog.upload') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function ( url ) {
                        new PNotify( {
                            title: 'آپلود',
                            text: 'فایل با موفقیت بارگزاری شد.',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[ noteStack ],
                            width: width,
                            delay: 3000,
                            addclass: 'rtl yekan'
                        } );
                        $( '.ladda-button' ).hide();
                        $( "#image_blog" ).fadeIn( 'fast' ).find( 'img' ).attr( 'src', url );
                        $( "#inputImageNews" ).val( url );
                        
                    }
                } ).fail( function () {
                    new PNotify( {
                        title: 'آپلود',
                        text: 'متاسفانه فایل آپلود نشد.<br/>توجه داشته باشید فقط فایل تصویری مجاز است',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[ noteStack ],
                        width: width,
                        delay: 5000,
                        addclass: 'rtl yekan'
                    } );
                    ladda_btn.stop();
                } );
            }
            
            function sendFile( file, editor, welEditable )
            {
                
                data = new FormData();
                data.append( "file", file );
                data.append( "_token", "{{ csrf_token() }}" );
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "{{ route('admin.blog.upload') }}",
                    cache: false,
                    contentType: false,
                    timeout: 3000000,
                    processData: false,
                    success: function ( url ) {
                        new PNotify( {
                            title: 'آپلود',
                            text: 'فایل با موفقیت بارگزاری شد.',
                            shadow: noteShadow,
                            opacity: noteOpacity,
                            addclass: noteStack,
                            type: noteStyle,
                            stack: Stacks[ noteStack ],
                            width: width,
                            delay: 3000,
                            addclass: 'rtl yekan'
                        } );
                        editor.insertImage( welEditable, url );
                    }
                } ).fail( function () {
                    new PNotify( {
                        title: 'آپلود',
                        text: 'متاسفانه فایل آپلود نشد.<br/>توجه داشته باشید فقط فایل تصویری مجاز است',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[ noteStack ],
                        width: width,
                        delay: 5000,
                        addclass: 'rtl yekan'
                    } );
                } );
            }
            
            $( '.summernote' ).summernote( {
                lang: 'fa-IR', // default: 'en-US'
                height: 255, //set editable area's height
                focus: false, //set focus editable area after Initialize summernote
                oninit: function () {
                },
                onChange: function ( contents, $editable ) {
                },
                onImageUpload: function ( files, editor, welEditable ) {
                    sendFile( files[ 0 ], editor, welEditable );
                }
            } );
            $( ".tm-input-seo" ).tagit( {
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true
            } );
            $( ".tm-input" ).tagit( {
                singleField: true,
                singleFieldNode: $( '#mySingleField' ),
                allowSpaces: true,
                minLength: 2,
                removeConfirmation: true,
                tagSource: function ( request, response ) {
                    //console.log("1");
                    $.ajax( {
                        url: "{{ url('/admin/blog/tag') }}",
                        data: { term: request.term },
                        dataType: "json",
                        success: function ( data ) {
                            response( $.map( data, function ( item ) {
                                return {
                                    label: item.name,
                                    value: item.name
                                };
                            } ) );
                        }
                    } );
                }
            } );
        } );
    </script>
    
    
    
    
    <script>
        $( function () {
            $( 'input[name=show_at_home_top]' ).first().change( function () {
                if ( $( this ).is( ':checked' ) )
                {
                    $( 'textarea[name=aparat]' ).first().removeProp( 'disabled' );
                }
                else
                {
                    $( 'textarea[name=aparat]' ).first().prop( 'disabled', 'disabled' );
                }
            } );
            
            
            $( 'input[name=show_at_home_top]' ).first().trigger( 'change' );
        } );
    </script>
@endpush
