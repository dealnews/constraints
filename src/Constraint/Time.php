<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Time Filter
 *
 * Converts any valid time string into a formatted time string
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Time extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A value describing an absolute or relative time";

    const EXAMPLE = "'01:21:58-05:00', '01:22:01 -0500', '1:23am', 'now', '-2 hours'";

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
            $value = date("H:i:s", $ts);
        }
        return $value;
    }
}
