<?php

namespace App\Notifications;

use App\Models\Theme;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleOffreFormationNotification extends Notification
{
    use Queueable;

    public function __construct(public Theme $theme) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->theme->loadMissing('domaine');

        return (new MailMessage)
            ->subject('Nouvelle offre de formation disponible')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Une nouvelle offre de formation est disponible sur la plateforme OFPPT.')
            ->line('Thème : '.$this->theme->intitule_theme)
            ->line('Domaine : '.$this->theme->domaine->nom_domaine)
            ->line('Durée : '.$this->theme->duree_formation.' jours')
            ->action('Consulter le catalogue', route('catalog.themes.show', $this->theme));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'nouvelle_offre',
            'theme_id' => $this->theme->id,
            'message' => 'Nouvelle offre : '.$this->theme->intitule_theme,
            'url' => route('catalog.themes.show', $this->theme),
        ];
    }
}
