<?php

namespace App;

class Post
{
    private $_id;
    private $_title;
    private $_content;
    private $_author;
    private $_added;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->hydrate($data);
        }
    }

    private function hydrate($data) // Initiates attributes with data retrieved from DB
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

    private function setId($id)
    {
        if(!empty($id))
        {
            $this->_id = (int) $id; // MySQL returns a string instead of an integer, hence the cast
        }
    }

    private function setTitle($title)
    {
        if(!empty($title) && (string) $title === $title)
        {
            $this->_title = $title;
        }
    }

    private function setContent($content)
    {
        if(!empty($content) && (string) $content === $content)
        {
            $this->_content = $content;
        }
    }

    private function setAuthor($author)
    {
        if(!empty($author) && (string) $author === $author)
        {
            $this->_author = $author;
        }
    }

    private function setAdded($added)
    {
        if(!empty($added) && (string) $added === $added)
        {
            $this->_added = $added;
        }
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function getExcerpt()
    {
        return substr($this->getContent(), 0, 300) . '...';
    }

    public function getId()
    {
        return $this->_id;
    }
}