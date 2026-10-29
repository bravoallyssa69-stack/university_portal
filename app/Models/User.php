<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'course',  // <-- Bagong dagdag para ma-save ang course
        'block',   // <-- Bagong dagdag para ma-save ang block
        'password',
        'role',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification with U-Bi Portal official branding.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new class($token) extends ResetPasswordNotification {
            public function toMail($notifiable)
            {
                return (new MailMessage)
                    ->subject('University of Binalonan - Reset Password Notification')
                    ->line('You are receiving this email because we received a password reset request for your U-Bi Student Portal account.')
                    ->action('Reset Password', url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
                    ->line('If you did not request a password reset, no further action is required.');
            }
        });
    }
}