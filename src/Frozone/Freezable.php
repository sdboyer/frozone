<?php

namespace Frozone;

/**
 * Interface for 'freezing,' which permanently locks an object's state.
 */
interface Freezable {

    /**
     * Permanently freeze this object, preventing future state changes.
     *
     * Freezing does not guarantee that objects contained within this frozen
     * object cannot be modified directly (thus indirectly modifying this object).
     *
     * The only guarantee is that this object's state cannot be changed directly.
     */
    public function freeze();

    /**
     * Indicates whether or not this object is frozen.
     *
     * @return bool
     */
    public function isFrozen();
}
