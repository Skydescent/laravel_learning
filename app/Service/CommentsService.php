<?php


namespace App\Service;


use App\Commentable;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CommentsService implements RepositoryServiceable
{
    public function store(FormRequest|Request $request, Commentable $model = null)
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $model->comments()->create($attributes);
    }

    /**
     * @param ValidatesWhenResolved $request
     * @param $model
     * @return mixed
     */
    public function update(FormRequest|Request $request, $model)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        // TODO: Implement destroy() method.
    }


}