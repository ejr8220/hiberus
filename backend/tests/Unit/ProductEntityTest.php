<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductEntityTest extends TestCase {
    public function testSetAndGetName() {
        $product = new Product();
        $product->setName('Test');
        $this->assertEquals('Test', $product->getName());
    }

    public function testSetAndGetPrice() {
        $product = new Product();
        $product->setPrice(9.99);
        $this->assertEquals(9.99, $product->getPrice());
    }
}
