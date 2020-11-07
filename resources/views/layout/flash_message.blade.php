@if(session()->has('message'))
    <div class="alert alert-{{ session('message_type') }} mt-r" >
        {{ session('message') }}
    </div>
@endif
