<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
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
     * @Route("/product/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne le détail d'un produit",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"show"}))
     *     )
     * )
     * @Security(name="Bearer")
     * @return Response
     */
    public function show(Product $product, Request $request)
    {
        $data = $this->serializer->serialize($product, 'json', SerializationContext::create()->setGroups(array('show')));
        $response = new Response($data, 200, [
            'Content-type' =>'application/json'
        ]);
        $this->httpCaching($response, 60, $request);

        return $response;
    }

    /**
     * @Route("/products", methods={"GET"})
     * @param Request $request
     * @SWG\Response(
     *     response=200,
     *     description="Retourne la liste des produits",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class, groups={"list"}))
     *     )
     * )
     * @Security(name="Bearer")
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        // Use pagerfanta
        $datas = $this->repository->findAll();
        $adapter = new ArrayAdapter($datas);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        // Use repository
        //$products = $this->repository->findProducts($page, 5);

        $data = $this->serializer->serialize($pagerfanta->getCurrentPageResults(), 'json', SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
        $this->httpCaching($response, 60, $request);

        return $response;
    }
}
