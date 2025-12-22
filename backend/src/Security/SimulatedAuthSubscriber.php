<?php
namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SimulatedAuthSubscriber implements EventSubscriberInterface {
  public static function getSubscribedEvents(): array {
    return [KernelEvents::REQUEST => ['onRequest', 255]];
  }

  public function onRequest(RequestEvent $event): void {
    $req = $event->getRequest();
    $role = $req->headers->get('X-Role');
    $customerId = $req->headers->get('X-Customer-Id');

    if ($role) {
      $req->attributes->set('role', strtoupper($role));
    }
    if ($customerId) {
      $req->attributes->set('customerId', $customerId);
    }
  }
}