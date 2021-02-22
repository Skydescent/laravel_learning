<ul>
@foreach($posts as $post)
    <li>{{$post->title}} <a href="{{ route('posts.show', ['post' => $post]) }}" class="stretched-link">читать>></a></li>
@endforeach
</ul>