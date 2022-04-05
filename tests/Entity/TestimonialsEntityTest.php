<?php

namespace App\Tests\Entity;

use App\Entity\Testimonials;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/TestimonialsEntityTest.php
 */
class TestimonialsEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $testimonial = new Testimonials();
        $testimonial
            ->setPseudo('pseudo')
            ->setContent('content');

        $this->assertTrue($testimonial->getPseudo() === 'pseudo');
        $this->assertTrue($testimonial->getContent() === 'content');
    }

    public function testIsFalse()
    {
        $testimonial = new Testimonials();
        $testimonial
            ->setPseudo('pseudo')
            ->setContent('content');

        $this->assertFalse($testimonial->getPseudo() === false);
        $this->assertFalse($testimonial->getContent() === 'pas content');
    }
}
