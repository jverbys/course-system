<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CourseIndexDto;
use App\Entity\Course;

class CourseIndexDtoTransformer extends AbstractResponseDtoTransformer
{
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

        return $dto;
    }
}