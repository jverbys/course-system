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

class FolderController extends AbstractApiController
{
    private FolderDtoTransformer $folderDtoTransformer;

    public function __construct(FolderDtoTransformer $folderDtoTransformer)
    {
        $this->folderDtoTransformer = $folderDtoTransformer;
    }

    /**
     * @Route("/api/courses/{courseId}/folders", methods={"GET"}, name="folder_index")
     */
    public function indexAction(Request $request, FolderRepository $repo): Response
    {
        $folders = $repo->findBy([
            'course' => $request->get('courseId'),
        ]);

        $dto = $this->folderDtoTransformer->transformFromObjects($folders);

        return $this->respond($dto);
    }

    /**
     * @Route("/api/courses/{courseId}/folders", methods={"POST"}, name="folder_create")
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
     * @Route("/api/courses/{courseId}/folders/{folderId}", methods={"GET"}, name="folder_show")
     */
    public function showAction(Request $request, FolderRepository $repo): Response
    {
        $folder = $repo->findOneBy([
            'course' => $request->get('courseId'),
            'id' => $request->get('folderId'),
        ]);

        if (!$folder) {
            return $this->respond('Folder not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->folderDtoTransformer->transformFromObject($folder);

        return $this->respond($dto);
    }

    /**
     * @Route("/api/courses/{courseId}/folders/{folderId}", methods={"PATCH"}, name="folder_update")
     */
    public function updateAction(Request $request, FolderRepository $repo, EntityManagerInterface $em): Response
    {
        $folder = $repo->findOneBy([
            'course' => $request->get('courseId'),
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
     * @Route("/api/courses/{courseId}/folders/{folderId}", methods={"DELETE"}, name="folder_delete")
     */
    public function deleteAction(Request $request, FolderRepository $repo, EntityManagerInterface $em): Response
    {
        $folder = $repo->findOneBy([
            'course' => $request->get('courseId'),
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
