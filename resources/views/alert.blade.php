<div class="alert alert-{{$type ?? 'danger'}} mt-r" >
    @isset($title)
        <h4 class="alert-heading">{{ $title }}</h4>
    @endisset
    {{ $slot }}
</div>
