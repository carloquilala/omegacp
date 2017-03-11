<?php

namespace AI\Omega\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use AI\Omega\Database\Types\Type;

class LineStringType extends Type
{
    const NAME = 'linestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'linestring';
    }
}
