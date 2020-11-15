<div class="col">
    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
            <h3 class="mb-0">{{ $post->title }}</h3>
            @include('layout.tags', [
                'tags' => $post->tags,
                'alias' => 'posts',
            ])
            <div class="mb-1 text-muted">{{ $post->created_at->toFormattedDateString() }}</div>
            <p class="card-text mb-auto">{{ $post->short_text }}</p>
            <a href="{{ route('posts.show', ['post' => $post]) }}">Продолжить читать</a>
        </div>
        <div class="col-auto d-none d-lg-block">
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        </div>
    </div>
</div>
