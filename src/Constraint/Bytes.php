<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;
use DealNews\Constraints\Interfaces\ConstraintInterface;

/**
 * Brief Class Description
 *
 * Ensures a value is a valid expression of bytes.
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Bytes extends AbstractConstraint implements ConstraintInterface {

    const DESCRIPTION = 'A value describing an amount of bytes';

    const EXAMPLE = '10kb, 250MB, 500GB, 2TB';

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
        if (!preg_match('/^\\d[\\d,\\.]*[kmgtp]b$/i', $value)) {
            $value = null;
        }

        return $value;
    }
}
