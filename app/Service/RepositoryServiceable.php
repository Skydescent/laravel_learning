<?php


namespace App\Service;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface RepositoryServiceable
{
    public function find(string $identifier);

    public function store(FormRequest|Request $request);

    public function update(FormRequest|Request $request,string $identifier, Authenticatable|User|null $user = null);

    public function destroy(string $identifier, Authenticatable|User|null $user = null);
}