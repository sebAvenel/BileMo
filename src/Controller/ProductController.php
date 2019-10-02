<?php

namespace App\Controller;

use App\Repository\ProductRepository;
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
     * @Route("/products", methods={"GET"})
     * @param ProductRepository $repository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(ProductRepository $repository, SerializerInterface $serializer)
    {
        $products = $repository->findAll();
        $data = $serializer->serialize($products, 'json');

        return new Response($data, 200, [
            'Content-type' => 'application/json'
        ]);
    }
}