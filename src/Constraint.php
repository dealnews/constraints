<?php

namespace DealNews\Constraints;

/**
 * Checks values against a data constraint
 *
 * @copyright   1997-Present DealNews.com, Inc
 * @package     Constraints
 */
class Constraint {

    /**
     * Primitive types supported by this class
     *
     * @var array
     *
     * @phan-suppress PhanReadOnlyProtectedProperty
     */
    protected $primitive_types = [
        'array',
        'boolean',
        'double',
        'integer',
        'string',
    ];

    /**
     * Abstract types and the classes that validate them
     *
     * @var array
     *
     * @phan-suppress PhanReadOnlyProtectedProperty
     */
    protected $abstract_types = [
        'bytes'     => \DealNews\Constraints\Constraint\Bytes::class,
        'currency'  => \DealNews\Constraints\Constraint\Currency::class,
        'date'      => \DealNews\Constraints\Constraint\Date::class,
        'datetime'  => \DealNews\Constraints\Constraint\DateTime::class,
        'length'    => \DealNews\Constraints\Constraint\Length::class,
        'range'     => \DealNews\Constraints\Constraint\Range::class,
        'time'      => \DealNews\Constraints\Constraint\Time::class,
        'url'       => \DealNews\Constraints\Constraint\URL::class,
        'url_path'  => \DealNews\Constraints\Constraint\URLPath::class,
        'year'      => \DealNews\Constraints\Constraint\Year::class,
    ];

    /**
     * A valid constraint and the type of each value for checking
     * constraints againt when debug is enabled.
     *
     * @var array
     *
     * @phan-suppress PhanReadOnlyProtectedProperty
     */
    protected $valid_constraint = [
        'type'           => '',
        'default'        => null,
        'is_nullable'    => false,
        'read_only'      => false,
        'constraint'     => [],
        'min'            => 0,
        'max'            => 0,
        'allowed_values' => [],
        'pattern'        => '',
    ];

    /**
     * Determines if debug is enabled
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     * Constructor
     * @param  array  $extra_options Array of extra options allowed in the
     *                               constraint.
     * @return object
     *
     * @suppress PhanTypeMissingReturn
     */
    public function __construct(array $extra_options = []) {

        if (!empty($_SERVER['DN_DEBUG'])) {
            $this->debug = true;
        }

        if (!empty($extra_options)) {
            $this->valid_constraint = array_merge($extra_options, $this->valid_constraint);
        }
    }

    /**
     * Checks if a value matches the constraint. Returns the value, cast and
     * modified as needed to match the constraint. For example, if the value
     * is the string "123" and the constraint specifies integer, the integer
     * 123 will be returned. If the value is not compatible with type juggling,
     * an exception is thrown.
     *
     * @param  mixed  $value      Some value
     * @param  array  $constraint A constraint array
     * @return mixed
     * @throws \LogicException
     * @throws ConstraintException
     */
    public function check($value, array $constraint) {

        if ($this->debug) {
            $this->validateConstraint($constraint);
        }

        $value = $this->checkDefault($value, $constraint);

        if ($this->isNull($value, $constraint)) {
            return null;
        }


        if (in_array($constraint['type'], $this->primitive_types)) {

            $new_value = $this->filterPrimitive($value, $constraint['type'], $constraint);

        } elseif (isset($this->abstract_types[$constraint['type']])) {

            $new_value = $this->filterAbstract($value, $this->abstract_types[$constraint['type']], $constraint);

        } elseif (is_object($value) || class_exists($constraint['type'])) {

            $new_value = $this->filterClass($value, $constraint['type']);

        } else {

            throw new \LogicException("Invalid constraint $constraint[type]");
        }

        if (!empty($constraint['allowed_values'])) {
            if (!in_array($new_value, $constraint['allowed_values'])) {
                throw new ConstraintException(
                    $value,
                    'one of ' . implode(', ', $constraint['allowed_values']),
                    ''
                );
            }
        }

        if (!empty($constraint['read_only'])) {
            // we can validate, but not modify read only values
            $new_value = $value;
        }

        return $new_value;

    }

