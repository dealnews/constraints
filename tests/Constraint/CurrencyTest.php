<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Currency;

class CurrencyTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Currency::filter($value, $constraint, $dc)
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
                "$1,000.00",
                ["type" => "currency"],
                "1000.00"
            ],
            [
                1000.00,
                ["type" => "currency"],
                "1000.00"
            ],
            [
                "10 cents",
                ["type" => "currency"],
                "0.10"
            ],
            [
                "10¢",
                ["type" => "currency"],
                null
            ],
        ];
    }
}
