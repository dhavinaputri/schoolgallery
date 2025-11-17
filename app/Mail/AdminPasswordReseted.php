<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordReseted extends Mailable
{
    use Queueable, SerializesModels;

    public Admin $targetAdmin;
    public string $temporaryPassword;

    public function __construct(Admin $targetAdmin, string $temporaryPassword)
    {
        $this->targetAdmin = $targetAdmin;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function build()
    {
        return $this->subject('Password Admin Anda Telah Direset')
            ->view('admin.emails.admin-password-reseted')
            ->with([
                'name' => $this->targetAdmin->name,
                'email' => $this->targetAdmin->email,
                'password' => $this->temporaryPassword,
                'loginUrl' => route('admin.login'),
            ]);
    }
}


