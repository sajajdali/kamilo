<?php
$under_line_counter++;
?>
@foreach($mian_part as $part)
    <option value="{{ $part->id }}">
        @for($i=1;$i<$under_line_counter;$i++)
            -
        @endfor
        {{ $part->title }}</option>
    @if($part->children()->exists())
        @include('support::admin.layouts._option_part_item', array('mian_part' => $part->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach