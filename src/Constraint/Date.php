<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Date Filter
 *
 * Converts any valid date string into a formatted date string
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Date extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A value describing an absolute or relative date";

    const EXAMPLE = "'2018-07-21', 'Sat, 21 Jul 2018', 'July 21, 2018', 'today', '-2 days'";

    const PRIMITIVE = "string";

    /**
     * Filter function for this abstract type
     *
     * @param  mixed      $value      Value to filter
     * @param  array      $constraint Constraint array
     * @param  Constraint $dc         Constraint class
     *
     * @return string
     *
     * @suppress PhanUnusedPublicMethodParameter
     */
    public static function filter($value, array $constraint, Constraint $dc) {
        $ts = strtotime($value);
        if ($ts === false || $ts <= 0) {
            $value = null;
        } else {
            $value = date("Y-m-d", $ts);
        }
        return $value;
    }
}
