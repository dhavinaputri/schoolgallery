<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(route('guest.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password - School Gallery')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->line('Silakan klik tombol di bawah ini untuk mereset password Anda.')
            ->action('Reset Password', $resetUrl)
            ->line('Link reset password ini akan kadaluarsa dalam :count menit.', [
                'count' => config('auth.passwords.users.expire')
            ])
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini. Akun Anda tetap aman.')
            ->salutation('Salam,  
Tim School Gallery');
    }
}
