<?php

namespace Frozone;

/**
 * Interface for 'locking', which locks an object's state using a key.
 *
 * State can only be unlocked (that is, state can be mutated) by calling the
 * unlock method with the same key.
 */
interface Lockable {

    /**
     * Locks this object using the provided key.
     *
     * The object can only be unlocked by providing the same key.
     *
     * @param mixed $key
     *   The key used to lock the object.
     *
     * @throws LockedObjectException
     *   Thrown if the collector is already locked.
     */
    public function lock($key);

    /**
     * Attempts to unlock the collector with the provided key.
     *
     * Key comparison is done using the identity operator (===).
     *
     * @param mixed $key
     *   The key with which to unlock the object.
     *
     * @throws LockedObjectException
     *   Thrown if the incorrect key is provided, or if the collector is not
     *   locked.
     */
    public function unlock($key);

    /**
     * Indicates whether this collector is currently locked.
     *
     * @return bool
     */
    public function isLocked();
}
