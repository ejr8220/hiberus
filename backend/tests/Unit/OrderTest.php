<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Order;

class OrderTest extends TestCase {
    public function testSetAndGetCustomerId() {
        $order = new Order();
        $order->setCustomerId('c-123');
        $this->assertEquals('c-123', $order->getCustomerId());
    }

    public function testSetAndGetTotal() {
        $order = new Order();
        $order->setTotal(100.50);
        $this->assertEquals(100.50, $order->getTotal());
    }
}
