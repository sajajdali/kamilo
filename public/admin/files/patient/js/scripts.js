var _time_input_keyup_count = 0;
$( document ).ready( function () {
    var _step_content = $( '.step-content' );
    if ( _step_content.length )
    {
        $( '.wizard li[data-step-id="' + _step_content.data( 'step-id' ) + '"]' ).addClass( 'active-step' );
    }
    
    /*
    if( $('.pmw-choices').length )
    {
        $('.pmw-choices ul li').click(function () {
            $('.pmw-choices > input').val( $(this).data('choice-id') );
            $('.pmw-choices ul li').removeClass('active-choice');
            $(this).addClass('active-choice');
        })
    }
    
    if( $('#pmw-wrapper .session-box').length )
    {
        $('body').on( 'click', '#pmw-wrapper .session-box .add-new-session', function () {
            var _this = $(this).parent();
            add_new_session( _this );
        })
        $('body').on( 'click', '#pmw-wrapper .session-box .remove-session', function () {
            var _this = $(this);
            if( $('#pmw-wrapper .session-box').length > 1 )
            {
                _this.parent().remove();
            }
            handle_input_names( '' );
        })
    }
    
    if( $('#pmw-wrapper .session-box').length == 1 )
    {
        $('#pmw-wrapper .session-box .add-remove-button').fadeOut(0);
        $('#pmw-wrapper .machine-properties input, #pmw-wrapper .machine-properties textarea').each(function () {
            $(this).on( 'keydown change', function () {
                var _add_key = $('#pmw-wrapper .session-box .add-remove-button');
                var _session_numbers = $('#pmw-wrapper .sessions_number');
                if ( ! _add_key.is(':visible') || ! _session_numbers.is(':visible') )
                {
                    _add_key.fadeIn();
                    _session_numbers.slideDown();
                }
            })
        })
    }
    
    if ( $('#sessions_number').length )
    {
        $('#sessions_number').change(function () {
            var _number = $(this).val();
            var _available = $('#pmw-wrapper .session-box').length;
            var _addable = ( _available > _number ) ? false : _number - _available;
            
            if ( ! _addable )
            {
                return false;
            }
            
            for ( i = 1; i <= _addable; i++ )
            {
                var _clone = $('#pmw-wrapper .session-box:last-child');
                add_new_session( _clone );
            }
        })
    }
    
    // verify input names
    handle_input_names( '' );
    */
    if ( typeof _case_device_times != 'undefined' )
    {
        var _case_device_times_select_tags = '';
        for ( var key in _case_device_times )
        {
            _case_device_times_select_tags += "<option value='" + key + "'>" + key + "</option>";
        }
        $( '._case_device_times' ).each( function () {
            $( this ).append( _case_device_times_select_tags );
        } );
    }
    
    /**
     * persian date picker
     */
    $( '.persian_date_picker' ).persianDatepicker( {
        observer: true,
        format: 'YYYY/MM/DD',
        initialValueType: 'persian'
    } );
    
    $( '._case_device_times' ).each( function () {
        $( this ).change( function () {
            var _value = $( this ).val();
            $( this ).parent().prev().find( 'input' ).val( _case_device_times[ _value ] );
        } );
    } );
    
    $( '#device_properties_form' ).submit( function ( e ) {
        var _has_unfilled = false;
        var _required = $( ".required" );
        _required.removeClass('buzz');
        _required.each( function () {
            var _this = $( this );
            if ( _this.find( '> textarea' ).length )
            {
                if ( !_this.find( '> textarea' ).val().length )
                {
                    _has_unfilled = true;
                    _this.addClass('buzz');
                }
            }
            else if ( _this.find( '> label' ) )
            {
                if ( !_this.find( 'input:checked' ).length )
                {
                    _has_unfilled = true;
                    _this.addClass('buzz');
                }
            }
        } );
        if ( _has_unfilled )
        {
            e.preventDefault();
        }
    } );
    
    $('.time_input:first-child').keyup(function (e) {
        if ( e.which >= 96 && e.which <= 105 )
            _time_input_keyup_count++;
        
        if ( _time_input_keyup_count == 2 )
        {
            _time_input_keyup_count = 0;
            $(this).next().focus();
        }
    })
    
    if ( $('.body-container .body-part').length )
    {
        $('.body-container .body-part, .body-container .body-part-buttons li').click(function () {
            var _this = $(this);
            var _part_id = _this.data('part-id');
            
            if ( ! _this.hasClass('active') )
            {
                $('.body-container .body-image .body-part[data-part-id="' + _part_id + '"]').addClass('active');
                $('.body-container .body-part-buttons li[data-part-id="' + _part_id + '"]').addClass('active');
                $('.body-container .front-body-parts-fields input[value="' + _part_id + '"]').prop('checked', true);
            }
            else
            {
                $('.body-container .body-image .body-part[data-part-id="' + _part_id + '"]').removeClass('active');
                $('.body-container .body-part-buttons li[data-part-id="' + _part_id + '"]').removeClass('active');
                $('.body-container .front-body-parts-fields input[value="' + _part_id + '"]').prop('checked', false);
            }
        })
    
        $('.body-container .body-part, .body-container .body-part-buttons li').mouseover(function () {
            console.log( 'afaf' );
            $('.body-container .body-image .body-part[data-part-id="' + $(this).data('part-id') + '"]').addClass('highlight');
            $('.body-container .body-part-buttons li[data-part-id="' + $(this).data('part-id') + '"]').addClass('highlight');
        }).mouseleave(function () {
            $('.body-container .body-image .body-part[data-part-id="' + $(this).data('part-id') + '"]').removeClass('highlight');
            $('.body-container .body-part-buttons li[data-part-id="' + $(this).data('part-id') + '"]').removeClass('highlight');
        });
        
        $('.front-or-behind-buttons li').click(function () {
            $('.front-or-behind-buttons li').removeClass('active');
            $(this).addClass('active');
            $('.body-container .body-image, .body-part-buttons').fadeOut(0);
            $('.body-container .body-image[data-side-id="' + $(this).data('side-id') + '"], .body-part-buttons[data-side-id="' + $(this).data('side-id') + '"]').fadeIn();
        })
    }

    if ( $('.widget-box').length )
    {
        $('.widget-box .widget-title').click(function () {
            var _this = $(this);
            if ( _this.parent().hasClass( 'collapsed' ) )
            {
                _this.parent().removeClass('collapsed');
                _this.next().slideUp( 300 );
            }
            else
            {
                _this.parent().addClass('collapsed');
                _this.next().slideDown( 300 );
            }
        })
    }

    if ( $('.widget-box .widget-content .body-container').length )
    {
        var _image_height = $('.machine-properties').height();
        $('.widget-box .body-image .main-body-image').each(function () {
            $(this).css({
                'height': _image_height + 'px',
                'width': 'auto'
            }).parent().css({
                'height': $(this).height() + 'px',
                'width': $(this).width() + 'px'
            });
        })
        $(window).resize(function () {
            var _image_height = $('.machine-properties').height();
            $('.widget-box .body-image .main-body-image').each(function () {
                $(this).css({
                    'height': _image_height + 'px',
                    'width': 'auto'
                }).parent().css({
                    'height': $(this).height() + 'px',
                    'width': $(this).width() + 'px'
                });
            })
        })
    }
    
    if ( $('.widget-box.device-widget-box').length )
    {
        $('.widget-box.device-widget-box').each(function () {
            if ( ! $(this).hasClass('collapsed') )
                $(this).find('.widget-content').hide(0);
        })
    }
} );

