<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Year;

class YearTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Year::filter($value, $constraint, $dc)
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
                'midnight',
                ['type' => 'year'],
                null,
            ],
            [
                '1998',
                ['type' => 'year'],
                1998,
            ],
            [
                1055,
                ['type' => 'year'],
                null,
            ],
            [
                2200,
                ['type' => 'year'],
                null,
            ],
        ];
    }
}
