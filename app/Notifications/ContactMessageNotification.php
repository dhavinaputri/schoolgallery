<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification
{
    use Queueable;

    protected $contactData;

    /**
     * Create a new notification instance.
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the notification's delivery channels.
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
            ->subject('Pesan Kontak Baru - ' . $this->contactData['subject'])
            ->greeting('Pesan Kontak Baru dari Website')
            ->line('Anda menerima pesan baru dari form kontak di website School Gallery.')
            ->line('')
            ->line('**Detail Pengirim:**')
            ->line('Nama: ' . $this->contactData['name'])
            ->line('Email: ' . $this->contactData['email'])
            ->line('IP Address: ' . $this->contactData['ip_address'])
            ->line('')
            ->line('**Subjek:**')
            ->line($this->contactData['subject'])
            ->line('')
            ->line('**Pesan:**')
            ->line($this->contactData['message'])
            ->line('')
            ->line('Waktu: ' . $this->contactData['created_at']->format('d M Y, H:i:s'))
            ->line('')
            ->action('Balas Email', 'mailto:' . $this->contactData['email'])
            ->line('Silakan balas pesan ini dengan mengklik tombol di atas.')
            ->salutation('Salam,  
Sistem School Gallery');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->contactData['name'],
            'email' => $this->contactData['email'],
            'subject' => $this->contactData['subject'],
            'message' => $this->contactData['message'],
        ];
    }
}
