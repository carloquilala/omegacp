<?php

namespace artworx\omegacp\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use artworx\omegacp\Database\Types\Type;

class YearType extends Type
{
    const NAME = 'year';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'year';
    }
}
