@php
    // Если $tags не передано, то используем пустую коллекцию
    $tags = $tags ?? collect();
@endphp


@if($tags->isNotEmpty())
    <div>
        @foreach($tags as $tag)
            <a href="/tasks/tags/{{ $tag->getRouteKey() }}" class="badge badge-secondary">{{$tag->name}}</a>
        @endforeach
    </div>
@endif
