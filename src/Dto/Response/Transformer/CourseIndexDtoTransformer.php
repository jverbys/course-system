<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CourseIndexDto;
use App\Entity\Course;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class CourseIndexDtoTransformer extends AbstractResponseDtoTransformer
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @var Course $object
     */
    public function transformFromObject($object): CourseIndexDto
    {
        $dto = new CourseIndexDto();
        $dto->id = $object->getId();
        $dto->name = $object->getName();
        $dto->description = $object->getDescription();
        $dto->startDate = $object->getStartDate();
        $dto->endDate = $object->getEndDate();

        /** @var User $user */
        $user = $this->security->getUser();
        $dto->userIsEnrolled = in_array($user, $object->getParticipants()->getValues());

        return $dto;
    }
}