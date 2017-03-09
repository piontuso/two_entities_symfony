<?php

namespace Piontuso\TwoEntitiesBundle\Service;

/**
 * Class UserMailer
 * @package Piontuso\TwoEntitiesBundle\Service
 */
class UserMailer
{
  private $mailer;

  /**
   * @param $mailer
   */
  public function __construct($mailer)
  {
    $this->mailer = $mailer;
  }

  /**
   * Send email.
   */
  public function sendmail()
  {
    global $kernel;
    $container = $kernel->getContainer();
    $mail_from = $container->getParameter('mail_from');
    $mail_to = $container->getParameter('mail_to');

    if (!empty($mail_from) && !empty($mail_to)) {
      $message = \Swift_Message::newInstance()
        ->setSubject('New comment')
        ->setFrom($mail_from)
        ->setTo($mail_to)
        ->setBody('Your comment was added with successfully !');

      $this->mailer->send($message);
    }
  }
}
