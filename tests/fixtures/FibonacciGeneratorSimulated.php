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

namespace Hough\Generators\Test\fixtures;

use Hough\Generators\AbstractGenerator;

class FibonacciGeneratorSimulated extends AbstractGenerator
{
    private $i;
    private $k;

    public function __construct()
    {
        $this->i = 0;
        $this->k = 1;
    }

    /**
     * Resume execution of the generator.
     *
     * @param int $position the zero-based "position" of execution
     *
     * @return null|array Return null to indicate completion. Otherwise return an array of up to two elements. If two
     *                    elements in the array, the first will be considered to be the yielded key and the second the
     *                    yielded value. If one element in the array, it will be considered to be the yielded value and
     *                    the yielded key will be $position.
     */
    protected function resume($position)
    {
        if ($position !== 0) {

            $this->k = $this->i + $this->k;
            $this->i = $this->k - $this->i;
        }

        return array($this->k);
    }
}

//$i = 0;
//$k = 1;
//yield $k;
//while(true)
//{
//    $k = $i + $k;
//    $i = $k - $i;
//    yield $k;
//}
