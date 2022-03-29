<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Range Filter
 *
 * Confirms a value is a valid range filter. A valid range filter is an
 * array that contains either two or three values. The first value must be
 * one of =, <, >, <=, >=, or between. Only between accepts 3 values. For the
 * other operators, the second value is the comparison value. For between,
 * the second and third values specify a lower and upper bound.
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Range extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A range query structure";

    const EXAMPLE = "`['>', 5]`, `['between', 5, 10]`";

    const PRIMITIVE = "array";

    const PRIMITIVE_IGNORED_PROPERTIES = ['constraint'];

    protected static $operators = [
        "=",
        "<",
        ">",
        ">=",
        "<=",
        "between"
    ];

    public static function filter($value, array $constraint, Constraint $dc) {
        $return_value = null;
        /**
         * Ranges consist of 2 or 3 elements
         * The first (key 0) is the comparison operator - = < > => =< >= <= or between
         * The second (key 1) is required and must not be null
         * The third (key 2) is required for a between comparison and must not be null
         */
        if (
            in_array($value[0], self::$operators) &&
            isset($value[1]) &&
            (
                $value[0] != "between" ||
                (array_key_exists(2, $value) && isset($value[2]))
            )
        ) {
            if (isset($constraint["constraint"])) {
                $value[1] = $dc->check($value[1], $constraint["constraint"]);
                if (array_key_exists(2, $value)) {
                    $value[2] = $dc->check($value[2], $constraint["constraint"]);
                }
            }

            $return_value = $value;
        }
        return $return_value;
    }
}
