<?php
namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed')]
class SeedCommand extends Command {
  public function __construct(private EntityManagerInterface $em) { parent::__construct(); }
  protected function execute(InputInterface $input, OutputInterface $output): int {
    foreach ([
      ['name'=>'Laptop','price'=>'999.00','stock'=>10],
      ['name'=>'Mouse','price'=>'25.50','stock'=>200],
      ['name'=>'Teclado','price'=>'45.00','stock'=>150],
    ] as $row) {
      $p = new Product();
      $p->setName($row['name']); $p->setPrice($row['price']); $p->setStock($row['stock']);
      $this->em->persist($p);
    }
    $this->em->flush();
    $output->writeln('Seeded');
    return Command::SUCCESS;
  }
}
