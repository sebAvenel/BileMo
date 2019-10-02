<?php


namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/api")
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @Route("/register", methods={"POST"})
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        if (isset($values->clientname, $values->password)){
            $client = new Client();
            $client
                ->setName($values->clientname)
                ->setPassword($passwordEncoder->encodePassword($client, $values->password))
                ->setRoles($client->getRoles());
            $entityManager->persist($client);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }

        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés clientname et password'
        ];

        return new JsonResponse($data, 500);
    }

    /**
     * @return JsonResponse
     * @Route("/login", methods={"POST"})
     */
    public function login()
    {
        $client = $this->getUser();
        return $this->json([
            'username' => $client->getUsername(),
            'roles' => $client->getRoles()
        ]);
    }
}