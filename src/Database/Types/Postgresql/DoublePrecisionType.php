<?php

namespace AI\Omega\Database\Types\Postgresql;

use AI\Omega\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
