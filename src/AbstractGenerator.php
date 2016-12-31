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

namespace Hough\Generators;

abstract class AbstractGenerator implements \Iterator
{
    /**
     * @var null|mixed
     */
    private $_lastValueSentIn;

    /**
     * @var int
     */
    private $_position = 0;

    /**
     * @var null|mixed
     */
    private $_lastYieldedValue;

    /**
     * @var null|string|int
     */
    private $_lastYieldedKey;

    /**
     * @var int
     */
    private $_lastPositionExecuted;

    /**
     * @var int
     */
    private $_positionsExecutedCount = 0;

    /**
     * @var bool
     */
    private $_sendInvokedAtLeastOnce = false;

    /**
     * @var bool
     */
    private $_hasMoreToExecute = true;

    /**
     * Get the yielded value.
     *
     * @return mixed|null the yielded value
     */
    final public function current()
    {
        if (!$this->valid()) {

            return null;
        }

        /*
         * Multiple calls to current() should be idempotent
         */
        if ($this->_lastPositionExecuted !== $this->_position) {

            $this->runToNextYieldStatement();
        }

        return $this->valid() ? $this->getLastYieldedValue() : null;
    }

    /**
     * Get the return value of a generator.
     *
     * @return mixed the generator's return value once it has finished executing
     */
    public function getReturn()
    {
        //override point
        throw new \RuntimeException('Cannot get return value of a generator that hasn\'t returned');
    }

    /**
     * Get the yielded key.
     *
     * @return mixed the yielded key
     */
    final public function key()
    {
        /*
         * Run to the first yield statement, if we haven't already.
         */
        $this->current();

        return $this->valid() ? $this->_lastYieldedKey : null;
    }

    /**
     * Resume execution of the generator.
     *
     * @return void
     */
    final public function next()
    {
        $this->send(null);
    }

    /**
     * Rewind the iterator.
     *
     * @return void
     */
    final public function rewind()
    {
        if ($this->_sendInvokedAtLeastOnce) {

            throw new \RuntimeException('Cannot rewind a generator that was already run');
        }

        /*
         * Run to the first yield statement, if we haven't already.
         */
        $this->current();
    }

    /**
     * Send a value to the generator.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    final public function send($value)
    {
        $this->_lastValueSentIn        = $value;
        $this->_sendInvokedAtLeastOnce = true;

        /*
         * If we've already ran to the first yield statement (from rewind() or key(), for instance), we need
         * to try to move to the next position;
         */
        if ($this->_positionsExecutedCount > 0) {

            $this->_position++;
        }

        return $this->current();
    }

    final public function __call($name, $args)
    {
        if ($name === 'throw') {

            /*
             * If the generator is already closed when this method is invoked, the exception will be thrown in the
             * caller's context instead.
             */
            if (!$this->valid()) {

                throw $args[0];
            }

            return $this->onExceptionThrownIn($args[0], $this->_position);
        }

        throw new \RuntimeException('Cannot dynamically invoke method ' . $name . '()');
    }

    /**
     * Check if the iterator has been closed.
     *
     * @return bool False if the iterator has been closed. Otherwise returns true.
     */
    final public function valid()
    {
        return $this->_hasMoreToExecute;
    }

    final public function __invoke()
    {
        return $this;
    }

    /**
     * @return null|mixed
     */
    final protected function getLastValueSentIn()
    {
        return $this->_lastValueSentIn;
    }

    /**
     * @return null|mixed
     */
    final protected function getLastYieldedValue()
    {
        return $this->_lastYieldedValue;
    }

    /**
     * An exception was thrown into the generator from the calling context.
     *
     * @param \Exception $e        the exception thrown in
     * @param int        $position the current position of the generator
     *
     * @throws \Exception
     */
    protected function onExceptionThrownIn(\Exception $e, $position)
    {
        //override point
        throw $e;
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
    abstract protected function resume($position);

    private function runToNextYieldStatement()
    {
        $executionResult             = $this->resume($this->_position);
        $this->_lastPositionExecuted = $this->_position;

        $this->_positionsExecutedCount++;

        /*
         * Nothing more to do.
         */
        if ($executionResult === null) {

            $this->_hasMoreToExecute = false;
            $this->_lastYieldedValue = null;
            $this->_lastYieldedKey   = null;

            return;
        }

        if (!is_array($executionResult) || count($executionResult) === 0 || count($executionResult) >= 3) {

            throw new \LogicException('executePosition() must return an array of up to two elements. If two elements, the first is the yielded key and the second is the yielded value. If one element, it is considered to be the yielded value.');
        }

        if (count($executionResult) === 2) {

            $this->_lastYieldedKey   = $executionResult[0];
            $this->_lastYieldedValue = $executionResult[1];

        } else {

            $this->_lastYieldedKey   = $this->_position;
            $this->_lastYieldedValue = $executionResult[0];
        }
    }
}
