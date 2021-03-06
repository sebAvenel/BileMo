<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(UserRepository $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Response
     * @throws ErrorException
     * @IsGranted("ROLE_USER")
     * @Route("/user/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne le détail d'un utilisateur",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"show"}))
     *     )
     * )
     * @Security(name="Bearer")
     */

    public function show(User $user, Request $request)
    {
        if ($user->getClient() == $this->getUser()) {
            $data = $this->serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('show')));
            $response = new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
            //$this->httpCaching($response, 60, $request);

            return $response;
        }

        throw new ErrorException("Vous ne pouvez pas accéder à cet utilisateur");
    }

    /**
     * @Route("/users", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @SWG\Response(
     *     response=200,
     *     description="Retourne la liste des utilisateurs",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"list"}))
     *     )
     * )
     * @Security(name="Bearer")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->query->get('page');
        if(is_null($page) || $page < 1) {
            $page = 1;
        }

        $datas = $this->repository->findBy(['client' => $this->getUser()]);
        $adapter = new ArrayAdapter($datas);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(5);
        $pagerfanta->setCurrentPage($page);

        $data = $this->serializer->serialize($pagerfanta->getCurrentPageResults(), 'json', SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
        //$this->httpCaching($response, 60, $request);

        return $response;

    }

    /**
     * @Route("/user/add", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @IsGranted("ROLE_CLIENT")
     * @SWG\Response(
     *     response=200,
     *     description="Ajouter un utilisateur",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"show"}))
     *     )
     * )
     * @Security(name="Bearer")
     * @return JsonResponse|Response
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setClient($this->getUser());
        $errors = $validator->validate($user);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 400, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->persist($user);
        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'L\'utilisateur a bien été ajouté'
        ];

        return new JsonResponse($data, 201);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @Route("/user/delete/{id}", methods={"DELETE"})
     * @IsGranted("ROLE_CLIENT")
     * @SWG\Response(
     *     response=200,
     *     description="Supprimer un utilisateur",
     *     @SWG\Schema(
     *         type="int",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"show"}))
     *     )
     * )
     * @Security(name="Bearer")
     * @return Response
     * @throws ErrorException
     */
    public function delete(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->getClient() == $this->getUser()){
            $entityManager->remove($user);
            $entityManager->flush();
            $data = [
                'status' => 204,
                'message' => 'L\'utilisateur ' . $user->getFirstName() . ' ' . $user->getLastName() . ' a bien été supprimé'
            ];

            return new JsonResponse($data, 201);
        }

        throw new ErrorException("Vous ne pouvez pas supprimer cet utilisateur");
    }
}
