<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Time;

class TimeTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Time::filter($value, $constraint, $dc)
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
        return [
            [
                'midnight',
                ['type' => 'time'],
                '00:00:00',
            ],
            [
                '00:00:00',
                ['type' => 'time'],
                '00:00:00',
            ],
            [
                '3pm',
                ['type' => 'time'],
                '15:00:00',
            ],
            [
                '25:00:00',
                ['type' => 'time'],
                null,
            ],
            [
                '',
                ['type' => 'time'],
                null,
            ],
            [
                '2018-01-01',
                ['type' => 'time'],
                '00:00:00',
            ],
        ];
    }
}
