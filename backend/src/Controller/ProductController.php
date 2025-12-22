<?php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $service) {}

    #[Route('/products', methods: ['GET'])]
    public function list(Request $req): JsonResponse
    {
        $search = trim((string) $req->query->get('search', ''));
        $page   = max(1, (int) $req->query->get('page', 1));
        $sort   = (string) $req->query->get('sort', 'name'); // default más útil que id
        $limit  = max(1, (int) $req->query->get('limit', 10)); // opcional, por si lo usas

        $sortableFields = ['id', 'name', 'price', 'stock', 'description', 'createdAt'];
        if (!in_array($sort, $sortableFields, true)) {
            $sort = 'name';
        }

        $direction = 'ASC';

        [$items, $total] = $this->service->list($search, $page, $limit, $sort, $direction);

        $pages = (int) ceil($total / $limit);

        return new JsonResponse([
            'data'   => $items,
            'page'   => $page,
            'limit'  => $limit,
            'total'  => $total,
            'pages'  => $pages,
            'search' => $search,
            'sort'   => $sort,
        ]);
    }

    #[Route('/products', methods: ['POST'])]
    public function create(Request $req): JsonResponse
    {
        $roleAttr = $req->attributes->get('role');
        $roleHeader = $req->headers->get('X-Role');
        $role = strtoupper((string) ($roleAttr ?? $roleHeader ?? ''));

        if ($role !== 'ADMIN') {
            return new JsonResponse(['error' => 'FORBIDDEN'], 403);
        }

        $payload = json_decode($req->getContent(), true) ?? [];
        $result = $this->service->create($payload);

        if (isset($result['error'])) {
            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}