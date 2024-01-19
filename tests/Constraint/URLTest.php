<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\URL;

class URLTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            URL::filter($value, $constraint, $dc)
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
                'http://www.example.com/',
                ['type' => 'url'],
                'http://www.example.com/',
            ],
            [
                "http://www.example.com/  ",
                ["type" => "url"],
                "http://www.example.com/"
            ],
            [
                '    ',
                ['type' => 'url'],
                null,
            ],
            [
                'asdf',
                ['type' => 'url'],
                null,
            ],
        ];
    }
}
