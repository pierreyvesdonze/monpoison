<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Tests.php
 */
class Tests extends TestCase {

    public function testToTest () {
        $this->assertEquals(2, 1+1);
    }
}