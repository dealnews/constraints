<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * URL Path Filter
 *
 * Ensures a value is a valid URL path which begins with a /
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class URLPath extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = "A valid URL path which begins with '/'";

    const EXAMPLE = "/categories/laptops/";

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
        $value = trim($value);
        if (
            empty($value) ||
            $value[0] != "/" ||
            !filter_var("http://www.example.com".$value, FILTER_VALIDATE_URL)
        ) {
            $value = null;
        }
        return $value;
    }
}
