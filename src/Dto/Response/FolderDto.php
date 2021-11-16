<?php

declare(strict_types=1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class FolderDto
{
    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("string")
     */
    public string $name;

    /**
     * @Serialization\Type("array<App\Dto\Response\FileDto>")
     */
    public iterable $files;

    /**
     * @Serialization\Type("array<App\Dto\Response\FolderDto>")
     */
    public iterable $subFolders;

}