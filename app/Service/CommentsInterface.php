<?php

namespace App\Service;

use App\Commentable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface CommentsInterface
{
    public function storeComment(FormRequest|Request $request, Commentable $model = null);
}