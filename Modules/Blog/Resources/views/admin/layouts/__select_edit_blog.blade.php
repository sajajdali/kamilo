<?php
$under_line_counter++;
?>
@foreach($cats as $cat)
    <label class="block mt15 option option-primary"
           style="margin-right: {{ ($under_line_counter*20) }}px"
    >
        <input type="checkbox" value="{{ $cat->id }}" name="cat[]"
        @foreach($categories as $added_cat)
            @if($added_cat->id == $cat->id)
                CHECKED
            @endif
        @endforeach
        >
        <span class="checkbox"></span>{{ $cat->title }}</label>
    @if($cat->children()->exists())
        @include('blog::admin.layouts.__select_edit_blog', array('cats' => $cat->children,'under_line_counter'=>$under_line_counter,'categories'=>$categories))
    @endif
@endforeach