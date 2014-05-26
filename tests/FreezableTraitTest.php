<?php

namespace Frozone;

class FreezableTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $stub;

    public function setUp()
    {
        $this->stub = new FreezableStub();
        $this->stub->freeze();
    }

    public function testInitiallyNotFrozen()
    {
        $stub = new FreezableStub();
        $this->assertFalse($stub->doDefault());
        $this->assertFalse($stub->doMethod());
        $this->assertFalse($stub->doCustom('foo'));
    }

    public function testFreezeIsIdempotent()
    {
        $this->assertTrue($this->stub->isFrozen());
        $this->stub->freeze();
        $this->assertTrue($this->stub->isFrozen());
    }

    public function testDefaultException()
    {
        try {
            $this->stub->doDefault();
            $this->fail();
        } catch (FrozenObjectException $e) {
            // no op
        }
    }

    public function testMethodException()
    {
        try {
            $this->stub->doMethod();
            $this->fail();
        } catch (FrozenObjectException $e) {
            $this->assertTrue((bool) strpos($e->getMessage(), 'FreezableStub::doMethod'));
        }
    }

    public function testCustomException()
    {
        try {
            $this->stub->doCustom('foobar');
            $this->fail();
        }
        catch (FrozenObjectException $e) {
            $this->assertEquals('foobar', $e->getMessage());
        }
    }
}

class FreezableStub {
    use FreezableTrait;

    public function doDefault()
    {
        $this->attemptWrite();

        return false;
    }

    public function doMethod()
    {
        $this->attemptWriteWithMethod(__METHOD__);

        return false;
    }

    public function doCustom($msg)
    {
        $this->attemptWriteWithMessage($msg);

        return false;
    }
}
