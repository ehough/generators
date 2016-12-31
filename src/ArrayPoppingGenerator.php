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

namespace Hough\Generators;

class ArrayPoppingGenerator extends AbstractGenerator
{
    private $_array;

    public function __construct(array &$incoming)
    {
        $this->_array = &$incoming;
    }

    public function resume($position)
    {
        if (count($this->_array) === 0) {

            return null;
        }

        return array(array_pop($this->_array));
    }
}
