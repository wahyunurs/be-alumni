<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LokerCreatedNotifications extends Notification
{
    use Queueable;
    private $loker;

    /**
     * Create a new notification instance.
     */
    public function __construct($loker)
    {
        $this->loker = $loker;
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
            ->subject('Loker Baru Menunggu Verifikasi')
            ->greeting('Halo Koordinator!')
            ->line('Seorang alumni telah membuat loker baru.')
            ->action('Verifikasi Sekarang', url('https://sti.dinus.ac.id/Alumni/auth'))
            ->line('Silakan segera lakukan verifikasi.');
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
