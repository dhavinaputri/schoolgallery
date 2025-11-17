<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends ResetPasswordNotification
{
    use Queueable;

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password Admin')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun admin Anda.')
            ->action('Reset Password', url(route('admin.password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('Link reset password ini akan kadaluarsa dalam :count menit.', [
                'count' => config('auth.passwords.admins.expire')
            ])
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.');
    }
}
