<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Product {
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private ?int $id = null;

  #[ORM\Column(length: 150)]
  #[Assert\NotBlank]
  private string $name;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $description = null;

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  #[Assert\Positive]
  private string $price;

  #[ORM\Column(type: 'integer')]
  #[Assert\PositiveOrZero]
  private int $stock;

  public function getId(): ?int { return $this->id; }

  public function getName(): string { return $this->name; }
  public function setName(string $name): void { $this->name = $name; }

  public function getDescription(): ?string { return $this->description; }
  public function setDescription(?string $d): void { $this->description = $d; }

  public function getPrice(): string { return $this->price; }
  public function setPrice(float|string $p): void { $this->price = (string)$p; }

  public function getStock(): int { return $this->stock; }
  public function setStock(int $s): void { $this->stock = $s; }
}