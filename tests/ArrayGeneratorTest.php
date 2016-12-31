<?php
/*
 * Copyright 2016 - 2017 Eric D. Hough (https://github.com/ehough)
 *
 * This file is part of ehough/generators (https://github.com/ehough/generators)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Hough\Generators\Test;

use Hough\Generators\ArrayGenerator;

/**
 * @covers \Hough\Generators\ArrayGenerator<extended>
 */
class ArrayGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayGenerator
     */
    private $_sut;

    public function setUp()
    {
        $this->_sut = new ArrayGenerator(
            array(1, 2, 'three')
        );
    }

    public function testThrow()
    {
        $this->setExpectedException('\RuntimeException', 'foobar');

        /* @noinspection PhpUndefinedMethodInspection */
        $this->_sut->throw(new \RuntimeException('foobar'));
    }

    public function testGetReturn()
    {
        $this->setExpectedException('\RuntimeException', 'Cannot get return value of a generator that hasn\'t returned');

        $this->_sut->getReturn();
    }

    public function testRewind()
    {
        $this->_sut->rewind();
        $this->_sut->rewind();

        $sut = $this->_sut;
        $this->assertSame($this->_sut, $sut());
        $this->assertSame($this->_sut, $sut());

        $this->assertTrue($this->_sut->valid());

        $result = array();
        foreach ($this->_sut as $val) {

            $result[] = $val;
        }

        $this->assertEquals(array(1, 2, 'three'), $result);
        $this->assertFalse($this->_sut->valid());

        try {

            $this->_sut->rewind();
            $this->fail('Should have thrown exception');

        } catch (\RuntimeException $e) {

            $this->assertEquals('Cannot rewind a generator that was already run', $e->getMessage());
        }

        $this->assertNull($this->_sut->current());
        $this->assertNull($this->_sut->key());
    }
}
