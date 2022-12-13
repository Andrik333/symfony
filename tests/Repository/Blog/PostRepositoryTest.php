<?php

namespace App\Tests\Repository\Blog;

use App\Entity\Blog\Post;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
{
    /**
     * @var \App\Repository\Blog\PostRepository
     */
    private $blogRepository;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->blogRepository = $entityManager->getRepository(Post::class);
    }

    public function testGetTags()
    {
        $tags = $this->blogRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagsWeight = $this->blogRepository->getTagWeights(
            array('php', 'code', 'code', 'symblog', 'blog')
        );

        $this->assertTrue(count($tagsWeight) > 1);

        $tagsWeight = $this->blogRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagsWeight) >= 1);

        $tagsWeight = $this->blogRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        // Test empty case
        $tagsWeight = $this->blogRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }
}