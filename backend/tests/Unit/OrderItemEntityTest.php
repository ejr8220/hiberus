<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\OrderItem;
use App\Entity\Product;

class OrderItemEntityTest extends TestCase {
    public function testSetAndGetQuantity() {
        $item = new OrderItem();
        $item->setQuantity(3);
        $this->assertEquals(3, $item->getQuantity());
    }

    public function testSetAndGetProduct() {
        $item = new OrderItem();
        $product = new Product();
        $item->setProduct($product);
        $this->assertSame($product, $item->getProduct());
    }
}
