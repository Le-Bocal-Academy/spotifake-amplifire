<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Bonjour ' . $notifiable->firstname . ' ' . $notifiable->lastname . ' ! ')
            ->subject('Votre lien de réinitialisation du mot de passe')
            ->line('Vous recevez ce courriel parce que nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
            // TODO demander à l'équipe front quelle URL 
            ->action('Réinitialiser le mot de passe', url(config('app.url') . ":8080/reset-password?" . $this->token))
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune autre action n\'est requise.')
            ->salutation('L\'équipe d\'' . config('app.name') . '.');
    }
}
