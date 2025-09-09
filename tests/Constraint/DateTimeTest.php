<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\DateTime;

class DateTimeTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            DateTime::filter($value, $constraint, $dc)
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
                ['type' => 'datetime'],
                date('Y-m-d') . ' 00:00:00',
            ],
            [
                '0000-00-00 00:00:00',
                ['type' => 'datetime'],
                null,
            ],
            [
                '',
                ['type' => 'datetime'],
                null,
            ],
            [
                '2018-01-01',
                ['type' => 'datetime'],
                '2018-01-01 00:00:00',
            ],
        ];
    }
}
