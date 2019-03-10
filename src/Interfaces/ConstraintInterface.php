<?php

/**
 * Interface for constraints
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */

namespace DealNews\Constraints\Interfaces;

use DealNews\Constraints\Constraint;

interface ConstraintInterface {

    public static function filter($value, array $constraint, Constraint $dc);
}
