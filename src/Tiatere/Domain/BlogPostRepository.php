<?php

namespace Tiatere\Domain;

interface BlogPostRepository
{

    /**
     * @param $numberOfEntries
     * @return mixed
     */
    public function findLastEntries($numberOfEntries);

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);
}
