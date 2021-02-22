
<h3>Здравствуйте!</h3>
<br>
<ul>
@foreach($posts as $post)
    <li>{{$post->title}} <a href="{{ route('posts.show', ['post' => $post]) }}" class="stretched-link">читать>></a></li>
@endforeach
</ul>
<br>
<h3>Спасибо, что подписаны на наш блог!</h3>