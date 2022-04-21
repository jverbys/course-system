<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\FolderDtoTransformer;
use App\Entity\Course;
use App\Entity\Folder;
use App\Entity\User;
use App\Form\FolderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="folder_")
 */
class FolderController extends AbstractApiController
{
    private FolderDtoTransformer $folderDtoTransformer;

    public function __construct(FolderDtoTransformer $folderDtoTransformer)
    {
        $this->folderDtoTransformer = $folderDtoTransformer;
    }

    /**
     * @Route("/courses/{course}/folders", methods={"GET"}, name="index")
     */
    public function indexAction(Course $course): Response
    {
        $folders = $course->getFolders();

        $dto = $this->folderDtoTransformer->transformFromObjects($folders);

        return $this->respond($dto);
    }

    /**
     * @Route("/courses/{course}/folders", methods={"POST"}, name="create")
     */
    public function createAction(Request $request, Course $course, EntityManagerInterface $em): Response
    {
        $form = $this->buildForm(FolderType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Folder $folder */
        $folder = $form->getData();

        $folder->setCourse($course);
        $folder->setCreatedAt(new \DateTime());

        /** @var User $user */
        $user = $this->getUser();
        $folder->setOwner($user);

        if ($folder->getParentFolder() && $folder->getCourse() !== $folder->getParentFolder()->getCourse()) {
            return $this->respond(
                'Folder\'s and parent folder\'s courses don\'t match!',
                Response::HTTP_BAD_REQUEST
            );
        }

        $em->persist($folder);
        $em->flush();

        $dto = $this->folderDtoTransformer->transformFromObject($folder);

        return $this->respond($dto, Response::HTTP_CREATED);
    }

    /**
     * @Route("/folders/{folder}", methods={"GET"}, name="show")
     */
    public function showAction(Folder $folder): Response
    {
        $dto = $this->folderDtoTransformer->transformFromObject($folder);

        return $this->respond($dto);
    }

    /**
     * @Route("/folders/{folder}", methods={"PATCH"}, name="update")
     */
    public function updateAction(Request $request, Folder $folder, EntityManagerInterface $em): Response
    {
        $form = $this->buildForm(FolderType::class, $folder, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $folder = $form->getData();

        if ($folder->getParentFolder() && $folder->getCourse() !== $folder->getParentFolder()->getCourse()) {
            return $this->respond(
                'Folder\'s and parent folder\'s courses don\'t match!',
                Response::HTTP_BAD_REQUEST
            );
        }

        $em->persist($folder);
        $em->flush();

        $dto = $this->folderDtoTransformer->transformFromObject($folder);

        return $this->respond($dto);
    }

    /**
     * @Route("/folders/{folder}", methods={"DELETE"}, name="delete")
     */
    public function deleteAction(Folder $folder, EntityManagerInterface $em): Response
    {
        $em->remove($folder);
        $em->flush();

        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }
}
