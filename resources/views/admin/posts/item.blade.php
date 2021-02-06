<tr>
    <th scope="row">{{$loop->iteration}}</th>
    <td>{{ $post->title }}</td>
    <td >
        {{ $post->short_text }}
    </td>
    <td>
        <form method="post" action="{{ route('admin.posts.update', ['post' => $post]) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" value="{{$post->published ? 0 : 1}}" name="published">
            <button type="submit" class="btn btn-sm {{$post->published ? "btn-danger" : "btn-success"}}">{{$post->published ? "Снять с публикации" : "Опубликовать"}}</button>
        </form>
    </td>
    <td>
        {{$post->owner->name}}
    </td>
    <td><a href="{{ route('admin.posts.edit', ['post' => $post]) }}" class="btn btn-sm btn-primary">Изменить</a></td>

</tr>
