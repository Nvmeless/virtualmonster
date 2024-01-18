<?php

namespace App\Controller;

use App\Entity\DownloadedFiles;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DownloadedFilesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DownloadedFilesController extends AbstractController
{
    #[Route('/', name: 'app.index')]
    public function index(): void
    {

        
    }
     #[Route('/api/file', name: 'files.create', methods:["POST"])]
    public function createFile(Request $request, DownloadedFilesRepository $repository, SerializerInterface $serializer, 
    EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $newFile = new DownloadedFiles();

        $file = $request->files->get("file");
        
        $newFile->setFile($file);
        $entityManager->persist($newFile);
        $entityManager->flush();

        $realname = $newFile->getRealname();
        $realpath = $newFile->getRealpath();
        $slug = $newFile->getSlug();
        $jsonPicture= [
            "id"=>$newFile->getId(),
            "name"=>$newFile->getName(),
            "realname"=>$realname,
            "realpath"=>$realpath,
            "mimetype"=>$newFile->getMimeType(),
            "slug"=>$slug,
        ];
        $location = $urlGenerator->generate("app.index",[], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonPicture, Response::HTTP_CREATED,["Location"=> $location. $realpath . "/".$slug]);
    }
}
