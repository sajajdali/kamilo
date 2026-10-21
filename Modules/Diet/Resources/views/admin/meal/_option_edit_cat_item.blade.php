<?php
$under_line_counter++;
?>
@foreach($cats as $cat)
    @if($cat->status == 0)
        @continue
    @endif
    <option value="{{ $cat->id }}">
        @for($i=1;$i<$under_line_counter;$i++)
            -
        @endfor
        {{ $cat->name }}</option>
    @if($cat->children()->exists())
        @include('diet::admin.meal._option_cat_item', array('cats' => $cat->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach