<?php

namespace Frozone;

/**
 * Exception thrown when a write-protected operation is attempted on a locked
 * object.
 */
class LockedObjectException extends ObjectStateException {}

