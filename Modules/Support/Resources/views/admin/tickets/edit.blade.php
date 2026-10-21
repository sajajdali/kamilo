@extends('admin.layout.layout')
@section('title', $title)
@section('content')
    <div id="content">
        <div class="row rtl yekan">
            <form method="post" enctype="multipart/form-data" id="send_ticket_all" action="{{ route('admin.support.tickets.update', $ticket->id) }}">
                @method('PUT')
                {{ csrf_field() }}
                <input type="hidden" id="parent_id" name="parent_id" value="0">
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">جزییات تیکت</span>
                        </div>
                        <div class="panel-body">
                            <div class="pull-right">
                                <i class="fa fa-calendar" style="margin-left: 5px;"></i>
                                تاریخ ایجاد تیکت :
                                {{ jdate('d F Y - h:i:s',strtotime($ticket->created_at)) }}
                            </div>
                            <div style="clear:both"></div>
                            <br>
                            <div class="pull-right">
                                <i class="fa fa-sort-alpha-asc" style="margin-left: 5px;"></i>
                                کد پیگیری تیکت :
                                {{ $ticket->code }}
                            </div>
                            <div style="clear:both"></div>
                            <br>
                            <div class="pull-right">
                                <i class="fa fa-certificate" style="margin-left: 5px;"></i>
                                نوع مشاوره درخواستی:
                                {{ $ticket->part->title }}
                            </div>
                            <div style="clear:both"></div>
                            <br>
                            <div class="pull-right">
                                <i class="fa fa-bookmark" style="margin-left: 5px;"></i>
                                تعداد سوالات و پاسخ ها :
                                {{ number_format($data['ticket_massage_cont']) }}
                            </div>
                            <div style="clear:both"></div>
                            <div style="clear:both"></div>
                            <hr style="margin: 20px 0px" />
                            <div class="col-xs-6 pull-right">
                                <button class="btn btn-primary btn-block" value="published" name="submit" type="submit">
                                    ارسال پیام
                                </button>
                            </div>
                            <div class="col-xs-6 pull-right">
                                @if($ticket->status == 2)
                                    <a href="{{ route('admin.support.tickets.changeStatus', $ticket->id) }}">
                                        <button class="btn btn-primary btn-success" value="published" name="submit"
                                                type="button">باز کردن تیکت
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('admin.support.tickets.changeStatus', $ticket->id) }}">
                                        <button class="btn btn-primary btn-danger" value="published" name="submit"
                                                type="button">بستن تیکت
                                        </button>
                                    </a>
                                @endif
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
                    @if(Session::has('successful'))
                        <div class="alert alert-success alert-dismissable rtl yekan">
                            <button aria-hidden="true" data-dismiss="alert" class="close pull-left" type="button">×
                            </button>
                            <i class="fa fa-check pr10"></i>
                            {{ Session::get('successful') }}
                        </div>
                    @endif
                    <div class="panel rtl yekan">
                        <div class="panel-heading">
                            <span class="panel-title">ارسال پاسخ</span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group mbn">
                                <div style="clear: both"></div>
                                <div class="form-group panel-quote" style="display: none;">
                                    <div class="col-lg-10">
                                        
                                        <div class="panel panel-border top panel-default">
                                            <div class="panel-heading">
                                                <span class="panel-title">
                                                    <span class="fa fa-quote-left"></span>
                                                    <span class="quote-ticket">پاسخ به</span>
                                                </span>
                                                <div class="panel-header-menu pull-left mr10 text-muted fs12">
                                                    <button type="button" class="btn btn-danger br2 btn-sm fs12 yekan left remove-quote">
                                                        <span class="glyphicons glyphicons-remove_2"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <label for="message" class="col-lg-2 control-label">پاسخ به تیکت</label>
                                    <div style="clear: both"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10">
                                        <textarea class="summernote" id="message"
                                                  name="message">{{ old('message') }}</textarea>
                                    </div>
                                    <label for="message" class="col-lg-2 control-label">متن پاسخ</label>
                                    <div style="clear: both"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10">
                                        <input type="file" name="attach" id="attach" />
                                        <span class="help-block mt5"><i class="fa fa-bell"></i>فقط فایل های فشرده،تصویری،صوتی و نوشتاری تا 8 مگ مجاز می باشد.</span>
                                    </div>
                                    <label class="col-lg-2 control-label" for="attach">انتخاب فایل</label>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">پیام ها</span>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content pn br-n">
                                <section id="content" class="animated fadeIn">
                                    <div class="mt30 timeline-single rtl yekan" id="timeline">
                                        <div class="row">
                                            <div class="col-sm-6 left-column">
                                                
                                                @include('support::admin.tickets.ticket_details', ['ticketDetails' => $ticketDetails, 'margin' => 0])
                                                
                                            </div>
                                        </div>
                                        <div class="pagination">
                                            {{ $ticketDetails->links() }}
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- TAG MANAGER -->
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('admin/files/assets/js/sms-counter-0.0.1.min.js') }}"></script>
    
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin/files/vendor/plugins/magnific/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript"
            src="{{ asset('admin/files/vendor/plugins/magnific/jquery.magnific-popup.js') }}"></script>
    <!-- Admin Modals CSS -->
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
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            @if($data['ticket_type'] == 'sms')
            $( '#send_ticket_all' ).on( 'submit', function () {
                $( '#lang_input' ).val( $( '#lang' ).text() );
                $( '#character_count_input' ).val( $( '#character_count' ).text() );
            } );
            @endif
            countSMS( $( '#message' ), $( '#lang' ), $( '#character_count' ), $( '#sms_count' ) );
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
                        url: "{{ route('admin.blog.tag') }}",
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
    
            $('.index').each(function (index) {
                $(this).text(++index+'#');
            });
    
        } );

        $('.reply').click(function () {
            $('.panel-quote').removeAttr('style');
            var parent_id = $(this).data('parent');
            $('#parent_id').val(parent_id);
            var index = $(this).parent().find('.index').text().split('#');
            $('.quote-ticket').text('پاسخ به تیکت شماره ' + index[0]);
            scrollTo(document.body, 0, 1250);
            $('.note-editable').focus();
        });

        $('.remove-quote').click(function () {
            $('#parent_id').val(0);
            $('.panel-quote').attr('style', 'display: none;');
        });
        
    </script>
@endpush
