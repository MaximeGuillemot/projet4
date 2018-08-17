<?php

namespace App;

class Post
{
    protected $id;
    protected $content;
    protected $author;
    protected $added;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->hydrate($data);
        }
    }

    protected function hydrate($data) // Initiates attributes with data retrieved from DB
    {
        foreach($data as $k => $v) // For each DB column, use the corresponding setter to set attribute value
        {
            $method = 'set' . ucfirst($k);

            if(is_callable([$this, $method])) // Check whether the setter actually exists
            {
                $this->$method($v); // Call the dynamic method : use "$method" and not just "method" to refer to the variable and not an attribute
            }
        }
    }

    protected function setId($id)
    {
        if(!empty($id))
        {
            $this->id = (int) $id; // MySQL returns a string instead of an integer, hence the cast
        }
    }

    protected function setContent($content)
    {
        if(!empty($content) && (string) $content === $content)
        {
            $this->content = $content;
        }
    }

    protected function setAuthor($author)
    {
        if(!empty($author) && (string) $author === $author)
        {
            $this->author = $author;
        }
    }

    protected function setAdded($added)
    {
        if(!empty($added) && (string) $added === $added)
        {
            $this->added = $added;
        }
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getExcerpt()
    {
        return substr($this->getContent(), 0, 300) . '...';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}