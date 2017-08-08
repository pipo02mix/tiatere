<?php

namespace Domain;

class BlogPost
{
    private $content;
    private $title;

    /**
     * BlogPost constructor.
     * @param $an_title
     * @param $an_content
     */
    public function __construct($an_title, $an_content)
    {
        $this->title = $an_title;
        $this->content = $an_content;
        $this->created_at = time();
    }

    public function title()
    {
        return $this->title;
    }

    public function content()
    {
        return $this->content;
    }

    public function createdAt()
    {
        return date('Y-m-d', $this->created_at);
    }
}
