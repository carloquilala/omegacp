<?php

namespace AI\Omega\Database\Types\Sqlite;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use AI\Omega\Database\Types\Type;

class RealType extends Type
{
    const NAME = 'real';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'real';
    }
}
