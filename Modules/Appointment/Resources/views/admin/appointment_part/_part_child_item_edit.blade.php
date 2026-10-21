<?php
$under_line_counter++;
?>
@foreach($main_part as $p_item)
    @continue($p_item->id == $part->id)
    <option value="{{ $p_item->id }}">
        @for($i=1;$i<$under_line_counter;$i++)
            -
        @endfor
        {{ $p_item->title }}</option>
    @if($p_item->children()->exists())
        @include('appointment::admin.appointment_part._part_child_item_edit', array('main_part' => $p_item->children,'part'=>$part,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach