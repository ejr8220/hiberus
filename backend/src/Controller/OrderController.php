<?php
namespace App\Controller;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private OrderService $orders,
        private EntityManagerInterface $em
    ) {}

    #[Route('/orders', methods: ['POST', 'OPTIONS'])]
    public function create(Request $req): JsonResponse
    {
        $roleAttr    = $req->attributes->get('role');
        $roleHeader  = $req->headers->get('X-Role');
        $role        = strtoupper((string) ($roleAttr ?? $roleHeader ?? ''));

        if ($role !== 'CLIENTE') {
            return new JsonResponse(['error' => 'FORBIDDEN'], 403);
        }

        // Obtener customerId de atributos o cabecera
        $customerId = (string) ($req->attributes->get('customerId') ?? $req->headers->get('X-Customer-Id') ?? '');

        $payload = json_decode($req->getContent(), true) ?? [];
        $items   = $payload['items'] ?? [];

        try {
            $order = $this->orders->createOrder($customerId, $items);
            return new JsonResponse([
                'id'     => $order->getId(),
                'total'  => $order->getTotal(),
                'status' => $order->getStatus()
            ], 201);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error'   => 'ORDER_FAILED',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/orders/{id}', methods: ['GET', 'OPTIONS'])]
    public function detail(Request $req, int $id): JsonResponse
    {

        $roleAttr   = $req->attributes->get('role');
        $roleHeader = $req->headers->get('X-Role');
        $role       = strtoupper((string) ($roleAttr ?? $roleHeader ?? ''));

        if ($role !== 'CLIENTE') {
            return new JsonResponse(['error' => 'FORBIDDEN'], 403);
        }

        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'NOT_FOUND'], 404);
        }

        $items = [];
        foreach ($order->getItems() as $it) {
            $product = $it->getProduct();
            if ($product) {
                $items[] = [
                    'productId' => $product->getId(),
                    'name'      => $product->getName(),
                    'quantity'  => $it->getQuantity(),
                    'unitPrice' => $it->getUnitPrice()
                ];
            }
        }

        return new JsonResponse([
            'id'     => $order->getId(),
            'status' => $order->getStatus(),
            'total'  => $order->getTotal(),
            'items'  => $items
        ], 200);
    }
        
    #[Route('/orders/{id}/checkout', methods: ['POST', 'OPTIONS'])]
    public function checkout(int $id, Request $req ): JsonResponse
    {
        $roleAttr   = $req->attributes->get('role');
        $roleHeader = $req->headers->get('X-Role');
        $role       = strtoupper((string) ($roleAttr ?? $roleHeader ?? ''));

        if ($role !== 'CLIENTE') {
            return new JsonResponse(['error' => 'FORBIDDEN'], 403);
        }

        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'NOT_FOUND'], 404);
        }


        try {
            $order = $this->orders->checkout($order);
            return new JsonResponse([
                'id'     => $order->getId(),
                'status' => "PAID",
                'total'  => $order->getTotal()
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error'   => 'CHECKOUT_FAILED',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}