<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Currency Filter
 *
 * Converts any valid US dollar amount into a formatted string
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Currency extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A value describing a US dollar amount";

    const EXAMPLE = "19.99, $50, $49.99, 99 cents";

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
        if (preg_match('/^(\d{1,2}) cents$/', $value, $match)) {
            $value = "0.".$match[1];
        } else {
            $value = preg_replace('/^\$/', '', $value);
            $value = str_replace(",", "", $value);
            $value = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
            if ($value != null) {
                $value = number_format($value, 2, ".", "");
            }
        }
        return $value;
    }
}
