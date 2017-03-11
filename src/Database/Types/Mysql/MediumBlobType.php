<?php

namespace AI\Omega\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use AI\Omega\Database\Types\Type;

class MediumBlobType extends Type
{
    const NAME = 'mediumblob';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'mediumblob';
    }
}
