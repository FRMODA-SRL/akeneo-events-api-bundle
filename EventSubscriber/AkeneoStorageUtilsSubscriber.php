<?php

declare( strict_types=1);

namespace Frmoda\Bundle\EventsApiBundle\EventSubscriber;

use Akeneo\Tool\Component\StorageUtils\Event\RemoveEvent;
use Akeneo\Tool\Component\StorageUtils\StorageEvents;
use Akeneo\Tool\Component\Versioning\Model\VersionableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Throwable;
use Frmoda\Bundle\EventsApiBundle\Model\CreateEntityEventAdapter;
use Frmoda\Bundle\EventsApiBundle\Model\GenericEventInterface;
use Frmoda\Bundle\EventsApiBundle\Model\EventsHandler;
use Frmoda\Bundle\EventsApiBundle\Model\RemoveEntityEventAdapter;
use Frmoda\Bundle\EventsApiBundle\Model\UpdateEntityEventAdapter;

class AkeneoStorageUtilsSubscriber implements EventSubscriberInterface
{
    /** @var EventsHandler */
    private $handler;

    /** @var array */
    private $entitiesToBeCreated;

    /** @var LoggerInterface */
    private $logger;

    /**
     * AkeneoStorageUtilsSubscriber constructor.
     * @param EventsHandler $handler
     * @param LoggerInterface $logger
     */
    public function __construct(EventsHandler $handler, LoggerInterface $logger)
    {
        $this->handler = $handler;
        $this->logger  = $logger;
        $this->entitiesToBeCreated = [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            StorageEvents::PRE_SAVE => 'preSave',
            StorageEvents::POST_SAVE => 'postSave',
            StorageEvents::POST_REMOVE => 'postRemove'
        ];
    }

    /**
     * @param GenericEvent $genericEvent
     */
    public function preSave(GenericEvent $genericEvent): void
    {
        $entity = $genericEvent->getSubject();
        $entityHash = spl_object_hash($entity);

        if (!isset($this->entitiesToBeCreated[$entityHash])) {
            if ($entity instanceof VersionableInterface && is_null($entity->getId())) {
                $this->entitiesToBeCreated[$entityHash] = true;
            }
        }
    }

    /**
     * @param GenericEvent $genericEvent
     */
    public function postSave(GenericEvent $genericEvent): void
    {
        $entity = $genericEvent->getSubject();
        $entityHash = spl_object_hash($entity);

        if (isset($this->entitiesToBeCreated[$entityHash])) {
            $event = CreateEntityEventAdapter::createFromGenericEvent($genericEvent);
        } else {
            $event = UpdateEntityEventAdapter::createFromGenericEvent($genericEvent);
        }

        $this->handleEvent($event);
    }

    /**
     * @param RemoveEvent $removeEvent
     */
    public function postRemove(RemoveEvent $removeEvent): void
    {
        $this->handleEvent(RemoveEntityEventAdapter::createFromRemoveEvent($removeEvent));
    }

    /**
     * @param GenericEventInterface $event
     */
    private function handleEvent(GenericEventInterface $event): void
    {
        try {
            $this->handler->handle($event);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
