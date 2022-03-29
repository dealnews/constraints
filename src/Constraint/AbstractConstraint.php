<?php

namespace DealNews\Constraints\Constraint;

use DealNews\Constraints\Constraint;

abstract class AbstractConstraint {

    const DESCRIPTION = "A valid value";

    const EXAMPLE = "none provided";

    const PRIMITIVE = "";

    const PRIMITIVE_IGNORED_PROPERTIES = [];

    final public function __construct() {
        trigger_error(get_called_class()." must not be instantiated.", E_USER_ERROR);
        exit(1);
    }
}
