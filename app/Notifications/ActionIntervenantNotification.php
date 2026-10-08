<?php

namespace App\Notifications;

use App\Models\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionIntervenantNotification extends Notification
{
    use Queueable;

    public function __construct(public Action $action) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->action->load('theme.domaine', 'etablissement');

        return (new MailMessage)
            ->subject('Nouvelle action de formation correspondant à vos compétences')
            ->greeting('Bonjour,')
            ->line('Une action de formation correspondant à vos compétences a été créée.')
            ->line('Thème : '.$this->action->theme->intitule_theme)
            ->line('Établissement : '.$this->action->etablissement->nom_efp)
            ->line('Période : '.$this->action->date_debut->format('d/m/Y').' - '.$this->action->date_fin->format('d/m/Y'))
            ->action('Voir l\'action', url('/actions/'.$this->action->id));
    }

    public function toArray(object $notifiable): array
    {
        $this->action->loadMissing('theme');

        return [
            'type' => 'action_intervenant',
            'action_id' => $this->action->id,
            'message' => 'Action de formation : '.$this->action->theme->intitule_theme,
        ];
    }
}
