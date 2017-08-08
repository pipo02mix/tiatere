<?php

namespace Application;

use Domain\BlogPost;
use Domain\BlogPostRepository;

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
        $blogEntries = [];
        foreach (range(1, $numberOfEntries) as $index) {
            $blogEntries[] = new BlogPost('title'.$index, 'content'.$index);
        }

        return $blogEntries;
    }
}