    /**
     * Validates primitive types
     *
     * @param  mixed  $value                Value to filter
     * @param  string $type                 Type which should match
     * @param  array  $constraint           A constraint array
     * @param  array  $ignored_properties   A list of properties of a constraint to ignore
     *                                      (with the assumption they will be checked, elsewhere)
     * @return mixed
     */
    public function filterPrimitive($value, string $type, array $constraint, array $ignored_properties = []) {

        $expectation = "valid $type";

        $new_value = $value;

        if (!empty($ignored_properties)) {
            // remove array keys in $constraint that are listed in $ignored_properties
            $constraint = array_diff_key($constraint, array_combine($ignored_properties, $ignored_properties));
        }

        switch ($type) {

            case 'array':
                if (!is_array($value)) {
                    $new_value = null;
                    if (is_object($value) && $value instanceof \ArrayObject) {
                        $new_value = $value->getArrayCopy();
                    }
                }
                if (is_array($new_value) && !empty($constraint['constraint'])) {
                    try {
                        foreach ($new_value as $nv) {
                            $this->check($nv, $constraint['constraint']);
                        }
                    } catch (ConstraintException $e) {
                        $new_value   = null;
                        $expectation = 'array contents: ' . $e->getExpected();
                    }
                }
                break;

            case 'boolean':
                if (!is_bool($value)) {
                    $new_value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                }
                break;

            case 'double':
                if (!is_float($value)) {
                    $new_value = filter_var($value, FILTER_VALIDATE_FLOAT);
                }
                if ($new_value === false) {
                    $new_value = null;
                } elseif (isset($constraint['max']) && $new_value > $constraint['max']) {
                    $new_value   = null;
                    $expectation = "$constraint[max] or less";

                } elseif (isset($constraint['min']) && $new_value < $constraint['min']) {
                    $new_value   = null;
                    $expectation = "$constraint[max] or more";
                }
                break;

            case 'integer':
                if (!is_int($value)) {
                    $new_value = filter_var($value, FILTER_VALIDATE_INT);
                }
                if ($new_value === false) {
                    $new_value = null;
                } elseif (isset($constraint['max']) && $new_value > $constraint['max']) {
                    $new_value   = null;
                    $expectation = "$constraint[max] or less";

                } elseif (isset($constraint['min']) && $new_value < $constraint['min']) {
                    $new_value   = null;
                    $expectation = "$constraint[max] or more";
                }
                break;

            case 'string':
                if (!is_scalar($value)) {
                    $new_value = null;
                } else {
                    $new_value = (string)$value;
                    if (isset($constraint['max']) && mb_strlen($new_value) > $constraint['max']) {
                        $new_value   = null;
                        $expectation = "maximum length of $constraint[max]";
                    } elseif (isset($constraint['min']) && mb_strlen($new_value) < $constraint['min']) {
                        $new_value   = null;
                        $expectation = "minimum length of $constraint[min]";
                    } elseif (isset($constraint['pattern']) && !preg_match($constraint['pattern'], $new_value)) {
                        $new_value   = null;
                        $expectation = 'matches pattern';
                    }
                }
                break;

        }

        if ($new_value === null) {
            throw new ConstraintException(
                $value,
                $expectation,
                ''
            );
        }

        return $new_value;
    }

    /**
     * Validates an abstract type
     *
     * @param  mixed  $value      Value to filter
     * @param  string $class      Abstract class name
     * @param  array  $constraint A constraint array
     * @return mixed
     */
    public function filterAbstract($value, string $class, array $constraint) {
        $new_value = $class::filter(
            $this->filterPrimitive($value, $class::PRIMITIVE, $constraint, $class::PRIMITIVE_IGNORED_PROPERTIES),
            $constraint,
            $this
        );
        if ($new_value === null) {
            throw new ConstraintException(
                $value,
                $class::DESCRIPTION,
                $class::EXAMPLE
            );
        }

        return $new_value;
    }


    /**
     * Validates a constraint with a class for a type
     *
     * @param  mixed  $value      Value to filter
     * @param  string $class      Class name
     * @return mixed
     */
    public function filterClass($value, string $class) {
        if (!($value instanceof $class)) {
            if (is_array($value) && is_a($class, '\\ArrayObject', true)) {
                $obj = new $class();
                $obj->exchangeArray($value);
                $value = $obj;
            } else {
                throw new ConstraintException(
                    $value,
                    "instance of $class",
                    ''
                );
            }
        }

        return $value;
    }

    /**
     * Checks if the value should be set to the default. Returns the value,
     * possibly modifed to the default value.
     *
     * @param  mixed  $value      Value to filter
     * @param  array  $constraint A constraint array
     * @return mixed
     */
    protected function checkDefault($value, array $constraint) {
        /**
         * If we got a null value but the column has a default, change it
         * and throw a warning on dev as that is bad form.
         */
        if (array_key_exists('default', $constraint)) {
            if ($constraint['default'] !== null && $value === null) {
                $value = $constraint['default'];
            }
        }

        return $value;
    }

    /**
     * Determines if the value is null and if it is allowed to be null.
     *
     * @param  mixed  $value      Value to filter
     * @param  array  $constraint A constraint array
     * @return boolean             [description]
     */
    protected function isNull($value, array $constraint): bool {
        $is_null = false;
        /**
         * If we are still null after the default check, we need to just skip
         * out because it messes up all the type checks. Any column can be
         * null. It is the o-negative of database values
         */
        if (
            $value === null &&
            (
                !isset($constraint['is_nullable'])  ||
                $constraint['is_nullable'] === true ||
                (
                    isset($constraint['default']) &&
                    $constraint['default'] === null
                )
            )
        ) {
            $is_null = true;
        }

        return $is_null;
    }

    /**
     * Validates a constraint array
     * @param  array  $constraint A constraint array
     * @return bool
     * @throws \LogicException
     */
    public function validateConstraint(array $constraint): bool {
        foreach ($constraint as $option => $value) {
            if (!array_key_exists($option, $this->valid_constraint)) {
                throw new \LogicException("Invalid constraint option $option", 1);
            }
            if ($this->valid_constraint[$option] !== null && gettype($value) != gettype($this->valid_constraint[$option])) {
                throw new \LogicException("Constraint option $option must be of type " . gettype($this->valid_constraint[$option]), 2);
            }
        }

        return true;
    }

    /**
     * Returns a singleton for this class
     *
     * @param  array  $extra_options Array of extra options allowed in the
     *                               constraint.
     * @return object
     */
    public static function init(array $extra_options = []) {
        static $instances = [];

        $key = md5(serialize($extra_options));
        if (!array_key_exists($key, $instances)) {
            $class           = get_called_class();
            $instances[$key] = new $class($extra_options);
        }

        return $instances[$key];
    }
}
