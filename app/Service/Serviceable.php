<?php


namespace App\Service;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface Serviceable extends Indexable
{
    public function find(string|array $identifier);

    public function store(FormRequest|Request $request);

    public function update(FormRequest|Request $request,string $identifier, ?User $user = null);

    public function destroy(string $identifier, ?User $user = null);
}