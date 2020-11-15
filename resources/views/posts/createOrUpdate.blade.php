<div class="form-group">
    <label for="slug">Slug статьи</label>
    <input type="text" class="form-control" id="slug" placeholder="введите slug статьи"
           name= "slug"
           value="{{ old('slug', $post->slug) }}">
</div>
<div class="form-group">
    <label for="inputTitle">Название статьи</label>
    <input type="text" class="form-control" id="inputTitle" placeholder="Введите заголовок статьи"
           name= "title"
           value="{{ old('title', $post->title) }}">
</div>

<div class="form-group">
    <label for="shortText">Краткое описание статьи</label>
    <textarea name="short_text" id="shortText" cols="30" rows="2" class="form-control" >{{ old('short_text', $post->short_text) }}</textarea>
</div>
<div class="form-group">
    <label for="body">Детальное описание статьи</label>
    <textarea cols="30" rows="7" class="form-control" name="body">{{ old('body', $post->body) }}</textarea>
</div>
<div class="form-group">
    <label for="inputTags">Тэги</label>
    <input type="text"
           name="tags"
           class="form-control"
           id="inputTags"
           value="{{ old('tags', $post->tags->pluck('name')->implode(',')) }}">
</div>
<div class="form-group form-check">
    <input type="checkbox" value="1" class="form-check-input" id="isPublished"
           name="published"
        {{ $post->published == '1' ? 'checked' : ''}}>
    <label class="form-check-label" for="isPublished">
        Опубликовать
    </label>
</div>

<button type="submit" class="btn btn-primary">{{ __($btnText) }}</button>

