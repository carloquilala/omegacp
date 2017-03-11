<?php

namespace artworx\omegacp\Database\Types\Postgresql;

use artworx\omegacp\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
