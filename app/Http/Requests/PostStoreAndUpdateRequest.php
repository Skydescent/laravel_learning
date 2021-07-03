<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class PostStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->user()->isAdmin() && !$this->slug) {
            return ['published'=>''];
        }
        $slugRule = 'required|regex:/^[a-z0-9-_]+$/i|unique:posts';

        if ($this->route()->hasParameter('post')) {

            $slugRule .= ',slug,' . $this->attributes->get('post')->id;
        }

        return [
            'slug' => $slugRule,
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => '',
            'tags' => ''
        ];

    }
}
