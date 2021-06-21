<?php

namespace App\Repositories;

use App\Commentable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface CommentableInterface
{
    public function storeComment(FormRequest|Request $request, Commentable $model = null);
}