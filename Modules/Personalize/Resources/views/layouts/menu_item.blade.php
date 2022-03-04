<?php
$counter++;
?>
@foreach($items as $item)
    <li class="dd-item" data-id="{{ $item->id }}">
        <div class="dd-handle">
            {{ $item->translations('fa') }}
        </div>
    @if($item->children()->exists())
        <ol class="dd-list">
            @include('personalize::layouts.menu_item', array('items' => $item->children,'counter'=>$counter))
        </ol>
    @endif
    </li>
@endforeach