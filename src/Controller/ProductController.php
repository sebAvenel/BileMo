<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ProductController constructor.
     * @param ProductRepository $repository
     * @param SerializerInterface $serializer
     */
    public function __construct(ProductRepository $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return Response
     * @Route("/product/{id}", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Product $product, Request $request)
    {
        $data = $this->serializer->serialize($product, 'json', ['groups' => ['show']]);
        $response = new Response($data, 200, [
            'Content-type' =>'application/json'
        ]);
        $this->makeCache($response, 60, $request);

        return $response;
    }

    /**
     * @Route("/products", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }
        $products = $this->repository->findProducts($page, $_SERVER['LIMIT']);
        $data = $this->serializer->serialize($products, 'json', ['groups' => ['list']]);
        $response = new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
        $this->makeCache($response, 60, $request);

        return $response;
    }
}
