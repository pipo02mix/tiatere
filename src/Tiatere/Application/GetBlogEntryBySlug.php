<?php

namespace Tiatere\Application;

use Tiatere\Domain\BlogPost;
use Tiatere\Domain\BlogPostRepository;

class GetBlogEntryBySlug
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * GetBlogEntryBySlug constructor.
     * @param BlogPostRepository $blogPostRepository
     */
    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function execute($slug)
    {
        $blogEntry = $this->blogPostRepository->findBySlug($slug);

        return $blogEntry;
    }
}
