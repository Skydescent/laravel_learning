@extends('layout.app')

@section('title', 'Статистика портала')

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Статистика
        </h3>

       <ul>
           <li>Общее количество статей: {{$statistics['postsCount']}}</li>
           <li>Общее количество новостей: {{$statistics['newsCount']}}</li>
           <li>ФИО автора, у которого больше всего статей: {{$statistics['maxPostsCountAuthorName']}}</li>
           <li>
               Самая длинная статья:
               <a href="{{route('posts.show', ['post' => $statistics['longestPost']])}}">
                   {{$statistics['longestPost']->title}}
               </a>
                длина: {{$statistics['longestPost']->len_body}}
           </li>
           <li>
               Самая короткая статья:
               <a href="{{route('posts.show', ['post' => $statistics['shortestPost']])}}">
                   {{$statistics['shortestPost']->title}}
               </a>
               длина: {{$statistics['shortestPost']->len_body}}
           </li>
           <li>Средние количество статей у активных пользователей: {{$statistics['activeUsersAvgPosts']}}</li>
           <li>
               Самая непостоянная статья:
               <a href="{{route('posts.show', ['post' => $statistics['mostChangedPost']])}}">
                   {{$statistics['mostChangedPost']->title}}
               </a>
           </li>
           <li>
               Самая обсуждаемая статья:
               <a href="{{route('posts.show', ['post' => $statistics['mostCommentedPost']])}}">
                   {{$statistics['mostCommentedPost']->title}}
               </a>
           </li>
       </ul>

    </div>
@endsection
