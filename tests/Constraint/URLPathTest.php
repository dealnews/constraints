<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\URLPath;

class URLPathTest extends \PHPUnit\Framework\TestCase {
    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();
        $this->assertEquals(
            $expect,
            URLPath::filter($value, $constraint, $dc)
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
                ['type' => 'url_path'],
                null,
            ],
            [
                '    ',
                ['type' => 'url_path'],
                null,
            ],
            [
                '/foo/bar',
                ['type' => 'url_path'],
                '/foo/bar',
            ],
        ];
    }
}
