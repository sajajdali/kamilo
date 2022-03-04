<?php
$under_line_counter++;
?>
@foreach($cats as $cat)
    @unless($cat->id == $category->id)
        <option value="{{ $cat->id }}" @if($cat->id == $category->parent) selected="selected" @endif>
            @for($i=1;$i<$under_line_counter;$i++)
                -
            @endfor
            {{ $cat->title }}</option>
        @if($cat->children()->where('parent','<>',$cat->id)->exists())
            @include('blog::admin.layouts._option_cat_item', array('cats' => $cat->children,'under_line_counter'=>$under_line_counter))
        @endif
    @endunless
@endforeach