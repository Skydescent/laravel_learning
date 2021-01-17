<tr>
    <th scope="row">{{$loop->iteration}}</th>
    <td>{{ $post->slug }}</td>
    <td>{{ $post->title }}</td>
    <td >
        {{ $post->short_text }}
        <a href="{{ route('posts.show', ['post' => $post]) }}">Продолжить читать</a>
    </td>
    <td>{{$post->published ? "Опубликована" : "Не опубликована"}}</td>
    <td>Автор</td>
    <td>Действия</td>

</tr>
