<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface Serviceable extends Indexable
{
    public function find(string|array $identifier);

    public function store(array $attributes);

    public function update(array $attributes,string $identifier, ?User $user = null);

    public function destroy(string $identifier, ?User $user = null);

    public function getCurrentModel() : Model;
}