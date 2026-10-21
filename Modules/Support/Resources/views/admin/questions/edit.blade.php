@extends('admin.layout.layout')
@section('title', $title)
@section('content')
    <div id="content">
        <div class="row rtl yekan">
            <form method="post" action="{{ route('admin.support.questions.update', $question) }}">
                @method('PATCH')
                {{ csrf_field() }}
                
                @if (Session::has('sys_error'))
                    <div class="alert alert-danger alert-dismissable rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×
                        </button>
                        <i class="fa fa-remove pr10"></i>
                        <strong>خطا!</strong>
                        {{ Session::get('sys_error') }}
                    </div>
                @endif
                @if(Session::has('successful'))
                    <div class="alert alert-success alert-dismissable rtl yekan">
                        <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×
                        </button>
                        <i class="fa fa-check pr10"></i>
                        {{ Session::get('successful') }}
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
                
                <div class="panel rtl yekan">
                    <div class="panel-heading">
                        <span class="panel-title">ویرایش سوال پرتکرار</span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group mbn">
                            <div style="clear: both"></div>
                            <div class="form-group row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-9 admin-form">
                                        <input type="text" class="gui-input" id="title"
                                               name="title" value="{{ $question->title }}" placeholder="عنوان سوال..">
                                </div>
                                <label for="question" class="col-lg-2 control-label">عنوان سوال</label>
                                <div style="clear: both"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-9">
                                        <textarea class="summernote" id="question"
                                                  name="question">{{ $question->question }}</textarea>
                                </div>
                                <label for="question" class="col-lg-2 control-label">متن سوال</label>
                                <div style="clear: both"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-9">
                                        <textarea class="summernote" id="answer"
                                                  name="answer">{{ $question->answer }}</textarea>
                                </div>
                                <label for="answer" class="col-lg-2 control-label">متن پاسخ</label>
                                <div style="clear: both"></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-primary pull-left" type="submit">
                            ویرایش سوال
                        </button>
                        <div class="clear-div"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Summernote CSS  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/editors/summernote/summernote-bs3.css') }}">
    
    <!-- Summernote JS -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/editors/summernote/summernote-fa-IR.js') }}"></script>
    
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            
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
            
        } );
    </script>
@endpush
