<div class="blog-post">
    <h2 class="blog-post-title"><a href="{{route('tasks.show', ['task' => $task])}}">{{$task->title}}</a></h2>
    <p class="blog-post-meta">{{$task->created_at->toFormattedDateString()}} <a href="#"></a></p>

    @include('layout.tags', [
                'tags' => $task->tags,
                'alias' => 'tasks',
            ])

    {{$task->body}}
</div>
