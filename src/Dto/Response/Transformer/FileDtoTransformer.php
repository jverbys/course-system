<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\FileDto;
use App\Entity\File;

class FileDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @var File $object
     */
    public function transformFromObject($object): FileDto
    {
        $dto = new FileDto();
        $dto->id = $object->getId();
        $dto->name = $object->getName();

        return $dto;
    }
}