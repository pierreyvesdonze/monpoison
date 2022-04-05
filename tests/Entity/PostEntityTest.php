<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/PostEntityTest.php
 */
class PostEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $post = new Post();
        $post
            ->setTitle('titre')
            ->setContent('content')
            ->setDate($date = new \DateTime())
            ->addComment($comment = new Comment)
            ->setIsPublished(false)
            ->setSlug('new_slug_test');

        $this->assertTrue($post->getTitle() === 'titre');
        $this->assertTrue($post->getContent() === 'content');
        $this->assertTrue($post->getDate() === $date);
        $this->assertTrue($post->getIsPublished() === false);
        $this->assertTrue($post->getSlug() === 'new_slug_test');
    }

    public function testIsFalse()
    {
        $post = new Post();
        $post
            ->setTitle('titre')
            ->setContent('content')
            ->setDate($date = new \DateTime())
            ->addComment($comment = new Comment)
            ->setIsPublished(false)
            ->setSlug('new_slug_test');

        $this->assertFalse($post->getTitle() === false);
        $this->assertFalse($post->getContent() === 'no content');
        $this->assertFalse($post->getDate() === false);
        $this->assertFalse($post->getIsPublished() === true);
        $this->assertFalse($post->getSlug() === false);
    }
}
