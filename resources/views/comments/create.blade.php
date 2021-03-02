@include('layout.errors')

<form method="post" action="{{ route('comments.store') }}">

    @csrf

    <div class="form-group">
        <label for="body">Детальное описание статьи</label>
        <textarea cols="30" rows="7" class="form-control" name="body">{{ old('body', $comment->body) }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>