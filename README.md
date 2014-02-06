# Frozone

![Frozone stops writes like a boss](frozone.jpg)
[![Build Status](https://travis-ci.org/sdboyer/frozone.png?branch=master)](https://travis-ci.org/sdboyer/frozone)
[![Coverage Status](https://coveralls.io/repos/sdboyer/frozone/badge.png)](https://coveralls.io/r/sdboyer/frozone)
[![Latest Stable Version](https://poser.pugx.org/sdboyer/frozone/v/stable.png)](https://packagist.org/packages/sdboyer/frozone)

Managing state sucks. Frozone is a simple set of interfaces and traits (so, PHP >=5.4) that implement some patterns to make it easier. It facilitates two cases:

* **Freezing**, in which an object with mutable state is irrevocably locked such that that state cannot be further mutated (from the outside).
* **Locking** in which an object with mutable state is locked with a key, and that state cannot be mutated until the same key is provided to unlock.

## Freezing

Freezing is a one-way operation, initiated by calling the ```freeze()``` method.

```php

use Frozone\Freezable;
use Frozone\FreezableTrait;

class Counter implements Freezable {
    use FreezableTrait;

    protected $callcount = 0;

    public function incrementAndEcho() {
        $this->attemptWrite();
        // or $this->attemptWriteWithMethod(__METHOD__);
        // or $this->attemptWriteWithMessage('What the exception will say if it's frozen');

        // now, your method's state-changing logic.
        echo ++$this->callcount;
    }

    public function justEcho() {
        echo $this->callcount;
    }
}

$counter = new Counter();
$counter->isFrozen(); // return FALSE
$counter->incrementAndEcho(); // prints '1'

$counter->freeze();
$counter->isFrozen(); // return TRUE

$counter->justEcho(); // prints '1'
$counter->incrementAndEcho(); // throws FrozenObjectException

```

## Locking

Locking is a reversible operation, initiated by calling the ```lock()``` method with a key, and reversed by calling ```unlock()``` with the same key.

It is useful if you need to send a mutable object around to other code, but want to restrict mutations for as long as the object is in that context.

```php

use Frozone\Lockable;
use Frozone\LockableTrait;

class Counter implements Lockable {
    use FreezableTrait;

    protected $callcount = 0;

    public function incrementAndEcho() {
        $this->attemptWrite();
        // or $this->attemptWriteWithMethod(__METHOD__);
        // or $this->attemptWriteWithMessage('What the exception will say if it's frozen');

        // now, your method's state-changing logic.
        echo ++$this->callcount;
    }

    public function justEcho() {
        echo $this->callcount;
    }
}

$counter = new Counter();
$counter->isLocked(); // return FALSE
$counter->incrementAndEcho(); // prints '1'

$key = mt_rand(1, 10000); // Use a key appropriate for your use case
$counter->lock($key);
$counter->isLocked(); // return TRUE

$counter->justEcho(); // prints '1'
$counter->incrementAndEcho(); // throws LockedObjectException
$counter->unlock('foo'); // throws LockedObjectException; wrong key
$counter->lock('foo'); // throws LockedObjectException; already locked

$counter->unlock($key);
$counter->isLocked(); // return FALSE
$counter->incrementAndEcho(); // prints '2'

```

## FAQ

**Reflection can change PHP object state, regardless of visibility. Doesn't that make this pointless?**

On a purely functional level, it absolutely does.

On an API design level, even if it's possible for calling code to override the protections provided by Frozone, if your object shouldn't be mutated in a specific context/after a certain point, it's still preferable to provide clear feedback to client code that that's the contract you're providing.

**Is object state really worth managing in PHP?**

In a lot of older PHP applications, no. Being that in the vast majority of PHP applications' execution environments, state is built from scratch on each request, object state tends to have a lot less meaning. But, as more and more modern PHP applications emerge, certain types of state are being effectively encapsulated in objects. In those cases, it's worth managing.


