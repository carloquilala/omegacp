<?php

namespace artworx\omegacp\Database\Types\Postgresql;

use artworx\omegacp\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
