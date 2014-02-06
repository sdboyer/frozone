<?php

namespace Frozone;

class LockableTraitTest extends \PHPUnit_Framework_Testcase {
    protected $stub;

    public function setUp() {
        $this->stub = new LockableStub();
        $this->stub->lock('key');
    }

    public function testInitiallyUnlocked() {
        $stub = new LockableStub();
        $this->assertFalse($stub->doDefault());
    }

    //public function testFreezeAlreadyFrozen() {
    //$this->assertTrue($this->stub->freeze());
    //}

    public function testDefaultException() {
        try {
            $this->stub->doDefault();
            $this->fail();
        }
        catch (LockedObjectException $e) {}
    }

    public function testMethodException() {
        try {
            $this->stub->doMethod();
            $this->fail();
        }
        catch (LockedObjectException $e) {
            $this->assertTrue((bool) strpos($e->getMessage(), 'LockableStub::doMethod'));
        }
    }

    public function testCustomException() {
        try {
            $this->stub->doCustom('foobar');
            $this->fail();
        }
        catch (LockedObjectException $e) {
            $this->assertEquals('foobar', $e->getMessage());
        }
    }

    /**
     * @expectedException Frozone\LockedObjectException
     */
    public function testUnlockWrongKey() {
        $this->stub->unlock('foo');
    }

    /**
     * @expectedException Frozone\LockedObjectException
     */
    public function testUnlockNotLocked() {
        $stub = new LockableStub();
        $stub->unlock('foo');
    }

    public function testUnlock() {
        $this->assertTrue($this->stub->unlock('key'));
    }
}

class LockableStub {
    use LockableTrait;

    public function doDefault() {
        $this->attemptWrite();
        return FALSE;
    }

    public function doMethod() {
        $this->attemptWriteWithMethod(__METHOD__);
        return FALSE;
    }

    public function doCustom($msg) {
        $this->attemptWriteWithMessage($msg);
        return FALSE;
    }
}
