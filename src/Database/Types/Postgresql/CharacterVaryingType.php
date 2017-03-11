<?php

namespace artworx\omegacp\Database\Types\Postgresql;

use artworx\omegacp\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
