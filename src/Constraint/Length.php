<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Length filter
 *
 * Ensures a value is a valid length expression.
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Length extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A value describing a length, possibly in feet, inches, or a metric length";

    const EXAMPLE = "15.1, 15.1\", 15.1in, 15.1-inch, 10cm, 10m, 10km";

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
        if (!preg_match("/^\d\d*\.?\d*(\"|'|-?foot|-?ft|-?inch|-?in|(m|c|k)*m)*$/i", $value)) {
            $value = null;
        } else {
            $value = preg_replace("/(-?foot|-?ft)/i", "'", $value);
            $value = preg_replace("/(-?inch|-?in)/i", "\"", $value);
            $value = strtolower($value);
        }
        return $value;
    }
}
