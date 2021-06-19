<?php


namespace App\Service;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface RepositoryServiceable
{
    public function store(FormRequest|Request $request);

    public function update(FormRequest|Request $request, Model $model);

    public function destroy(Model $model);
}