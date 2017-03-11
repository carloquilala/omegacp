<?php

namespace AI\Omega\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use AI\Omega\Database\Types\Type;

class YearType extends Type
{
    const NAME = 'year';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'year';
    }
}
