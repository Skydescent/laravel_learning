<div class="form-group">
    <label for="inputTitle">Название новосити</label>
    <input type="text" class="form-control" id="inputTitle" placeholder="Введите заголовок новости"
           name= "title"
           value="{{ old('title', $news->title) }}">
</div>

<div class="form-group">
    <label for="body">Текст</label>
    <textarea cols="30" rows="7" class="form-control" name="body">{{ old('body', $news->body) }}</textarea>
</div>
<div class="form-group form-check">
    <input type="checkbox" value="1" class="form-check-input" id="isPublished"
           name="published"
            {{ $news->published == '1' ? 'checked' : ''}}>
    <label class="form-check-label" for="isPublished">
        Опубликовать
    </label>
</div>

<button type="submit" class="btn btn-primary">{{ __($btnText) }}</button>
