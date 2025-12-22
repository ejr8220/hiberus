<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Order;
use App\Entity\OrderItem;

class OrderEntityTest extends TestCase {
    public function testAddItemIncreasesCount() {
        $order = new Order();
        $item = new OrderItem();
        $order->addItem($item);
        $this->assertCount(1, $order->getItems());
    }

    public function testSetAndGetStatus() {
        $order = new Order();
        $order->setStatus('PENDING');
        $this->assertEquals('PENDING', $order->getStatus());
    }
}
