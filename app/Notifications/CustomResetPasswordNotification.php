<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 Recuperar acceso - Sistema de Seguridad')
            ->greeting('¡Hola!')
            ->line('Estás recibiendo este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta en el Sistema de Seguridad.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de restablecimiento de contraseña expirará en 60 minutos.')
            ->line('Si no realizaste esta solicitud, no es necesario realizar ninguna acción adicional.')
            ->salutation('Saludos, ' . config('app.name'));
    }
}
