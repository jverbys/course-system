<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CourseDto;
use App\Entity\Course;

class CourseDtoTransformer extends AbstractResponseDtoTransformer
{
    private FolderDtoTransformer $folderDtoTransformer;

    public function __construct(FolderDtoTransformer $folderDtoTransformer)
    {
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

        return $dto;
    }
}