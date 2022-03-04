<?php
use Modules\Media\Models\Media;
/**
 * @param $bytes
 *
 * @return string
 */
function media_get_file_size( $bytes )
{
    if ( $bytes >= 1073741824 )
    {
        $bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
    } else if ( $bytes >= 1048576 )
    {
        $bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
    } else if ( $bytes >= 1024 )
    {
        $bytes = number_format( $bytes / 1024, 2 ) . ' KB';
    } else if ( $bytes > 1 )
    {
        $bytes = $bytes . ' bytes';
    } else if ( $bytes == 1 )
    {
        $bytes = $bytes . ' byte';
    } else
    {
        $bytes = '0 bytes';
    }
    
    return $bytes;
}

function make_media_uploader( array $args = [] )
{
    return Media::make_media_uploader( $args );
}

function get_media_url( $media_id, $size = 'full' )
{
    return Media::get_media_url( $media_id, $size );
}



