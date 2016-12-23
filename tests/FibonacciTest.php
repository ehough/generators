<?php
/**
 * Copyright 2016 Eric Hough (https://github.com/ehough)
 *
 * This file is part of ehough/generators (https://github.com/ehough/generators)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Hough\Generators\Test;

use Hough\Generators\Test\fixtures\FibonacciGeneratorSimulated;

class FibonacciTest extends \PHPUnit_Framework_TestCase
{
    public function testFibonacci()
    {
        if (version_compare(PHP_VERSION, '5.5', '<')) {

            $this->markTestSkipped('PHP ' . PHP_VERSION);
            return;
        }

        $real = require __DIR__ . '/fixtures/FibonacciGeneratorReal.php';
        $this->assertEquals(
            $this->getOutput($real()),
            $this->getOutput(new FibonacciGeneratorSimulated())
        );
    }

    private function getOutput($generator)
    {
        ob_start();

        $y = 0;

        foreach($generator as $fibonacci)
        {
            echo $fibonacci . "\n";
            $y++;
            if($y > 30)
            {
                break;
            }
        }

        return ob_end_clean();
    }
}
