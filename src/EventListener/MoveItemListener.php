<?php

namespace MartenaSoft\AdminBundle\EventListener;

use MartenaSoft\CommonLibrary\Event\MoveItemEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class MoveItemListener
{
    #[AsEventListener]
    public function onMovePage(MoveItemEvent $event): void
    {
        if ($event->isUp()) {
            $event->getAdminManagerMove()->up($event->getActiveSiteDto(), $event->getItem());
        } else {
            $event->getAdminManagerMove()->down($event->getActiveSiteDto(), $event->getItem());
        }
    }
}
