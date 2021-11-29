<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CourseDto;
use App\Entity\Course;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class CourseDtoTransformer extends AbstractResponseDtoTransformer
{
    private Security $security;
    private FolderDtoTransformer $folderDtoTransformer;

    public function __construct(Security $security, FolderDtoTransformer $folderDtoTransformer)
    {
        $this->security = $security;
        $this->folderDtoTransformer = $folderDtoTransformer;
    }

    /**
     * @var Course $object
     */
    public function transformFromObject($object): CourseDto
    {
        $dto = new CourseDto();
        $dto->id = $object->getId();
        $dto->name = $object->getName();
        $dto->description = $object->getDescription();
        $dto->startDate = $object->getStartDate();
        $dto->endDate = $object->getEndDate();
        $dto->folders = $this->folderDtoTransformer->transformFromObjects($object->getFolders());

        /** @var User $user */
        $user = $this->security->getUser();
        $dto->userIsOwner = $object->getOwner() === $user;
        $dto->userIsModerator = in_array($user, $object->getModerators()->getValues()) ||
            in_array(User::ROLE_ADMIN, $user->getRoles());
        $dto->userIsEnrolled = in_array($user, $object->getParticipants()->getValues());
        $dto->userCanEnroll = !in_array(User::ROLE_COMPANY, $user->getRoles());

        return $dto;
    }
}