<div class="blog-post">
    <h2 class="blog-post-title"><a href="{{route('tasks.show', ['task' => $task])}}">{{$task->title}}</a></h2>
    <p class="blog-post-meta">@datetime($task->created_at)<a href="#"></a></p>

    @include('tags.cloud', [
                'tags' => $task->tags,
                'alias' => 'tasks',
            ])

    {{$task->body}}
</div>
