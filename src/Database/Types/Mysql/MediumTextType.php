<?php

namespace artworx\omegacp\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use artworx\omegacp\Database\Types\Type;

class MediumTextType extends Type
{
    const NAME = 'mediumtext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'mediumtext';
    }
}
