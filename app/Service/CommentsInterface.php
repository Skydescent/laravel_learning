<?php

namespace App\Service;

use App\Models\Commentable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface CommentsInterface
{
    public function storeComment(FormRequest|Request $request, Commentable $model = null);
}