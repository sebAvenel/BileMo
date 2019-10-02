<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/api")
 */
class ProductController extends Controller
{
    /**
     * @Route("/products/{page<\d+>?1}", methods={"GET"})
     * @param Request $request
     * @param ProductRepository $repository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, ProductRepository $repository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }
        $products = $repository->findProducts($page, $_ENV['LIMIT']);
        $data = $serializer->serialize($products, 'json');

        return new Response($data, 200, [
            'Content-type' => 'application/json'
        ]);
    }
}