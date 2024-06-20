<?php

namespace Apb\UserBundle\Service;

use Apb\MailerBundle\Event\SendMailEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class MailerService
{
    public function __construct(
        private ParameterBagInterface    $bag,
        private EventDispatcherInterface $dispatcher,
    )
    {}

    public function send(string $email, array $context = [], ?string $template = null): void
    {
        if (!$this->bag->get('user_bundle.configuration.mailer')) {
            return;
        }

        if (class_exists('\Apb\MailerBundle\Event\SendMailEvent')) {
            $this->dispatcher->dispatch(new SendMailEvent($email, $context, $template));
        }
    }
}