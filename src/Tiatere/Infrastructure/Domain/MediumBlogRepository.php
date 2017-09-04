<?php

namespace Tiatere\Infrastructure\Domain;

use Tiatere\Domain\BlogPost;
use Tiatere\Domain\BlogPostRepository;
use PicoFeed\Reader\Reader;

class MediumBlogRepository implements BlogPostRepository
{
    private $reader;

    /**
     * MediumBlogRepository constructor.
     */
    function __construct()
    {
        $this->reader = new Reader();
    }

    /**
     * @param $numberOfEntries
     * @return mixed
     */
    public function findLastEntries($numberOfEntries) {
        $blogEntries = [];

        try {
            $resource = $this->reader->download('https://medium.com/feed/@tiatere');

            $parser = $this->reader->getParser(
              $resource->getUrl(),
              $resource->getContent(),
              $resource->getEncoding()
            );

            $feed = $parser->execute();
            foreach ($feed->getItems() as $item) {
                if (in_array('Post', $item->getCategories()) ||
                  in_array( 'javascript', $item->getCategories()) ||
                  in_array('erlang', $item->getCategories())
                ) {
                    $blogEntries[] = new BlogPost($item->getTitle(), $item->getContent(), $item->getDate());
                }
            }
        } catch (Exception $e) {
            return [];
        }

        return array_slice($blogEntries, 0, $numberOfEntries);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug) {

        try {
            $resource = $this->reader->download('https://medium.com/feed/@tiatere');

            $parser = $this->reader->getParser(
              $resource->getUrl(),
              $resource->getContent(),
              $resource->getEncoding()
            );

            $feed = $parser->execute();
            foreach ($feed->getItems() as $item) {
                $entry = new BlogPost($item->getTitle(), $item->getContent(), $item->getDate());
                if ($entry->slug() === $slug) {
                    return $entry;
                }
            }
        } catch (Exception $e) {
            return [];
        }

        return null;
    }
}
