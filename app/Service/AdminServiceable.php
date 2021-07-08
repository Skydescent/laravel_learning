<?php

namespace App\Service;

use App\Models\User;

interface AdminServiceable extends Serviceable
{
    public function adminIndex(User $user);
}