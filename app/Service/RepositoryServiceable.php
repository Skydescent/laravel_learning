<?php


namespace App\Service;


use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Http\Request;

interface RepositoryServiceable
{
    public function store(ValidatesWhenResolved|Request $request);

    public function update(ValidatesWhenResolved|Request $request, $model);

    public function destroy($model);
}