@include('layout.errors')

<form method="post" action="{{$action}}">

    @csrf

    <div class="form-group">
        <label for="body">Оставьте ваш комментарий</label>
        <textarea cols="30" rows="2" class="form-control" name="body">{{ old('body') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>