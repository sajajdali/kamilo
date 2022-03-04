<ul>
    @foreach($parts->get() as $part)
        <li id="{{ $part->id }}" @if($part->children()->exists()) class="folder" @endif>{{ $part->title }}
            @include('appointment::admin.appointment.layout._part_child',['parts'=>$part->children()])
        </li>
    @endforeach
</ul>