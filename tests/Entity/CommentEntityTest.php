<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


/**
 * php bin/phpunit tests/Entity/CommentEntityTest.php
 */
class CommentEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $comment = new Comment();
        $comment
            ->setContent('contenu')
            ->setPost($post = new Post)
            ->setDate($date = new \DateTime())
            ->setUser($user = new User());

        $this->assertTrue($comment->getContent() === 'contenu');
        $this->assertTrue($comment->getPost() === $post);
        $this->assertTrue($comment->getDate() === $date);
        $this->assertTrue($comment->getUser() === $user);
    }

    public function testIsFalse()
    {
        $comment = new Comment();
        $comment
            ->setContent('contenu')
            ->setPost($post = new Post)
            ->setDate($date = new \DateTime())
            ->setUser($user = new User());


        $this->assertFalse($comment->getContent() === 'pas contenu');
        $this->assertFalse($comment->getPost() === false);
        $this->assertFalse($comment->getDate() === false);
        $this->assertFalse($comment->getUser() === false);
    }
}
