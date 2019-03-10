<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Date;

class DateTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Date::filter($value, $constraint, $dc)
        );

        if ($expect !== null) {
            // now check that the integration works for
            // ones that are not expected to fail
            $this->assertEquals(
                $expect,
                $dc->check($value, $constraint)
            );
        }
    }

    public function constraintData() {

        return [
            [
                "now",
                ["type" => "date"],
                date("Y-m-d")
            ],
            [
                "0000-00-00 00:00:00",
                ["type" => "datetime"],
                null
            ],
            [
                "",
                ["type" => "date"],
                null
            ],
            [
                "2018-01-01",
                ["type" => "date"],
                "2018-01-01"
            ],
        ];
    }
}
