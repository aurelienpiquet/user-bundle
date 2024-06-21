<?php

namespace Apb\UserBundle\Service;

use Apb\MailerBundle\Event\SendMailEvent;
use Apb\MailerBundle\Service\MailServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class MailerService implements MailServiceInterface
{
    public function __construct(
        private ParameterBagInterface    $bag,
        private EventDispatcherInterface $dispatcher,
    )
    {}

    public function send(string $mail, array $context = [], ?string $template = null): bool
    {
        if (!($this->bag->get('user_bundle.configuration'))['mailer']) {
            return false;
        }

        $this->dispatcher->dispatch(new SendMailEvent($mail, $context, $template));

        return true;
    }
}