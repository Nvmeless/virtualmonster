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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Contracts\EventDispatcher\Event;

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
    public function getAllMonstredex(MonstredexRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $monstredexs = $repository->findAll();// Datas 
 
        $jsonMonstredex = $serializer->serialize($monstredexs,'json');

        return new JsonResponse($jsonMonstredex, Response::HTTP_OK,[],true);
    }
        
        #[Route('/api/monstredex/{id}', name: 'monstredex.get', methods: ['GET'])]
    public function getMonstredex(int $id, MonstredexRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $monstredexs = $repository->find($id);// Datas 
 
        $jsonMonstredex = $serializer->serialize($monstredexs,'json');

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
        
        $jsonMonstredex = $serializer->serialize($monstredexEntry,'json');
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
    public function updateMonstredex(int $id,Request $request, MonstredexRepository $repository,SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $monstredex = $repository->find($id);// Datas 
        $updatedMonstredex = $serializer->deserialize($request->getContent(), Monstredex::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $monstredex]);
        $updatedMonstredex->setUpdatedAt(new DateTimeImmutable());
        $entityManager->persist($monstredex);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT,[]);
    }
}
