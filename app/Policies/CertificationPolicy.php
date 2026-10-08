<?php

namespace App\Policies;

class CertificationPolicy extends IntervenantRelatedPolicy
{
    protected function permission(): string
    {
        return 'certifications.manage';
    }
}
