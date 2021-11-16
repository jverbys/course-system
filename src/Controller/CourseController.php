<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\CourseResponseDtoTransformer;
use App\Entity\Course;
use App\Entity\User;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractApiController
{
    private CourseResponseDtoTransformer $courseResponseDtoTransformer;

    public function __construct(CourseResponseDtoTransformer $courseResponseDtoTransformer)
    {
        $this->courseResponseDtoTransformer = $courseResponseDtoTransformer;
    }

    /**
     * @Route("/api/courses", methods={"GET"}, name="course_index")
     */
    public function indexAction(CourseRepository $repo): Response
    {
        $courses = $repo->findAll();

        $dto = $this->courseResponseDtoTransformer->transformFromObjects($courses);

        return $this->respond($dto);
    }

    /**
     * @Route("/api/courses", methods={"POST"}, name="course_create")
     */
    public function createAction(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->buildForm(CourseType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Course $course */
        $course = $form->getData();

        $course->setCreatedAt(new \DateTime());

        /** @var User $user */
        $user = $this->getUser();
        $course->setOwner($user);

        $em->persist($course);
        $em->flush();

        return $this->respond($course, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/courses/{courseId}", methods={"GET"}, name="course_show")
     */
    public function showAction(Request $request, CourseRepository $repo): Response
    {
        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        return $this->respond($course);
    }

    /**
     * @Route("/api/courses/{courseId}", methods={"PATCH"}, name="course_update")
     */
    public function updateAction(Request $request, CourseRepository $repo, EntityManagerInterface $em): Response
    {
        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        $form = $this->buildForm(CourseType::class, $course, [
            'method' => $request->getMethod(),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $course = $form->getData();

        $em->persist($course);
        $em->flush();

        return $this->respond($course);
    }

    /**
     * @Route("/api/courses/{courseId}", methods={"DELETE"}, name="course_delete")
     */
    public function deleteAction(Request $request, CourseRepository $repo, EntityManagerInterface $em): Response
    {
        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        $em->remove($course);
        $em->flush();

        return $this->respond(null, Response::HTTP_NO_CONTENT);
    }
}
