<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;

class OrderServiceTest extends TestCase {
  public function testCheckoutOnlyPending(): void {
    $em = $this->createMock(EntityManagerInterface::class);
    $svc = new OrderService($em);

    $order = new \App\Entity\Order();
    $order->setCustomerId('c-1');
    $order->setStatus('PAID');

    $this->expectException(\RuntimeException::class);
    $svc->checkout($order);
  }
}