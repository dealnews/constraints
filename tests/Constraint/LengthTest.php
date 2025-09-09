<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Length;

class LengthTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Length::filter($value, $constraint, $dc)
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

    public static function constraintData() {
        // 15.1, 15.1\", 15.1in, 15.1-inch, 10cm, 10m, 10km
        return [
            [
                15.1,
                ['type' => 'length'],
                '15.1',
            ],
            [
                '15.1"',
                ['type' => 'length'],
                '15.1"',
            ],
            [
                '15.1in',
                ['type' => 'length'],
                '15.1"',
            ],
            [
                '15.1-inch',
                ['type' => 'length'],
                '15.1"',
            ],
            [
                '15ft',
                ['type' => 'length'],
                "15'",
            ],
            [
                "15'",
                ['type' => 'length'],
                "15'",
            ],
            [
                '15-foot',
                ['type' => 'length'],
                "15'",
            ],
            [
                '10cm',
                ['type' => 'length'],
                '10cm',
            ],
            [
                '10m',
                ['type' => 'length'],
                '10m',
            ],
            [
                '10km',
                ['type' => 'length'],
                '10km',
            ],
            [
                '10mm',
                ['type' => 'length'],
                '10mm',
            ],
            [
                '10 feet',
                ['type' => 'length'],
                null,
            ],
            [
                '10 miles',
                ['type' => 'length'],
                null,
            ],
        ];
    }
}
