<?php

namespace AI\Omega\Database\Types\Postgresql;

use AI\Omega\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
