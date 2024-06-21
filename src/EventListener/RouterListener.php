<?php

namespace Apb\UserBundle\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class RouterListener
{
    public function __construct(
        private ParameterBagInterface $bag,
    )
    {}

    #[AsEventListener(event: ControllerEvent::class)]
    public function onRouterEvent(ControllerEvent $event): void
    {
        $attributes = $event->getControllerReflector()->getAttributes();

        foreach ($attributes as $attribute) {
            if (isset($attribute->getArguments()['name']) && str_contains($attribute->getArguments()['name'], 'apb_user_bundle')) {
                $check = $attribute->getArguments()['name'];
            }
        }

        if (isset($check) && !in_array($check, $this->bag->get('user_bundle.configuration')['allowed_controllers'])) {
            throw new NotFoundHttpException('This controller is not available. Change the configuration of apb/user_bundle.');
        }
    }
}