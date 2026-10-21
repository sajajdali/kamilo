@extends('admin.layout.layout')
@section('content')
    <!-- Start: Topbar -->
    <header id="topbar">
        <div class="topbar-right rtl yekan">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="dashboard.html">مدیریت منو</a>
                </li>
                <li class="crumb-icon">
                    <a href="dashboard.html">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </li>
                <li class="crumb-link">
                    <a href="index.html">خانه</a>
                </li>
            </ol>
        </div>
    </header>
    <!-- End: Topbar -->
    
    <!-- Begin: Content -->
    <section id="content" class="table-layout animated fadeIn">
        <!-- begin: .tray-left -->
        <aside class="tray tray-left tray350 va-t p20" data-tray-height="match">
            <div class="animated-delay rtl yekan" data-animate='["200","fadeIn"]'>
                <h4 class="mt5 mb20"> اضافه کردن -
                    <span class="fs14 fw400 text-muted">افزودن ایتم جدید</span>
                </h4>
                <form method="post">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="admin-form">
                        <?php
                        $counter = 0;
                        ?>
                        @foreach(Config::get('app.locales')  as $key=>$value)
                            <div class="section">
                                <label class="field prepend-icon" for="title">
                                    <input value="{{ old('title.'.$counter) }}" type="text" placeholder="عنوان {{ $value }}" class="gui-input" id="title_{{ $key }}" name="title[]" aria-required="true">
                                    <label class="field-icon" for="title_{{ $key }}">
                                        <i class="fa fa-globe"></i>
                                    </label>
                                </label>
                            </div><!-- end section -->
                            <?php
                            $counter++;
                            ?>
                        @endforeach
                        <div class="section">
                            <label class="field prepend-icon" for="title">
                                <input value="{{ old('link') }}" type="text" placeholder="لینک منو" class="gui-input" id="link" name="link" aria-required="true">
                                <label class="field-icon" for="link">
                                    <i class="fa fa-link"></i>
                                </label>
                            </label>
                        </div><!-- end section -->
                    </div>
                    <div class="section row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-default btn-sm ph25 yekan">اضافه کردن</button>
                        </div>
                    </div>
                </form>
            </div>
            <hr class="alt short mt25 mb25">
            <div class="animated-delay" data-animate='["600","fadeIn"]'>
                <div class="animations rtl yekan">
                    <h5 class="mt5">حذف ایتم</h5>
                    <form method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="admin-form">
                            <!-- Animation Delay -->
                            <div class="section mb15">
                                <label class="field select">
                                    <select class="yekan empty" name="item_id" id="delete_selection">
                                        <option selected="selected" value="0">انتخاب ایتم</option>
                                        @foreach($all_items as $it)
                                            <option value="{{ $it->id }}">{{ $it->translations('fa') }}</option>
                                        @endforeach
                                    </select>
                                    <i class="arrow double"></i>
                                </label>
                            </div>
                            <div class="section row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-default btn-sm ph25 yekan">حذف</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="alt short mt25 mb25">
            <div class="animated-delay" data-animate='["800","fadeIn"]'>
                <div class="animations rtl yekan">
                    <h5 class="mt5">ویرایش ایتم</h5>
                    <div class="admin-form">
                        <!-- Animation Delay -->
                        <div class="section mb15">
                            <label class="field select">
                                <select class="yekan empty" name="item_id" id="edit_item_select">
                                    <option selected="selected" value="0">انتخاب ایتم</option>
                                    @foreach($all_items as $it)
                                        <option value="{{ $it->id }}">{{ $it->translations('fa') }}</option>
                                    @endforeach
                                </select>
                                <i class="arrow double"></i>
                            </label>
                        </div>
                        <div class="section row">
                            <div class="col-sm-12">
                                <button id="edit_item" type="button" class="btn btn-default btn-sm ph25 yekan">ویرایش
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!-- end: .tray-left -->
        <!-- begin: .tray-center -->
        <div class="tray tray-center ph20 pv30 va-t posr">
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
            <div class="row table-layout rtl yekan">
                <div class="col-xs-5 va-m pln">
                    <p><strong>ویرایش {{ $menu->title }}</strong>
                    </p>
                </div>
                <div class="col-xs-7 va-b prn">
                    <menu id="nestable-menu" class="text-left mb10">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default">
                                <input data-action="collapse-all" type="radio" name="options" class="change_menu" autocomplete="off" checked>
                                بستن همه
                            </label>
                            <label class="btn btn-default">
                                <input data-action="expand-all" type="radio" name="options" class="change_menu" autocomplete="off">
                                بازکردن همه
                            </label>
                        </div>
                    </menu>
                </div>
            </div>
            <div class="row mt5">
                <form method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="col-md-12">
                        <!-- List 1 -->
                        <div class="dd mb35" id="nestable">
                            <ol class="dd-list">
                                @include('personalize::layouts.menu_item', array('items' => $items,'counter'=>'0'))
                            </ol>
                        </div>
                        <div class="nestable-output hidden">
                            <p><strong>داده های منو </strong>
                            </p>
                            <textarea id="nestable-output" class="form-control hidden" name="menu"></textarea>
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
                </form>
            </div>
        </div>
        <!-- end: .tray-center -->
    </section>
    <!-- End: Content -->
    
    <!-- Admin Form Popup -->
    <div id="modal-form" class=" popup-basic mfp-with-anim mfp-hide rtl yekan">
        <div class="panel">
            <div class="admin-form">
                <div class="panel-heading">
                    <span class="panel-title pull-right yekan"><i class="fa fa-edit"></i>ویرایش ایتم</span>
                </div>
            </div>
            <!-- end .panel-heading section -->
            <form method="post" id="form_update_setting">
            </form>
        </div>
        <!-- end: .panel -->
    </div>
