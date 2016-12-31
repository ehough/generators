<?php
/*
 * Copyright 2016 Eric D. Hough (https://github.com/ehough)
 *
 * This file is part of ehough/generators (https://github.com/ehough/generators)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Hough\Generators\Test;

use Hough\Generators\ArrayPoppingGenerator;

/**
 * @covers \Hough\Generators\ArrayPoppingGenerator<extended>
 */
class ArrayPoppingGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayPoppingGenerator
     */
    private $_sut;

    /**
     * @var array
     */
    private $_array;

    public function setUp()
    {
        $this->_array = array(1, 2, 'three');

        $this->_sut = new ArrayPoppingGenerator($this->_array);
    }

    public function testBasics()
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

        $this->assertEquals(array('three', 2, 1), $result);
        $this->assertFalse($this->_sut->valid());
        $this->assertEmpty($this->_array);

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
