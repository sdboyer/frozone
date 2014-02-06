<?php

namespace Frozone;

/**
 * Trait implementing Lockable.
 *
 * Make sure to begin any state-mutating method in the composed class with
 * a call to one of the $this->attemptWrite*() methods.
 */
trait LockableTrait /* implements Lockable */ {

    /**
     * Flag indicating whether or not the object is locked.
     *
     * Named weirdly to ensure no naming conflicts.
     *
     * @var bool
     */
    protected $_tlocked = FALSE;

    /**
     * The data key with which the lock was set.
     *
     * An identical value (===) must be provided to unlock the object.
     *
     * There are no type restrictions.
     *
     * Named weirdly to ensure no naming conflicts.
     *
     * @var mixed
     */
    protected $_tlockKey;

    public function lock($key) {
        $this->attemptWrite(__METHOD__);

        $this->_tlocked = TRUE;
        $this->_tlockKey = $key;
        return TRUE;
    }

    public function unlock($key) {
        if (!$this->isLocked()) {
            throw new LockedObjectException('Object is not locked', E_WARNING);
        }

        if ($this->_tlockKey !== $key) {
            throw new LockedObjectException('Attempted to unlock object with incorrect key.', E_WARNING);
        }

        $this->_tlocked = FALSE;
        $this->_tlockKey = NULL;
        return TRUE;
    }

    public function isLocked() {
        return $this->_tlocked;
    }

    /**
     * Checks if the object is locked and throws an exception if it is.
     *
     * @throws LockedObjectException
     */
    protected function attemptWrite() {
        if ($this->isLocked()) {
            throw new LockedObjectException(sprintf('State-changing method called on locked instance of %s.', __CLASS__));
        }
    }

    /**
     * Checks if the object is locked and throws an exception if it is.
     *
     * @param string $method
     *   The name of the method that was originally called. Your code should
     *   pass __METHOD__ to this.
     *
     * @throws LockedObjectException
     */
    protected function attemptWriteWithMethod($method) {
        if ($this->isLocked()) {
            throw new LockedObjectException(sprintf('State-changing method %s::%s called on a locked object instance.', __CLASS__, $method));
        }
    }

    /**
     * Checks if the object is locked and throws an exception if it is.
     *
     * @param string $msg
     *   The message to use in the exception, if one is thrown.
     *
     * @throws LockedObjectException
     */
    protected function attemptWriteWithMessage($msg) {
        if ($this->isLocked()) {
            throw new LockedObjectException($msg);
        }
    }
}
