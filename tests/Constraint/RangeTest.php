<?php

namespace DealNews\Constraints\Tests\Constraint;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\Constraint\Range;

class RangeTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider constraintData
     */
    public function testConstraint($value, $constraint, $expect) {
        $dc = new Constraint();

        $this->assertEquals(
            $expect,
            Range::filter($value, $constraint, $dc)
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
        $tests = [];
        foreach (["=","<",">",">=","<="] as $operator) {
            $tests[] = [
                [$operator, 1],
                [
                    "type"       => "range",
                    "constraint" => [
                        "type" => "integer"
                    ]
                ],
                [$operator, 1]
            ];
        }

        $tests[] = [
            ["between", 1, 10],
            [
                "type"       => "range",
                "constraint" => [
                    "type" => "integer"
                ]
            ],
            ["between", 1, 10],
        ];

        $tests[] = [
            [">", "midnight"],
            [
                "type"       => "range",
                "constraint" => [
                    "type" => "datetime"
                ]
            ],
            [">", date("Y-m-d")." 00:00:00"],
        ];

        $tests[] = [
            [">", null],
            [
                "type"       => "range"
            ],
            null
        ];

        $tests[] = [
            ["between", 1],
            [
                "type"       => "range"
            ],
            null
        ];

        $tests[] = [
            ["between", 1, null],
            [
                "type"       => "range"
            ],
            null
        ];

        $tests[] = [
            ["gte", 1],
            [
                "type"       => "range"
            ],
            null
        ];

        $tests[] = [
            ["=<", 1],
            [
                "type"       => "range"
            ],
            null
        ];

        $tests[] = [
            ["=>", 1],
            [
                "type"       => "range"
            ],
            null
        ];

        return $tests;
    }
}
