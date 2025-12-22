<?php
namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class OrderService {
  public function __construct(private EntityManagerInterface $em) {}

  public function createOrder(string $customerId, array $items): Order {
    $order = new Order();
    $order->setCustomerId($customerId);
    $total = 0.0;

    foreach ($items as $i) {
      $product = $this->em->getRepository(Product::class)->find($i['productId']);
      if (!$product) throw new \InvalidArgumentException('Product not found');
      if ($product->getStock() < $i['quantity']) throw new \InvalidArgumentException('Insufficient stock');

      $lineTotal = (float)$product->getPrice() * (int)$i['quantity'];
      $total += $lineTotal;

      $oi = new OrderItem();
      $oi->setProduct($product);
      $oi->setQuantity((int)$i['quantity']);
      $oi->setUnitPrice($product->getPrice());
      $order->addItem($oi);

      $product->setStock($product->getStock() - (int)$i['quantity']);
      $this->em->persist($product);
    }
    $order->setStatus('PENDING');
    $order->setTotal(number_format($total, 2, '.', ''));
    $this->em->persist($order);
    $this->em->flush();

    return $order;
  }

  public function checkout(Order $order): Order {
    if ($order->getStatus() !== 'PENDING') throw new \RuntimeException('Order not pending');
    // Simulación de pago: siempre exitoso
    $order->setStatus('PAID');
    $this->em->persist($order);
    $this->em->flush();
    return $order;
  }
}