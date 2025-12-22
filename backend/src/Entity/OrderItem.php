<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class OrderItem {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
  private Order $order;

  #[ORM\ManyToOne(targetEntity: Product::class)]
  private Product $product;

  #[ORM\Column(type: 'integer')]
  private int $quantity;

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  private string $unitPrice;

  public function getId(): ?int { return $this->id; }
  public function getOrder(): Order { return $this->order; }
  public function setOrder(Order $o): void { $this->order = $o; }
  public function getProduct(): Product { return $this->product; }
  public function setProduct(Product $p): void { $this->product = $p; }
  public function getQuantity(): int { return $this->quantity; }
  public function setQuantity(int $q): void { $this->quantity = $q; }
  public function getUnitPrice(): string { return $this->unitPrice; }
  public function setUnitPrice(string $u): void { $this->unitPrice = $u; }
}