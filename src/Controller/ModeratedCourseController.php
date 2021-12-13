<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Response\Transformer\CourseIndexDtoTransformer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/moderated-courses", name="moderated_course_")
 */
class ModeratedCourseController extends AbstractApiController
{
    private CourseIndexDtoTransformer $courseIndexDtoTransformer;

    public function __construct(CourseIndexDtoTransformer $courseIndexDtoTransformer)
    {
        $this->courseIndexDtoTransformer = $courseIndexDtoTransformer;
    }

    /**
     * @Route("", methods={"GET"}, name="index")
     */
    public function indexAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $courses = $user->getModeratedCourses();

        $dto = $this->courseIndexDtoTransformer->transformFromObjects($courses);

        return $this->respond($dto);
    }
}