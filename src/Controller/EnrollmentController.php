<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/courses", name="course_")
 */
class EnrollmentController extends AbstractApiController
{
    /**
     * @Route("/{courseId}/enroll", methods={"POST"}, name="enroll")
     */
    public function enroll(Request $request, CourseRepository $repo, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        if (in_array($user, $course->getParticipants()->getValues())) {
            return $this->respond('You are already enrolled!', Response::HTTP_FORBIDDEN);
        }

        $course->addParticipant($user);

        $em->persist($user);
        $em->flush();

        return $this->respond('Successfully enrolled!');
    }

    /**
     * @Route("/{courseId}/unroll", methods={"POST"}, name="unroll")
     */
    public function unroll(Request $request, CourseRepository $repo, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $course = $repo->find($request->get('courseId'));

        if (!$course) {
            return $this->respond('Course not found!', Response::HTTP_NOT_FOUND);
        }

        if (!in_array($user, $course->getParticipants()->getValues())) {
            return $this->respond('You are not enrolled!', Response::HTTP_FORBIDDEN);
        }

        $course->removeParticipant($user);

        $em->persist($user);
        $em->flush();

        return $this->respond('Successfully unrolled!');
    }
}