@extends('admin.layout.layout')
@section('title',$title ?? 'ویرایش نظر')
@section('content')

    <!-- Start: Topbar -->
    <header id="topbar" class="ph10">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a>مدیریت نظرات</a>
                </li>
                <li class="crumb-trail">ویرایش نظر</li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->

    <div id="content">
        <div class="row rtl yekan">
            <form method="post" action="{{ route('admin.blog.comments.update', $comment->id) }}">

                <input name="_method" type="hidden" value="PUT">

                {{ csrf_field() }}
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">جزییات</span>
                        </div>
                        <div class="panel-body">
                            <div class="pull-right">
                                <i class="fa fa-calendar" style="margin-left: 5px;"></i>
                                تاریخ ارسال :
                                {{ jdate('d F Y - h:i:s',strtotime($comment->create_at)) }}
                            </div>
                            <div style="clear:both;margin-bottom: 5px;"></div>
                            <div class="pull-right">
                                <i class="fa fa-user" style="margin-left: 5px;"></i>
                                ارسال شده توسط :
                                {{ $comment->name }}
                            </div>
                            <div style="clear:both;margin-bottom: 5px;"></div>
                            <div class="pull-right">
                                <i class="fa fa-envelope" style="margin-left: 5px;"></i>
                                ایمیل :
                                {{ $comment->email }}
                            </div>
                            <div style="clear:both;margin-bottom: 5px;"></div>
                            <div class="pull-right ">
                                <i class="fa fa-flag" style="margin-left: 5px;"></i>
                                آی پی :
                                {{ $comment->ip }}
                            </div>
                            <div style="clear:both;margin-bottom: 5px;"></div>
                            <div style="clear:both;"></div>
                            <hr class="short"/>
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-primary btn-block" value="published" name="submit" type="submit">ارسال پاسخ</button>
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
                            <span class="panel-icon"><i class="fa fa-pencil"></i>
                            </span>
                            <span class="panel-title">مدیریت نظر</span>
                        </div>
                        <div class="panel-body">
                            <h3 class="mt5">متن نظر : </h3>
                            <p><?=nl2br($comment->message)?></p>
                            <hr>
                            <h5>ارسال شده در  :
                                <a>{{ $comment->blog->title }}</a>
                            </h5>
                            <hr>
                            <div class="tab-content pn br-n">
                                <div class="" id="tab">
                                    <div style="clear: both"></div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                                <textarea class="summernote" id="answer" name="answer" >@if($comment->children()->exists())
                                                        {!! $comment->children[0]->message !!}
                                                    @endif
                                                </textarea>
                                        </div>
                                        <label for="answer" class="col-lg-2 control-label">ارسال جواب</label>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
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
                    url: "{{ route('admin.blog.upload') }}",
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
                    new PNotify({
                        title: 'آپلود',
                        text: 'امکان درج تصویر در بخش ',
                        shadow: noteShadow,
                        opacity: noteOpacity,
                        addclass: noteStack,
                        type: 'danger',
                        stack: Stacks[noteStack],
                        width: width,
                        delay: 5000,
                        addclass:'rtl yekan'
                    });
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
                        url: "{{ route('admin.blog.tag') }}",
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


