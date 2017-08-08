<?php

namespace spec\Application;

use Application\GetLastBlogEntries;
use Domain\BlogPost;
use Domain\BlogPostRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GetLastBlogEntriesSpec extends ObjectBehavior
{
    /**
     * @param BlogPostRepository $blogPostRepository
     */
    function it_gets_last_n_entries(BlogPostRepository $blogPostRepository)
    {
        $blogEntry = new BlogPost( 'title1', 'content1');

        $blogPostRepository->findLastEntries(3)->willReturn($blogEntry);
        $this->beConstructedWith($blogPostRepository);

        $this->execute(3)
          ->shouldHaveCount(3);
        $this->execute(3)
          ->shouldHaveBlogPost($blogEntry);
    }

    public function getMatchers()
    {
        return [
          'haveBlogPost' => function ($items, $blogPost) {
              foreach ($items as $item) {
                  if ($item == $blogPost) {
                      return true;
                  }
              }
              return false;
          }
        ];
    }
}
