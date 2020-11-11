<?php

namespace App;

class Post extends \App\Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public static function validate(\Illuminate\Http\Request $request, $postId = null)
    {
        if (!is_null($postId)) {
            $slug = 'required|regex:/^[a-z0-9-_]+$/i|unique:posts';
        } else {
            $slug = 'required|regex:/^[a-z0-9-_]+$/i|unique:posts,slug,' . $postId;
        }

        $validationRules = [
            'slug' => $slug,
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => ''
        ];

        return $request->validate($validationRules);
    }
}
