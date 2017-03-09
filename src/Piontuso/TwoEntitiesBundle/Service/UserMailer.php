<?php

namespace Piontuso\TwoEntitiesBundle\Service;

/**
 * Class UserMailer
 * @package Piontuso\TwoEntitiesBundle\Service
 */
class UserMailer
{
    /**
     * @var object
     */
    private $mailer;

    /**
     * Construct.
     *
     * @param object $mailer
     *   Injected mailer service.
     */
    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email.
     *
     * @param string $subject
     *   Mail subject.
     * @param string $body
     *   Mail body.
     *
     * @return bool
     */
    public function sendmail($subject, $body)
    {
        global $kernel;
        if (empty($subject) || empty($body)) {
          return false;
        }
        $container = $kernel->getContainer();
        $mail_from = $container->getParameter('mail_from');
        $mail_to = $container->getParameter('mail_to');

        if (!empty($mail_from) && !empty($mail_to)) {
          $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($mail_from)
            ->setTo($mail_to)
            ->setBody($body);

          $this->mailer->send($message);
        }
    }
}
