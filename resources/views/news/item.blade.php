<div class="col">
    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
            <h3 class="mb-0">{{ $pieceOfNews->title }}</h3>
            @include('tags.cloud', [
                'tags' => $pieceOfNews->tags,
                'alias' => 'posts',
            ])
            <div class="mb-1 text-muted">{{ $pieceOfNews->created_at->toFormattedDateString() }}</div>
            <p class="card-text mb-auto">{{ $pieceOfNews->short_body }}</p>
            <a href="{{ route('news.show', ['news' => $pieceOfNews]) }}">Продолжить читать</a>
        </div>
    </div>
</div>
