<?php

declare(strict_types=1);

namespace Infrastructure\Mailer;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Transports;
use Symfony\Component\Mime\Address;

class ResetPasswordMailer
{

    public function __construct(
        private MailerInterface $mailer,
        private string $frontendDomain,
        private string $contactEmail
    ) { 
    }

    public function send(User $user, string $token): void
    {
        $templateMail = (new TemplatedEmail())
            ->to(new Address($user->getEmail(), $user->getName()))
            ->from(new Address($this->contactEmail))
            ->subject('Password forgotten')
            ->htmlTemplate('email/reset-password.html.twig')
            ->context([
                'name' => $user->getName(),
                'link' => sprintf('%s/reset-password/%s', $this->frontendDomain, $token)
            ]);
        
        
        $this->mailer->send($templateMail);
    }
}