<?php

namespace DealNews\Constraints\Tests;

use \DealNews\Constraints\Constraint;
use \DealNews\Constraints\ConstraintException;

class ConstraintTest extends \PHPUnit\Framework\TestCase {
    public function testMultiByteStrings() {
        $input = 'foobar😀';
        $dc    = Constraint::init();
        $value = $dc->check(
            $input,
            [
                'type' => 'string',
                'max'  => 7,
            ]
        );
        $this->assertEquals($input, $value);
    }

    public function testExceptionMethods() {
        $dc = new ConstraintException(
            'some value',
            'expected',
            'example'
        );
        $this->assertEquals(
            'expected',
            $dc->getExpected()
        );
        $this->assertEquals(
            'example',
            $dc->getExample()
        );
    }

    /**
     * @dataProvider exceptionData
     */
    public function testExceptions($value, $constraint) {
        $this->expectException(ConstraintException::class);
        $dc = new Constraint();
        $dc->check($value, $constraint);
    }

    public function testInvalidType() {
        $this->expectException(\LogicException::class);
        $dc = new Constraint();
        $dc->check(0, ['type' => 'foo']);
    }

    public function testConstraintValidation() {
        $valid_constraint = [
            'type'        => 'string',
            'default'     => '',
            'is_nullable' => false,
            'read_only'   => false,
            'constraint'  => [
                'type' => 'integer',
            ],
            'min'            => 0,
            'max'            => 10,
            'allowed_values' => ['foo', 'bar'],
            'pattern'        => '',
        ];
        $dc = new Constraint();
        $this->assertTrue(
            $dc->validateConstraint($valid_constraint)
        );
    }

    public function testConstraintValidationExceptionTypes() {
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(2);
        $dc = new Constraint();
        $this->assertTrue(
            $dc->validateConstraint(['type' => 1])
        );
    }

    public function testConstraintValidationExceptionKeys() {
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(1);
        $dc = new Constraint();
        $this->assertTrue(
            $dc->validateConstraint(['foo' => 1])
        );
    }

    public function testConstraintValidationExtraValues() {
        $dc = new Constraint(['foo' => 0]);
        $this->assertTrue(
            $dc->validateConstraint(['foo' => 1])
        );
    }

