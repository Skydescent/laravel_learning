<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{$comment->author()->name}}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{$comment['created_at']}}</h6>
        <p class="card-text">{{$comment->body}}</p>
    </div>
</div>
