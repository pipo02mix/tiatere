<?php

namespace Tiatere\Application;

class ContactCommand
{
    private $fullname;
    private $email;
    private $query;

    public function __construct($fullname, $email, $query)
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->query = $query;
    }

    public function fullname()
    {
        return $this->fullname;
    }

    public function email()
    {
        return $this->email;
    }

    public function query()
    {
        return $this->query;
    }
}