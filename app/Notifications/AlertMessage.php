<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertMessage extends Notification
{
    use Queueable;

    private User $user;
    private ?string $message;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param mixed|null $message
     */
    public function __construct(User $user, $message = null)
    {
        //
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage;
        if (! $this->message) {
            $mailMessage = $mailMessage->line('L\'utilisateur ' . $this->user->name . ' vient d\'etre cree')
                ->line('Et va bientot etre supprimer!');
        } else if (is_array($this->message)) {
            foreach ($this->message as $message) {
                $mailMessage = $mailMessage->line($message);
            }
        } else if (is_string($this->message)) {
            $mailMessage = $mailMessage->line($this->message)
                ->line('Thanks you for using our app');
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
