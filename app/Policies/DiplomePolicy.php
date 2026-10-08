<?php

namespace App\Policies;

class DiplomePolicy extends IntervenantRelatedPolicy
{
    protected function permission(): string
    {
        return 'diplomes.manage';
    }
}
