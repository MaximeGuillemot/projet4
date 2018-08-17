<?php

namespace App;

class Article extends Post
{
    protected $title;

    protected function setTitle($title)
    {
        if(!empty($title) && (string) $title === $title)
        {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }
}