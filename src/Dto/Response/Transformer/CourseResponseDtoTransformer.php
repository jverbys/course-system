<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\CourseResponseDto;
use App\Entity\Course;

class CourseResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @var Course $object
     */
    public function transformFromObject($object): CourseResponseDto
    {
        $dto = new CourseResponseDto();
        $dto->id = $object->getId();
        $dto->name = $object->getName();
        $dto->description = $object->getDescription();
        $dto->startDate = $object->getStartDate();
        $dto->endDate = $object->getEndDate();

        return $dto;
    }
}