<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PostStoreAndUpdateRequest extends FormRequest
{
    private \App\Service\RepositoryServiceable $postsService;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
        \App\Service\RepositoryServiceable|null $postsService = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->postsService = $postsService;
    }

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
        Log::info('PostRequest@rules, post' . $this->route('post') . ' ' . $this->slug . ' ' . $this->id);
        if ($this->route()->hasParameter('post')) {
            $post = $this->postsService->find($this->slug, auth()->user());
            $slugRule .= ',slug,' . $post->id;
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
