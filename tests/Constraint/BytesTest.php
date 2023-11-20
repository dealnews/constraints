<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Bytes;

class BytesTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            Bytes::filter($value, $constraint, $dc)
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
                '1MB',
                ['type' => 'bytes'],
                '1MB',
            ],
            [
                '1kb',
                ['type' => 'bytes'],
                '1kb',
            ],
            [
                10,
                ['type' => 'bytes'],
                null,
            ],
        ];
    }
}
