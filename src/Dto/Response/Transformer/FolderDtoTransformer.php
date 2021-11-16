<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\FolderDto;
use App\Entity\Folder;

class FolderDtoTransformer extends AbstractResponseDtoTransformer
{
    private FileDtoTransformer $fileDtoTransformer;

    public function __construct(FileDtoTransformer $fileDtoTransformer)
    {
        $this->fileDtoTransformer = $fileDtoTransformer;
    }

    /**
     * @var Folder $object
     */
    public function transformFromObject($object): FolderDto
    {
        $dto = new FolderDto();
        $dto->id = $object->getId();
        $dto->name = $object->getName();
        $dto->files = $this->fileDtoTransformer->transformFromObjects($object->getFiles());
        $dto->subFolders = $this->transformFromObjects($object->getSubFolders());

        return $dto;
    }
}