$( document ).ready( function () {
    
    SyntaxHighlighter.defaults[ 'toolbar' ] = false;
    SyntaxHighlighter.all();
    
    $( '.show_bellow_code' ).click( function () {
        if ( ! $( this ).next().find( '.hljs' ).hasClass('height-auto') )
            $( this ).next().find( '.hljs' ).addClass('height-auto').css( 'height', 'auto' );
        else
            $( this ).next().find( '.hljs' ).removeClass('height-auto').css( 'height', '0' );
    } );
    
} );
