<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractApiController
{
    /**
     * @Route("/api/courses", methods={"GET"}, name="courses_index")
     */
    public function index(CourseRepository $repo): Response
    {
        $courses = $repo->findAll();

        return $this->json($courses);
    }

    /**
     * @Route("/api/courses", methods={"POST"}, name="courses_store")
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

        $em->persist($course);
        $em->flush();

        return $this->json($course);
    }
}
