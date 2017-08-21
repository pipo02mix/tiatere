<?php

namespace Tiatere\Domain;

class BlogPost
{
    private $content;
    private $title;
    private $slug;

    /** @var \DateTime $an_created_at */
    private $created_at;

    /**
     * BlogPost constructor.
     * @param $an_title
     * @param $an_content
     * @param $an_created_at
     */
    public function __construct($an_title, $an_content, $an_created_at = null)
    {
        $this->title = $an_title;
        $this->content = $this->sanitizeContent($an_content);
        $this->slug = $this->slugify($an_title);
        $this->created_at = $an_created_at ? $an_created_at : new \DateTime('now');
    }

    public function title()
    {
        return $this->title;
    }

    public function content()
    {
        return $this->content;
    } 
    
    public function slug()
    {
        return $this->slug;
    }


    public function createdAt()
    {
        return $this->created_at;
    }

    private function sanitizeContent($content)
    {
        // Improve code areas
        $content = preg_replace('~<pre>+~u', '<pre class="code"><code>', $content);
        $content = preg_replace('~</pre>+~u', '</code></pre>', $content);

        return $content;
    }

    private function slugify($title)
    {
        // replace non letter or digits by -
        $title = preg_replace('~[^\pL\d]+~u', '-', $title);

        // transliterate
        $title = iconv('utf-8', 'us-ascii//TRANSLIT', $title);

        // remove unwanted characters
        $title = preg_replace('~[^-\w]+~', '', $title);

        // trim
        $title = trim($title, '-');

        // remove duplicate -
        $title = preg_replace('~-+~', '-', $title);

        // lowercase
        $title = strtolower($title);

        if (empty($title)) {
            return 'n-a';
        }

        return $title;
    }
}
