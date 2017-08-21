<?php

namespace Tiatere\Application;

use Tiatere\Domain\BlogPost;
use Tiatere\Domain\BlogPostRepository;

class GetLastBlogEntries
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * GetLastBlogEntries constructor.
     * @param BlogPostRepository $blogPostRepository
     */
    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function execute($numberOfEntries)
    {
        $blogEntries = $this->blogPostRepository->findLastEntries($numberOfEntries);

        return $blogEntries;
    }
}
