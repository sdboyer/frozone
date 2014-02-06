<?php

namespace Frozone;

/**
 * Parent to exceptions thrown when a write-protected operation is attempted
 * on a frozen object.
 */
abstract class ObjectStateException extends \LogicException {}
