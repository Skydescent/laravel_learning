@if($errors->count())
    <div class="alert alert-danger mt-r" >
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
