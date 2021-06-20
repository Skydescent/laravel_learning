<?php

namespace App;

interface Stepable
{
    public function steps();

    public function addStep(array $attributes);
}