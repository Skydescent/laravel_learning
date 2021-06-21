<?php


namespace App\Service;

use App\Commentable;
use App\Repositories\CommentableInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CommentsService implements CommentableInterface
{
    public function storeComment(FormRequest|Request $request, Commentable $model = null)
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $model->comments()->create($attributes);
    }

}