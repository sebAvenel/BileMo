<?php


namespace App\Controller;


use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Controller extends AbstractController
{
    /**
     * Serializer that handles exceptions of type 'circular reference'.
     * @return Serializer
     * @throws AnnotationException
     */
    public function userSerializer() : Serializer
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null , $defaultContext);
        $userSerializer = new Serializer([$normalizer], [$encoder]);

        return $userSerializer;
    }

    /**
     * @param Response $response
     * @param int $time
     * @param Request $request
     * @return Response
     */
    public function httpCaching(Response $response, int $time, ?Request $request = null) : Response
    {
        $response->setSharedMaxAge($time);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        // Validation
        $response->setEtag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
        // Pour vérifier que la validation fonctionne (304 = non modifiée, 200 = modification)
        // dump($response->getStatusCode());

        return $response;
    }
}
