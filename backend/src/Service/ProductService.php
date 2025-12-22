<?php
namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator
    ) {}

    /**
     * Listado paginado con filtros y orden.
     *
     * @return array [items, total]
     */
    public function list(
        string $search = '',
        int $page = 1,
        int $limit = 20,
        string $sort = 'id',
        string $direction = 'ASC'
    ): array {
        $repo = $this->em->getRepository(Product::class);
        $qb = $repo->createQueryBuilder('p');

        if ($search !== '') {
            $qb->andWhere('LOWER(p.name) LIKE :s OR LOWER(p.description) LIKE :s')
               ->setParameter('s', '%'.strtolower($search).'%');
        }

        // Lista blanca de campos ordenables
        $sortableFields = ['id','name','description','price','stock'];
        if (!in_array($sort, $sortableFields, true)) {
            $sort = 'id';
        }

        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        // Query principal con paginación
        $qb->orderBy('p.'.$sort, $direction)
           ->setMaxResults($limit)
           ->setFirstResult($limit * ($page - 1));

        $items = array_map(fn(Product $p) => [
            'id'          => $p->getId(),
            'name'        => $p->getName(),
            'description' => $p->getDescription(),
            'price'       => $p->getPrice(),
            'stock'       => $p->getStock(),
        ], $qb->getQuery()->getResult());

        // Query para contar total sin paginación
        $countQb = $repo->createQueryBuilder('p')
            ->select('COUNT(p.id)');
        if ($search !== '') {
            $countQb->andWhere('LOWER(p.name) LIKE :s OR LOWER(p.description) LIKE :s')
                    ->setParameter('s', '%'.strtolower($search).'%');
        }
        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        return [$items, $total];
    }

    public function create(array $payload): array
    {
        $p = new Product();
        $p->setName($payload['name'] ?? '');
        $p->setDescription($payload['description'] ?? null);
        $p->setPrice((string)($payload['price'] ?? '0.00'));
        $p->setStock((int)($payload['stock'] ?? 0));

        $errors = $this->validator->validate($p);
        if (count($errors) > 0) {
            return ['error' => 'VALIDATION', 'details' => (string)$errors];
        }

        $this->em->persist($p);
        $this->em->flush();

        return ['id' => $p->getId()];
    }
}