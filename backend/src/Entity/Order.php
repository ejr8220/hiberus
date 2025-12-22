<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(length: 100)]
  private string $customerId;

  #[ORM\Column(length: 20)]
  private string $status = 'PENDING'; // PENDING, PAID, FAILED

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  private string $total = '0.00';

  #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist'], orphanRemoval: true)]
  private $items;

  public function __construct() {
    $this->items = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public function getId(): ?int { return $this->id; }
  public function getCustomerId(): string { return $this->customerId; }
  public function setCustomerId(string $c): void { $this->customerId = $c; }
  public function getStatus(): string { return $this->status; }
  public function setStatus(string $s): void { $this->status = $s; }
  public function getTotal(): string { return $this->total; }
  public function setTotal(string $t): void { $this->total = $t; }
  public function getItems() { return $this->items; }
  public function addItem(OrderItem $item): void { $this->items->add($item); $item->setOrder($this); }
}