<?php

namespace App\EventSubscriber;

use Twig\Environment;
use App\Repository\UserSettingRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $userSettingRepository;
    private $user;


    public function __construct(Environment $twig, UserSettingRepository $userSettingRepository, Security $security)
    {
        $this->twig = $twig;
        $this->userSettingRepository = $userSettingRepository;
        $this->user = $security->getUser();
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if ($this->user) {

            $setting = $this->userSettingRepository->findOneByUser($this->user);

            $this->twig->addGlobal('setting', $setting);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
