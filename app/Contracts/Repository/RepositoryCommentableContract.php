<?php

namespace App\Contracts\Repository;

interface RepositoryCommentableContract
{
    public function addComment(array $attributes, string $commentableIdentifier );
}