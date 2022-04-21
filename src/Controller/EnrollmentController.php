<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/courses", name="course_")
 */
class EnrollmentController extends AbstractApiController
{
    /**
     * @Route("/{course}/enroll", methods={"POST"}, name="enroll")
     */
    public function enroll(Course $course, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (in_array($user, $course->getParticipants()->getValues())) {
            return $this->respond('You are already enrolled!', Response::HTTP_FORBIDDEN);
        }

        $course->addParticipant($user);

        $em->persist($user);
        $em->flush();

        return $this->respond('Successfully enrolled!');
    }

    /**
     * @Route("/{course}/unroll", methods={"POST"}, name="unroll")
     */
    public function unroll(Course $course, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!in_array($user, $course->getParticipants()->getValues())) {
            return $this->respond('You are not enrolled!', Response::HTTP_FORBIDDEN);
        }

        $course->removeParticipant($user);

        $em->persist($user);
        $em->flush();

        return $this->respond('Successfully unrolled!');
    }
}
