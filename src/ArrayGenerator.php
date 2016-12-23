<?php
/**
 * Copyright 2016 Eric D. Hough (https://github.com/ehough)
 *
 * This file is part of ehough/generators (https://github.com/ehough/generators)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Hough\Generators;

class ArrayGenerator extends AbstractGenerator
{
    /**
     * @var array
     */
    private $_array;

    public function __construct(array $array)
    {
        $this->_array = $array;
    }

    /**
     * @param int $position
     *
     * @return null|mixed
     */
    protected function resume($position)
    {
        if ($position === count($this->_array)) {

            return null;
        }

        return array($this->_array[$position]);
    }
}
