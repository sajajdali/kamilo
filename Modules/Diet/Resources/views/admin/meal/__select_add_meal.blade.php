<?php
$under_line_counter++;
?>
@foreach($cats as $cat)
    <label class="block mt15 option option-primary"
           style="margin-right: {{ ($under_line_counter*20) }}px"
    >
        <input type="checkbox"
               @if(isset($old_category))
               @foreach($old_category as $oldCat)
               @if($oldCat->id == $cat->id)
               checked
               @endif
               @endforeach
               @endif
               @if(old('cat.'.$cat->id) == $cat->id) checked @endif data-type="{{$cat->type}}" value="{{ $cat->id }}" name="cat[{{$cat->id}}]">
        <span class="checkbox"></span>{{ $cat->name }}</label>
    @if($cat->children()->exists())
        @include('diet::admin.meal.__select_add_meal', array('cats' => $cat->children,'under_line_counter'=>$under_line_counter))
    @endif
@endforeach