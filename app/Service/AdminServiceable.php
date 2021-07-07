<?php

namespace App\Service;

use App\User;

interface AdminServiceable extends Serviceable
{
    public function adminIndex(User $user);
}