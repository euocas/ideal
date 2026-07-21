<?php

namespace App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Exception;

class Mailer
{
    /**
     * Envia o e-mail com o código de verificação para recuperação de senha.
     */
    public static function enviarCodigoRecuperacao(string $destinatario, string $codigo): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) ($_ENV['MAIL_PORT'] ?? 587);
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? $mail->Username,
                $_ENV['MAIL_FROM_NAME'] ?? 'Ideal - Empreiteira'
            );
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = 'Seu código de verificação - Ideal';
            $mail->Body    = self::montarCorpoEmail($codigo);
            $mail->AltBody = "Seu código de verificação é: {$codigo}. Ele expira em 10 minutos.";

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log('Erro ao enviar e-mail de recuperação: ' . $mail->ErrorInfo);
            return false;
        }
    }

    private static function montarCorpoEmail(string $codigo): string
    {
        return "
            <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto;'>
                <h2 style='color:#f97316;'>Recuperação de senha</h2>
                <p>Use o código abaixo para redefinir sua senha no sistema <strong>Ideal</strong>:</p>
                <p style='font-size: 32px; font-weight: bold; letter-spacing: 8px; text-align: center; margin: 24px 0;'>
                    {$codigo}
                </p>
                <p>Esse código é válido por <strong>10 minutos</strong>.</p>
                <p style='color:#888; font-size: 13px;'>Se você não solicitou essa alteração, ignore este e-mail.</p>
            </div>
        ";
    }
}