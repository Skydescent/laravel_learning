<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->user()->isAdmin() && !$this->title) {
            return ['published'=>''];
        }

        return [
            'title' => 'required|between:5,100',
            'body' => 'required',
            'published' => '',
        ];
    }
}
