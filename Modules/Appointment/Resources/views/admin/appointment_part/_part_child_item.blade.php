<?php
$under_line_counter++;
?>
@foreach($main_part as $part)
    <option value="{{ $part->id }}">
        @for($i=1;$i<$under_line_counter;$i++)
            -
        @endfor
        {{ $part->title }}</option>
    @if($part->children()->exists())
        @include('appointment::admin.appointment_part._part_child_item', array('main_part' => $part->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach