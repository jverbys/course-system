<?php

declare(strict_types=1);

namespace App\Dto\Response\Transformer;

interface IResponseDtoTransformer
{
    public function transformFromObject($object);

    public function transformFromObjects(iterable $objects): iterable;
}