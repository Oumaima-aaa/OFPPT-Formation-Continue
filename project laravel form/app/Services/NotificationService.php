<?php

namespace App\Services;

use App\Models\Action;
use App\Models\Entreprise;
use App\Models\Intervenant;
use App\Models\Theme;
use App\Models\User;
use App\Notifications\ActionIntervenantNotification;
use App\Notifications\NouvelleOffreFormationNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyNewTrainingOffer(Theme $theme): void
    {
        $users = User::role(User::ROLE_COMPANY)
            ->whereHas('entreprise', fn ($q) => $q->where('status', Entreprise::STATUS_ACTIF))
            ->get();

        Notification::send($users, new NouvelleOffreFormationNotification($theme));
    }

    public function notifyIntervenantsForAction(Action $action): void
    {
        $action->load('theme.domaine');

        $domaineName = $action->theme->domaine->nom_domaine;
        $themeName = $action->theme->intitule_theme;

        $intervenants = Intervenant::query()
            ->where(function ($query) use ($domaineName, $themeName) {
                $query->whereHas('competences', function ($q) use ($domaineName, $themeName) {
                    $q->where('nom', 'like', "%{$domaineName}%")
                        ->orWhere('nom', 'like', "%{$themeName}%");
                })->orWhereHas('certifications', function ($q) use ($domaineName) {
                    $q->where('domaine', 'like', "%{$domaineName}%");
                });
            })
            ->get();

        foreach ($intervenants as $intervenant) {
            $intervenant->notify(new ActionIntervenantNotification($action));

            if ($intervenant->user) {
                $intervenant->user->notify(new ActionIntervenantNotification($action));
            }
        }
    }
}
