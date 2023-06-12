<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailConfirmation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Bonjour ' . $notifiable->firstname . ' ' . $notifiable->lastname . ' ! ')
            ->subject('Confirmation de votre adresse mail')
            ->line('Merci de votre inscription ! Avant de commencer, vous devez confirmer votre adresse mail, afin d\'activer votre compte.')
            ->action('Confirmer mon adresse mail', route('confirmEmail', $notifiable->id))
            ->line('Si vous n\'avez pas créé de compte, aucune autre action n\'est requise.')
            ->salutation('L\'équipe d\'' . config('app.name') . '.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
