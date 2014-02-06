<?php

namespace Frozone;

/**
 * Trait implementing Freezable.
 *
 * Make sure to begin any state-mutating method in the composed class with
 * a call to one of the $this->attemptWrite*() methods.
 */
trait FreezableTrait /* implements Freezable */ {

    /**
     * State flag indicating whether or not this object is frozen.
     *
     * Named oddly to help avoid naming collisions.
     *
     * @var bool
     */
    protected $_tfrozen = FALSE;

    public function freeze() {
        $this->_tfrozen = TRUE;
    }

    public function isFrozen() {
        return $this->_tfrozen;
    }

    /**
     * Checks if the object is frozen and throws an exception if it is.
     *
     * @throws FrozenObjectException
     */
    protected function attemptWrite() {
        if ($this->isFrozen()) {
            throw new FrozenObjectException(sprintf('State-changing method called on frozen instance of %s.', __CLASS__));
        }
    }

    /**
     * Checks if the object is frozen and throws an exception if it is.
     *
     * @param string $method
     *   The name of the method that was originally called. Your code should
     *   pass __METHOD__ to this.
     *
     * @throws FrozenObjectException
     */
    protected function attemptWriteWithMethod($method) {
        if ($this->isFrozen()) {
            throw new FrozenObjectException(sprintf('State-changing method %s::%s called on a frozen object instance.', __CLASS__, $method));
        }
    }

    /**
     * Checks if the object is frozen and throws an exception if it is.
     *
     * @param string $msg
     *   The message to use in the exception, if one is thrown.
     *
     * @throws FrozenObjectException
     */
    protected function attemptWriteWithMessage($msg) {
        if ($this->isFrozen()) {
            throw new FrozenObjectException($msg);
        }
    }
}
