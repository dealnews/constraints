<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * URL Filter
 *
 * Ensures a value is a valid URL
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class URL extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = 'A valid URL';

    const EXAMPLE = 'https://www.dealnews.com/';

    const PRIMITIVE = 'string';

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
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $value = null;
        }

        return $value;
    }
}
