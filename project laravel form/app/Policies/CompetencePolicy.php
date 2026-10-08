<?php

namespace App\Policies;

class CompetencePolicy extends IntervenantRelatedPolicy
{
    protected function permission(): string
    {
        return 'competences.manage';
    }
}
