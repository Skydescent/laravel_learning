 <div class="col-md-8 blog-main">
     @forelse($post->comments as $comment)
         @include('comments.item')
     @empty
         <hr>
         <p>Здесь пока нет комментариев...</p>
     @endforelse
 </div>
