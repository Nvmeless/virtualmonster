<?php

namespace App\Controller;

use App\Repository\MonstredexRepository;
use App\Entity\Monstredex;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
class MonstredexController extends AbstractController
{
    #[Route('/monstredex', name: 'app_monstredex')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MonstredexController.php',
        ]);
    }

    #[Route('/api/monstredex', name: 'monstredex.getAll', methods: ['GET'])]
    public function getAllMonstredex(MonstredexRepository $repository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        // $monstredexs = $repository->findAll();// Datas 
        // $jsonMonstredex = $serializer->serialize($monstredexs,'json',["groups" => "getAllMonstredex"]);
       
        $idCache = "getAllMonstredex";
        $cache->invalidateTags(["monstredexCache"]);

        $jsonMonstredex = $cache->get($idCache, function (ItemInterface $item) use ($repository, $serializer) {
            $item->tag("monstredexCache");
            echo "MISE EN CACHE";
            $monstredexList = $repository->findAll();
            $context = SerializationContext::create()->setGroups(["getAllMonstredex"]);
            return $serializer->serialize($monstredexList, 'json', $context);

        } );
        return new JsonResponse($jsonMonstredex, Response::HTTP_OK,[],true);
    }
        /**
         * Cette methode recupere des monstredex
         */
        #[Route('/api/monstredex/{id}', name: 'monstredex.get', methods: ['GET'])]
        #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Monstredex::class, groups: ['getAllMonstredex']))
        )
    )]
    //     #[OA\Parameter(
    //     name: 'id',
    //     in: 'path',
    //     description: "l'id monstredex",
    //     schema: new OA\Schema(type: 'int')
    // )]
        //     #[OA\Parameter(
    //     name: 'id',
    //     in: 'query',
    //     description: "l'id monstredex",
    //     schema: new OA\Schema(type: 'int')
    // )]
    #[OA\Tag(name: 'monstredex')]
    #[Security(name: 'Bearer')]
    public function getMonstredex(int $id, MonstredexRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $monstredexs = $repository->find($id);// Datas 
        $context = SerializationContext::create()->setGroups(["getAllMonstredex"]);
 
        $jsonMonstredex = $serializer->serialize($monstredexs,'json',$context);

        return new JsonResponse($jsonMonstredex, Response::HTTP_OK,[],true);
    }
        
    #[Route('/api/monstredex', name: 'monstredex.create', methods:["POST"])]
    public function createMonstredex(Request $request, MonstredexRepository $repository, SerializerInterface $serializer, 
    EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $monstredexEntry = $serializer->deserialize($request->getContent(), Monstredex::class, 'json');
        $monstredexEntry->setCreatedAt(new DateTimeImmutable());
        $monstredexEntry->setUpdatedAt(new DateTimeImmutable());
        $monstredexEntry->setStatus("online");
        $entityManager->persist($monstredexEntry);
        $entityManager->flush();
        $context = SerializationContext::create()->setGroups(["getAllMonstredex"]);
        
        $jsonMonstredex = $serializer->serialize($monstredexEntry,'json',$context);
        $location = $urlGenerator->generate("monstredex.get",["id" => $monstredexEntry->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonMonstredex, Response::HTTP_CREATED,["Location"=> $location],true);
    }
        
        #[Route('/api/monstredex/{id}', name: 'monstredex.delete', methods: ['DELETE'])]
    public function deleteMonstredex(int $id, MonstredexRepository $repository,EntityManagerInterface $entityManager): JsonResponse
    {
        $monstredex = $repository->find($id);// Datas 
        $entityManager->remove($monstredex);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT,[]);
    }

    #[Route('/api/monstredex/{id}', name: 'monstredex.update', methods: ['PATCH', "PUT"])]
    public function updateMonstredex(int $id,TagAwareCacheInterface $cache, ValidatorInterface $validator,Request $request, MonstredexRepository $repository,SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $monstredex = $repository->find($id);// Datas 
        // $updatedMonstredex = $serializer->deserialize($request->getContent(), Monstredex::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $monstredex]);
        $updatedMonstredex = $serializer->deserialize($request->getContent(), Monstredex::class, 'json');
        $monstredex->setName($updatedMonstredex->getName() ?? $monstredex->getName());
        $monstredex->setPvMax($updatedMonstredex->getPvMax() ?? $monstredex->getPvMax());
        $monstredex->setPvMin($updatedMonstredex->getPvMin() ?? $monstredex->getPvMin());
        $monstredex->setUpdatedAt(new DateTimeImmutable());
        $monstredex->setDevolution($updatedMonstredex->getDevolution() ?? $monstredex->getDevolution());
        $monstredex->setUpdatedAt(new DateTimeImmutable());
      
        $errors = $validator->validate($monstredex);
        $cache->invalidateTags(["monstredexCache"]);
        if($errors->count() > 0){
            return new JsonResponse($serializer->serialize($errors, "json"), JsonResponse::HTTP_BAD_REQUEST ,[], true);
        }
        $entityManager->persist($monstredex);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT,[]);
    }
}
