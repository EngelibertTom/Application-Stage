<?php

namespace App\Service;

use App\Entity\Species;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    private function sendEmail(Address $to, string $subject, $template, array $context): bool
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address('contact@bonsailapartducolibri.org', 'APP BONSAÏ'))
                ->to($to)
                ->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($context);

            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface $exception) {
            $this->logger->warning('[MAIL] ' . $exception->getMessage());
            return false;
        }
    }

    public function sendSpeciesValidation(User $user, Species $species): bool
    {
        if ($email = $user->getEmail()) {

            return $this->sendEmail(
                new Address($email),
                'Validation d\'une espèce',
                'admin/email/species_validation.html.twig',
                [
                    'user'      => $user,
                    'species'   => $species
                ]
            );
        }

        return false;
    }
}
