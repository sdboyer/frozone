<?php

namespace Frozone;

/**
 * Exception thrown when a write-protected operation is attempted on a frozen
 * object.
 */
class FrozenObjectException extends ObjectStateException {}
