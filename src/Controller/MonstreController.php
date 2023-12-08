<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Monstre;
use App\Repository\MonstreRepository;
use App\Repository\MonstredexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MonstreController extends AbstractController
{
    #[Route('/monstre', name: 'app_monstre')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MonstreController.php',
        ]);
    }

    #[Route('/api/monstre', name: 'monstre.getAll', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message:"Hanhanhan vous n'avez pas dit le mot magiqueeeeuh")]
    public function getAllMonstre(MonstreRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $monstres = $repository->findAll();// Datas 
 
        $jsonMonstre = $serializer->serialize($monstres,'json',["groups" => "getAllMonstre"]);

        return new JsonResponse($jsonMonstre, Response::HTTP_OK,[],true);
    }
        
        #[Route('/api/monstre/{id}', name: 'monstre.get', methods: ['GET'])]
    public function getMonstre(int $id, MonstreRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $monstres = $repository->find($id);// Datas 
 
        $jsonMonstre = $serializer->serialize($monstres,'json',["groups" => "getAllMonstre"]);

        return new JsonResponse($jsonMonstre, Response::HTTP_OK,[],true);
    }
        
    #[Route('/api/monstre', name: 'monstre.create', methods:["POST"])]
    public function createMonstre(Request $request, MonstreRepository $repository, SerializerInterface $serializer, 
    EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $monstreEntry = $serializer->deserialize($request->getContent(), Monstre::class, 'json');
        $monstreEntry->setCreatedAt(new DateTimeImmutable());
        $monstreEntry->setUpdatedAt(new DateTimeImmutable());
        $monstreEntry->setStatus("online");
        $entityManager->persist($monstreEntry);
        $entityManager->flush();
        
        $jsonMonstre = $serializer->serialize($monstreEntry,'json',["groups" => "getAllMonstredex"]);
        $location = $urlGenerator->generate("monstre.get",["id" => $monstreEntry->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonMonstre, Response::HTTP_CREATED,["Location"=> $location],true);
    }
        
        #[Route('/api/monstre/{id}', name: 'monstre.delete', methods: ['DELETE'])]
    public function deleteMonstre(int $id, MonstreRepository $repository,EntityManagerInterface $entityManager): JsonResponse
    {
        $monstre = $repository->find($id);// Datas 
        $entityManager->remove($monstre);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT,[]);
    }

    #[Route('/api/monstre/{id}', name: 'monstre.update', methods: ['PATCH', "PUT"])]
    public function updateMonstredex(int $id,Request $request, MonstreRepository $repository, MonstredexRepository $monstredexRepository,SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $monstre = $repository->find($id);// Datas 
        $updatedMonstre = $serializer->deserialize($request->getContent(), Monstre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $monstre]);
        $updatedMonstre->setUpdatedAt(new DateTimeImmutable());
        $monstredexId  = $request->toArray()["monstredex"] ?? -1;
        $monstredex = $monstredexRepository->find($monstredexId);

        $updatedMonstre->setMonstreDex($monstredex);
        
        $entityManager->persist($monstre);
        $entityManager->flush();


        return new JsonResponse(null, Response::HTTP_NO_CONTENT,[]);
    }


}
