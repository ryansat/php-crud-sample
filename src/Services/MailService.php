<?php

namespace App\Services;

class MailService {
    private $fromEmail;
    private $fromName;

    public function __construct() {
        $this->fromEmail = $_ENV['MAIL_FROM_ADDRESS'];
        $this->fromName = $_ENV['MAIL_FROM_NAME'];
    }

    public function sendActivationEmail($email, $username, $activationCode) {
        try {
            $activationLink = $_ENV['APP_URL'] . '/activate.php?code=' . $activationCode;
            $subject = 'Account Activation - PHP CRUD Sample';
            $message = $this->getActivationEmailTemplate($username, $activationLink);
            
            $headers = [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . $this->fromName . ' <' . $this->fromEmail . '>'
            ];

            return mail($email, $subject, $message, implode("\r\n", $headers));
        } catch (\Exception $e) {
            throw new \Exception("Error sending activation email: " . $e->getMessage());
        }
    }

    private function getActivationEmailTemplate($username, $activationLink) {
        return <<<HTML
        <html>
        <body>
            <h2>Welcome to PHP CRUD Sample</h2>
            <p>Hello {$username},</p>
            <p>Thank you for registering. Please click the link below to activate your account:</p>
            <p><a href="{$activationLink}">Activate Account</a></p>
            <p>If the link doesn't work, copy and paste this URL into your browser:</p>
            <p>{$activationLink}</p>
            <p>Best regards,<br>PHP CRUD Sample Team</p>
        </body>
        </html>
HTML;
    }
}