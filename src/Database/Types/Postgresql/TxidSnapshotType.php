<?php

namespace artworx\omegacp\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use artworx\omegacp\Database\Types\Type;

class TxidSnapshotType extends Type
{
    const NAME = 'txid_snapshot';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'txid_snapshot';
    }
}
