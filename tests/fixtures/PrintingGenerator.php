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

namespace Hough\Generators\Test\fixtures;

use Hough\Generators\AbstractGenerator;

class PrintingGenerator extends AbstractGenerator
{
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
        if ($position === 0) {

            echo "I'm a printer!" . PHP_EOL;
        }

        echo $this->getLastValueSentIn() . PHP_EOL;

        return array(null);
    }
}

//function printer() {
//    echo "I'm printer!".PHP_EOL;
//    while (true) {
//        $string = yield;
//        echo $string.PHP_EOL;
//    }
//}
