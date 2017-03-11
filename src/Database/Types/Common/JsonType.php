<?php

namespace artworx\omegacp\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use artworx\omegacp\Database\Types\Type;

class JsonType extends Type
{
    const NAME = 'json';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'json';
    }
}
