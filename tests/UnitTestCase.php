<?php

namespace App\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    protected function mock($className) : MockObject
    {
        return $this->createMock($className);
    }
}