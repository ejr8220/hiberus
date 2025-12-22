<?php
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Entity\Product;

class ProductServiceTest extends TestCase {
    public function testListReturnsArray() {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $service = new ProductService($em, $validator);

        $repo = $this->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()->getMock();
        $qb = $this->getMockBuilder(\Doctrine\ORM\QueryBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'andWhere','setParameter','orderBy','setMaxResults','setFirstResult','select','getQuery'
            ])->getMock();
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('orderBy')->willReturnSelf();
        $qb->method('setMaxResults')->willReturnSelf();
        $qb->method('setFirstResult')->willReturnSelf();
        $qb->method('select')->willReturnSelf();

        $queryMock = $this->getMockBuilder(\Doctrine\ORM\Query::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getResult','getSingleScalarResult'])
            ->getMock();
        $queryMock->method('getResult')->willReturn([]);
        $queryMock->method('getSingleScalarResult')->willReturn(0);

        $qb->method('getQuery')->willReturn($queryMock);
        $repo->method('createQueryBuilder')->willReturn($qb);
        $em->method('getRepository')->willReturn($repo);

        $result = $service->list();
        $this->assertIsArray($result);
        $this->assertCount(2, $result); // [items, total]
        $this->assertIsArray($result[0]);
        $this->assertIsInt($result[1]);
    }

    public function testCreateWithInvalidData() {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $service = new ProductService($em, $validator);

        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $violations->method('count')->willReturn(1);
        $validator->method('validate')->willReturn($violations);

        $result = $service->create(['name' => '', 'price' => -1]);
        $this->assertArrayHasKey('error', $result);
    }

    public function testCreateWithValidData() {
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $service = new ProductService($em, $validator);

        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $violations->method('count')->willReturn(0);
        $validator->method('validate')->willReturn($violations);

        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $result = $service->create(['name' => 'Test', 'price' => 10, 'stock' => 1]);
        $this->assertArrayHasKey('id', $result);
    }
}
