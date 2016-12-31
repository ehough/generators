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

return function ()
{
    $i = 0;
    $k = 1;
    yield $k;
    while(true)
    {
        $k = $i + $k;
        $i = $k - $i;
        yield $k;
    }
};
