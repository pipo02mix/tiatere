<?php

namespace Domain;

interface BlogPostRepository
{

    /**
     * @param $numberOfEntries
     * @return mixed
     */
    public function findLastEntries($numberOfEntries);
}
