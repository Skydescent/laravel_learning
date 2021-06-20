<?php


namespace App\Service;


use App\Commentable;
use App\Repositories\CommentableInerface;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CommentsService implements CommentableInerface
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