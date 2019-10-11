<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param ValidatorInterface $validator
     * @IsGranted("ROLE_USER")
     * @return Response
     * @throws AnnotationException
     * @throws ErrorException
     * @Route("/user/{id}", methods={"GET"})
     */
    public function show(User $user, ValidatorInterface $validator)
    {
        if ($user->getClient() == $this->getUser()) {
            $serializer = $this->userSerializer();
            $data = $serializer->serialize($user, 'json', [
                'groups' => ['show']
            ]);

            return new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }

        throw new ErrorException("Vous ne pouvez pas accéder à cet utilisateur");
    }

    /**
     * @Route("/users", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @return Response
     * @throws AnnotationException
     */
    public function index()
    {
        $users = $this->repository->findBy(['client' => $this->getUser()]);
        $serializer = $this->userSerializer();
        $data = $serializer->serialize($users, 'json', [
            'groups' => ['list']
        ]);

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/user/add", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @IsGranted("ROLE_CLIENT")
     * @return JsonResponse
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

    /**
     * @Route("/verify")
     */
    public function verifyGetUser()
    {
        dump($this->getUser());
    }
}
