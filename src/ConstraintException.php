<?php

/**
 * Constraint Exception
 *
 * This exception is thrown when a value does not meet the constraint
 *
 * @author      Brian Moon <brianm@dealnews.com>
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */

namespace DealNews\Constraints;

class ConstraintException extends \UnexpectedValueException {

    /**
     * A human friendly explination of the expected value
     *
     * @var string
     */
    protected $expected = "";

    /**
     * A human friendly example of valid values
     *
     * @var string
     */
    protected $example = "";

    /**
     * Constructor
     *
     * @param mixed           $value    The invalid value
     * @param string          $expected A human friendly explination of the expected value
     * @param string          $example  A human friendly example of valid values
     * @param integer         $code     A unique code for this thrown exception
     * @param \Throwable|null $previous A previously thrown exception which was caught
     */
    public function __construct ($value, string $expected, string $example, int $code = 0, \Throwable $previous = null) {
        if (!empty($expected)) {
            $this->expected = $expected;
        }

        if (!empty($example)) {
            $this->example = $example;
        }

        $message = "Invalid value";

        if (is_scalar($value)) {
            $message.= " ".$value;
        }

        $message.= ". Expected: $this->expected.";

        if (!empty($this->example)) {
            $message.= " Example: $this->example";
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Return the expected string
     *
     * @return string
     */
    public function getExpected (): string {
        return $this->expected;
    }

    /**
     * Return the example string
     *
     * @return string
     */
    public function getExample (): string {
        return $this->example;
    }
}
