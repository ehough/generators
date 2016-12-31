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

use Hough\Generators\Test\fixtures\PrintingGenerator;

/**
 * @covers \Hough\Generators\AbstractGenerator<extended>
 */
class PrintingGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PrintingGenerator
     */
    private $_sut;

    public function setUp()
    {
        $this->_sut = new PrintingGenerator();
    }

    public function testOutput()
    {
        $this->expectOutputString("I'm a printer!\nHi\n");
        $this->_sut->send('Hi');
    }
}
