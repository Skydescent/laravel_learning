<tr>
    <th scope="row">{{$loop->iteration}}</th>
    <td>{{ $pieceOfNews->title }}</td>
    <td >
        {{ $pieceOfNews->body }}
    </td>
    <td>
        <form method="post" action="{{ route('admin.news.update', ['news' => $pieceOfNews]) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" value="{{$pieceOfNews->published ? 0 : 1}}" name="published">
            <button type="submit" class="btn btn-sm {{$pieceOfNews->published ? "btn-warning" : "btn-success"}}">{{$pieceOfNews->published ? "Снять с публикации" : "Опубликовать"}}</button>
        </form>
    </td>
    <td>
        <a href="{{ route('admin.news.edit', ['news' => $pieceOfNews]) }}" class="btn btn-sm btn-primary">Изменить</a>
        <form method="POST" action="{{route('admin.news.destroy', ['news' => $pieceOfNews])}}" class="mt-1">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
        </form>
    </td>

</tr>
