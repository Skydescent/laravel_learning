 <div class="col-md-8 blog-main">
     @forelse($comments as $comment)
         @include('comments.item')
     @empty
         <p>Нет комментариев</p>
     @endforelse
 </div>