@endsection
@push('scripts')
    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ Module::asset('personalize:ex/magnific-popup.css') }}">
    <!-- Page Plugins -->
    <script type="text/javascript" src="{{ Module::asset('personalize:ex/jquery.magnific-popup.min.js') }}"></script>
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="{{ Module::asset('personalize:ex/adminmodal.css') }}">
    
    <!-- Nestable CSS -->
    <link rel="stylesheet" type="text/css" href="{{ Module::asset('personalize:ex/nestable.css') }}">
    
    <!-- Plugin JS -->
    <script type="text/javascript" src="{{ Module::asset('personalize:ex/jquery.nestable.js') }}"></script>
    <script type="text/javascript">
        jQuery( document ).ready( function () {
            //edit part
            
            // Init custom page animation
            setTimeout( function () {
                $( '.custom-nav-animation li' ).each( function ( i, e ) {
                    var This = $( this );
                    var timer = setTimeout( function () {
                        This.addClass( 'animated zoomIn' );
                    }, 100 * i );
                } );
            }, 600 );
            
            // Init tray navigation smooth scroll
            $( '.tray-nav a' ).smoothScroll( {
                offset: -145
            } );
            
            // Nestable Output
            var updateOutput = function ( e ) {
                var list = e.length ? e : $( e.target ),
                    output = list.data( 'output' );
                if ( window.JSON )
                {
                    output.val( window.JSON.stringify( list.nestable( 'serialize' ) ) ); //, null, 2));
                } else
                {
                    output.val( 'JSON browser support required for this demo.' );
                }
            };
            
            // Init Nestable on list 1
            $( '#nestable' ).nestable( {
                group: 1
            } ).on( 'change', updateOutput );
            
            
            // nestable serialized output functionality
            updateOutput( $( '#nestable' ).data( 'output', $( '#nestable-output' ) ) );
            
            $( '.change_menu' ).on( 'click', function () {
                action = $( this ).data( 'action' );
                if ( action === 'expand-all' )
                {
                    $( '.dd' ).nestable( 'expandAll' );
                }
                if ( action === 'collapse-all' )
                {
                    $( '.dd' ).nestable( 'collapseAll' );
                }
            } );
            // nestable menu functionality
            $( '#nestable-menu' ).on( 'change', function ( e ) {
                var target = $( e.target ),
                    action = target.data( 'action' );
                if ( action === 'expand-all' )
                {
                    $( '.dd' ).nestable( 'expandAll' );
                }
                if ( action === 'collapse-all' )
                {
                    $( '.dd' ).nestable( 'collapseAll' );
                }
            } );
            
            $( '#edit_item' ).on( 'click', function () {
                var value_get = $( '#edit_item_select' ).val();
                if ( value_get == '0' )
                {
                    alert_danger( 'لطفا یک منو جهت ویرایش انتخاب نمایید.' );
                } else
                {
                    send_request( value_get );
                }
            } );
            
        } );
        
        function alert_danger( text )
        {
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
                    "context": $( "#stack-context" )
                }
            };
            new PNotify( {
                title: 'خطا',
                text: text,
                shadow: noteShadow,
                opacity: noteOpacity,
                addclass: noteStack,
                type: noteStyle,
                stack: Stacks[ noteStack ],
                width: width,
                delay: 3000
            } );
        }
        
        function send_request( id )
        {
            
            data = new FormData();
            data.append( "id", id );
            data.append( "_token", "{{ csrf_token() }}" );
            $.ajax( {
                data: data,
                type: "POST",
                url: "{{ url('admin/personalize/menu/getDetail') }}",
                cache: false,
                contentType: false,
                timeout: 180000,
                processData: false,
                success: function ( data ) {
                    $( '#form_update_setting' ).html( data );
                    $.magnificPopup.open( {
                        removalDelay: 500, //delay removal by X to allow out-animation,
                        items: {
                            src: '#modal-form'
                        },
                        // overflowY: 'hidden', //
                        callbacks: {
                            beforeOpen: function ( e ) {
                                var Animation = 'mfp-zoomOut';
                                this.st.mainClass = Animation;
                            }
                        },
                        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                    } );
                }
            } ).fail( function () {
                alert_danger( 'ارتباط برقرار نشد لطفا مجددا تلاش نمایید.' );
            } );
        }
    </script>
    <!-- END: PAGE SCRIPTS -->

@endpush
