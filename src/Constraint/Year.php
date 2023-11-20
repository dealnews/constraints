<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Year Filter
 *
 * A valid value is between 1901 and 2155
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Year extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = 'A value describing a year between 1901 and 2155';

    const EXAMPLE = '1998, 2000, 2018';

    const PRIMITIVE = 'integer';

    /**
     * Filter function for this abstract type
     *
     * @param  mixed      $value      Value to filter
     * @param  array      $constraint Constraint array
     * @param  Constraint $dc         Constraint class
     *
     * @return int
     *
     * @suppress PhanUnusedPublicMethodParameter
     */
    public static function filter($value, array $constraint, Constraint $dc) {
        if ($value < 1901 || $value > 2155) {
            $value = null;
        }

        return $value;
    }
}
