<?php

declare(strict_types=1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class CourseIndexDto
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
     * @Serialization\Type("string")
     */
    public string $description;

    /**
     * @Serialization\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public \DateTimeInterface $startDate;

    /**
     * @Serialization\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public \DateTimeInterface $endDate;
}