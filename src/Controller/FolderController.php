<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\FolderDtoTransformer;
use App\Entity\Folder;
use App\Entity\User;
use App\Form\FolderType;
use App\Repository\CourseRepository;
use App\Repository\FolderRepository;
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
     * @Route("/courses/{courseId}/folders", methods={"GET"}, name="index")
     */
    public function indexAction(Request $request, FolderRepository $repo): Response
    {
        $folders = $repo->findBy([
            'course' => $request->get('courseId'),
            'parentFolder' => null,
        ]);

        $dto = $this->folderDtoTransformer->transformFromObjects($folders);

        return $this->respond($dto);
    }

    /**
     * @Route("/courses/{courseId}/folders", methods={"POST"}, name="create")
     */
    public function createAction(Request $request, CourseRepository $repo, EntityManagerInterface $em): Response
    {
        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

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
     * @Route("/folders/{folderId}", methods={"GET"}, name="show")
     */
    public function showAction(Request $request, FolderRepository $repo): Response
    {
        $folder = $repo->findOneBy([
            'id' => $request->get('folderId'),
        ]);

        if (!$folder) {
            return $this->respond('Folder not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->folderDtoTransformer->transformFromObject($folder);

        return $this->respond($dto);
    }

    /**
     * @Route("/folders/{folderId}", methods={"PATCH"}, name="update")
     */
    public function updateAction(Request $request, FolderRepository $repo, EntityManagerInterface $em): Response
    {
        $folder = $repo->findOneBy([
            'id' => $request->get('folderId'),
        ]);

        if (!$folder) {
            return $this->respond('Folder not found!', Response::HTTP_NOT_FOUND);
        }

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
     * @Route("/folders/{folderId}", methods={"DELETE"}, name="delete")
     */
    public function deleteAction(Request $request, FolderRepository $repo, EntityManagerInterface $em): Response
    {
        $folder = $repo->findOneBy([
            'id' => $request->get('folderId'),
        ]);

        if (!$folder) {
            return $this->respond('Folder not found!', Response::HTTP_NOT_FOUND);
        }

        $em->remove($folder);
        $em->flush();

        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }
}