    public function testConstraintValidationExceptionExtraKeys() {
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(2);
        $dc = new Constraint(['foo' => 0]);
        $this->assertTrue(
            $dc->validateConstraint(['foo' => ''])
        );
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess($value, $constraint, $expect) {
        $_SERVER['DN_DEBUG'] = true;
        $dc                  = new Constraint();
        if (is_object($expect)) {
            $this->assertEquals(
                $expect,
                $dc->check($value, $constraint)
            );
        } else {
            $this->assertSame(
                $expect,
                $dc->check($value, $constraint)
            );
        }
    }

    public function successData() {
        $dt = new \DateTime();

        return [
            [
                'foo',
                ['type' => 'string'],
                'foo',
            ],
            [
                'foo',
                [
                    'type'    => 'string',
                    'pattern' => '/^f/',
                ],
                'foo',
            ],
            [
                1,
                ['type' => 'string'],
                '1',
            ],
            [
                'Yes',
                [
                    'type'           => 'string',
                    'allowed_values' => [
                        'Yes',
                        'No',
                    ],
                ],
                'Yes',
            ],
            [
                'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                [
                    'type' => 'string',
                    'max'  => 50,
                    'min'  => 10,
                ],
                'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            ],
            [
                1,
                ['type' => 'integer'],
                1,
            ],
            [
                1,
                [
                    'type' => 'integer',
                    'min'  => 0,
                    'max'  => 10,
                ],
                1,
            ],
            [
                '1',
                ['type' => 'integer'],
                1,
            ],
            [
                1,
                ['type' => 'double'],
                1.0,
            ],
            [
                '1',
                ['type' => 'double'],
                1.0,
            ],
            [
                1,
                [
                    'type' => 'double',
                    'min'  => 0,
                    'max'  => 10,
                ],
                1.0,
            ],
            [
                '1',
                ['type' => 'boolean'],
                true,
            ],
            [
                true,
                ['type' => 'boolean'],
                true,
            ],
            [
                'true',
                ['type' => 'boolean'],
                true,
            ],

            // Testing integration with the abstract types
            // Those types are tested in files of their own
            [
                'http://www.example.com/',
                ['type' => 'url'],
                'http://www.example.com/',
            ],

            // testing class checking
            [
                $dt,
                ['type' => "\DateTime"],
                $dt,
            ],
            [
                null,
                ['type' => "\DateTime"],
                null,
            ],
            // testing is_nullable and default
            [
                null,
                [
                    'type'        => 'string',
                    'pattern'     => '/^f/',
                    'is_nullable' => true,
                ],
                null,
            ],
            [
                null,
                [
                    'type'        => 'string',
                    'pattern'     => '/^f/',
                    'is_nullable' => false,
                    'default'     => 'foo',
                ],
                'foo',
            ],
            [
                '123',
                [
                    'type'      => 'integer',
                    'read_only' => true,
                ],
                '123',
            ],
            [
                [1,2,3],
                [
                    'type'       => 'array',
                    'constraint' => [
                        'type' => 'integer',
                    ],
                ],
                [1,2,3]
            ],
            [
                [
                    'https://www.example.com',
                    'https://www.example.com  ',
                ],
                [
                    'type'       => 'array',
                    'constraint' => [
                        'type' => 'url',
                    ],
                ],
                [
                    'https://www.example.com',
                    'https://www.example.com',
                ],
            ],
            [
                new \ArrayObject(),
                [
                    'type' => 'array',
                ],
                [],
            ],
            [
                [],
                [
                    'type' => "\ArrayObject",
                ],
                new \ArrayObject,
            ],
        ];
    }

    public function exceptionData() {
        return [
            [
                1000,
                [
                    'type' => 'integer',
                    'max'  => 100,
                ],
            ],
            [
                'foo',
                ['type' => 'integer'],
            ],
            [
                1,
                [
                    'type' => 'integer',
                    'min'  => 5,
                    'max'  => 10,
                ],
            ],
            [
                100,
                [
                    'type' => 'integer',
                    'min'  => 5,
                    'max'  => 10,
                ],
            ],
            [
                '1,000',
                ['type' => 'double'],
            ],
            [
                1,
                [
                    'type' => 'double',
                    'min'  => 5,
                    'max'  => 10,
                ],
            ],
            [
                100,
                [
                    'type' => 'double',
                    'min'  => 5,
                    'max'  => 10,
                ],
            ],
            [
                [],
                ['type' => 'string'],
            ],
            [
                'Bob',
                [
                    'type'           => 'string',
                    'allowed_values' => [
                        'Yes',
                        'No',
                    ],
                ],
            ],
            [
                'bar',
                [
                    'type'    => 'string',
                    'pattern' => '/^f/',
                ],
            ],
            [
                'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                [
                    'type' => 'string',
                    'max'  => 10,
                ],
            ],
            [
                '',
                [
                    'type' => 'string',
                    'min'  => 10,
                ],
            ],
            [
                1,
                ['type' => 'array'],
            ],
            [
                'Maybe',
                ['type' => 'boolean'],
            ],
            // Testing integration with the abstract types
            // Those types are tested in files of their own
            [
                'asdf',
                ['type' => 'url'],
            ],
            // testing class checking
            [
                'foo',
                ['type' => "\DateTime"],
            ],
            // testing is_nullable and missing default
            [
                null,
                [
                    'type'        => 'string',
                    'pattern'     => '/^f/',
                    'is_nullable' => false,
                ],
                '',
            ],
            [
                [[1],2,3],
                [
                    'type'       => 'array',
                    'constraint' => [
                        'type' => 'integer',
                    ],
                ]
            ],

        ];
    }
}
