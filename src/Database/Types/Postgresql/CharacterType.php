<?php

namespace AI\Omega\Database\Types\Postgresql;

use AI\Omega\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