function add_new_session( _this )
{
    var _clone = _this.clone();
    var _select_indexes = new Array();
    
    handle_input_names( _clone );
    
    var _counter = 0;
    _this.find( 'select' ).each( function () {
        _select_indexes[ _counter ] = $( this ).val();
        _counter++;
    } );
    var _counter = 0;
    _clone.find( 'select' ).each( function () {
        $( this ).val( _select_indexes[ _counter ] );
        _counter++;
    } );
    _this.parent().append( _clone );
}


function handle_input_names( clone )
{
    var _index_counter = 0;
    var _boxes = $( '#pmw-wrapper .session-box' );
    _boxes.each( function () {
        var _this = $( this );
        
        _this.find( 'input, textarea, select' ).each( function () {
            var _name = $( this ).attr( 'name' ).replace( /([a-zA-Z0-9]*)\[.*/, '$1' );
            $( this ).attr( 'name', _name + '[' + _index_counter + ']' );
        } );
        _index_counter++;
    } );
    if ( clone.length )
    {
        clone.find( 'input, textarea, select' ).each( function () {
            var _name = $( this ).attr( 'name' ).replace( /([a-zA-Z0-9]*)\[.*/, '$1' );
            $( this ).attr( 'name', _name + '[' + _index_counter + ']' );
        } );
    }
}


function trigger_keypress( el, which) {
    var e = $.Event('keyup');
    e.which = which; // Character 'A'
    el.trigger(e);
}