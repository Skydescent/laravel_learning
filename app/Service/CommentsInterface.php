<?php

namespace App\Service;

use App\Models\Commentable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface CommentsInterface
{
    public function storeComment(array $attributes, Commentable $model = null);
}