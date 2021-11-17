<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\CourseDtoTransformer;
use App\Dto\Response\Transformer\CourseIndexDtoTransformer;
use App\Entity\Course;
use App\Entity\User;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="course_")
 */
class CourseController extends AbstractApiController
{
    private CourseIndexDtoTransformer $courseIndexDtoTransformer;
    private CourseDtoTransformer $courseDtoTransformer;

    public function __construct(CourseIndexDtoTransformer $courseIndexDtoTransformer, CourseDtoTransformer $courseDtoTransformer)
    {
        $this->courseIndexDtoTransformer = $courseIndexDtoTransformer;
        $this->courseDtoTransformer = $courseDtoTransformer;
    }

    /**
     * @Route("/courses", methods={"GET"}, name="index")
     */
    public function indexAction(CourseRepository $repo): Response
    {
        $courses = $repo->findAll();

        $dto = $this->courseIndexDtoTransformer->transformFromObjects($courses);

        return $this->respond($dto);
    }

    /**
     * @Route("/courses", methods={"POST"}, name="create")
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

        $dto = $this->courseDtoTransformer->transformFromObject($course);

        return $this->respond($dto, Response::HTTP_CREATED);
    }

    /**
     * @Route("/courses/{courseId}", methods={"GET"}, name="show")
     */
    public function showAction(Request $request, CourseRepository $repo): Response
    {
        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->courseDtoTransformer->transformFromObject($course);

        return $this->respond($dto);
    }

    /**
     * @Route("/courses/{courseId}", methods={"PATCH"}, name="update")
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

        $dto = $this->courseDtoTransformer->transformFromObject($course);

        return $this->respond($dto);
    }

    /**
     * @Route("/courses/{courseId}", methods={"DELETE"}, name="delete")
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
