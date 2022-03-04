<?php
$under_line_counter++;
?>
@foreach($cats as $cat)
    <option value="{{ $cat->id }}">
        @for($i=1;$i<$under_line_counter;$i++)
            -
        @endfor
        {{ $cat->title }}</option>
    @if($cat->children()->exists())
        @include('blog::admin.layouts._option_cat_item', array('cats' => $cat->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach