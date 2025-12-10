<?php

namespace Core\Mail;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
        $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
        $this->mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 587;
        
        $this->mailer->setFrom(
            $_ENV['MAIL_FROM_ADDRESS'] ?? $_ENV['MAIL_USERNAME'],
            $_ENV['MAIL_FROM_NAME'] ?? 'My App'
        );
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (\Exception $e) {
           
            return false;
        }
    }
}
