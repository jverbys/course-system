<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\FileDtoTransformer;
use App\Entity\File;
use App\Entity\Folder;
use App\Form\FileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="file_")
 */
class FileController extends AbstractApiController
{
    private FileDtoTransformer $fileDtoTransformer;

    public function __construct(FileDtoTransformer $fileDtoTransformer)
    {
        $this->fileDtoTransformer = $fileDtoTransformer;
    }

    /**
     * @Route("/folders/{folder}/files", methods={"GET"}, name="index")
     */
    public function indexAction(Folder $folder): Response
    {
        $files = $folder->getFiles();

        $dto = $this->fileDtoTransformer->transformFromObjects($files);

        return $this->respond($dto);
    }

    /**
     * @Route("/folders/{folder}/files", methods={"POST"}, name="create")
     */
    public function createAction(Request $request, Folder $folder, EntityManagerInterface $em): Response
    {
        $form = $this->buildForm(FileType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var File $file */
        $file = $form->getData();

        $file->setFolder($folder);
        $file->setCreatedAt(new \DateTime());

        $em->persist($file);
        $em->flush();

        $dto = $this->fileDtoTransformer->transformFromObject($file);

        return $this->respond($dto, Response::HTTP_CREATED);
    }

    /**
     * @Route("/files/{file}", methods={"GET"}, name="show")
     */
    public function showAction(File $file): Response
    {
        $dto = $this->fileDtoTransformer->transformFromObject($file);

        return $this->respond($dto);
    }

    /**
     * @Route("/files/{file}", methods={"DELETE"}, name="delete")
     */
    public function deleteAction(File $file, EntityManagerInterface $em): Response
    {
        $em->remove($file);
        $em->flush();

        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }
}
