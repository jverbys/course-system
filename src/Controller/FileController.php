<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\FileDtoTransformer;
use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use App\Repository\FolderRepository;
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
     * @Route("/folders/{folderId}/files", methods={"GET"}, name="index")
     */
    public function indexAction(Request $request, FileRepository $repo): Response
    {
        $files = $repo->findBy([
            'folder' => $request->get('folderId'),
        ]);

        $dto = $this->fileDtoTransformer->transformFromObjects($files);

        return $this->respond($dto);
    }

    /**
     * @Route("/folders/{folderId}/files", methods={"POST"}, name="create")
     */
    public function createAction(Request $request, FolderRepository $repo, EntityManagerInterface $em): Response
    {
        $folder = $repo->find($request->get('folderId'));

        if (!$folder) {
            return $this->respond('Folder not found!', Response::HTTP_NOT_FOUND);
        }

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
     * @Route("/files/{fileId}", methods={"GET"}, name="show")
     */
    public function showAction(Request $request, FileRepository $repo): Response
    {
        $file = $repo->findOneBy([
            'id' => $request->get('fileId'),
        ]);

        if (!$file) {
            return $this->respond('File not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->fileDtoTransformer->transformFromObject($file);

        return $this->respond($dto);
    }

    /**
     * @Route("/files/{fileId}", methods={"DELETE"}, name="delete")
     */
    public function deleteAction(Request $request, FileRepository $repo, EntityManagerInterface $em): Response
    {
        $file = $repo->findOneBy([
            'id' => $request->get('fileId'),
        ]);

        if (!$file) {
            return $this->respond('File not found!', Response::HTTP_NOT_FOUND);
        }

        $em->remove($file);
        $em->flush();

        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }
}