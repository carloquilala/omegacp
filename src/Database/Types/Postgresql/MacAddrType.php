<?php

namespace AI\Omega\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use AI\Omega\Database\Types\Type;

class MacAddrType extends Type
{
    const NAME = 'macaddr';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'macaddr';
    }
}
