<?php

namespace App;

use \DateTime;

class Comment extends Post
{
    public function getTime()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->getAdded());
        return $date->format('H:i');
    }
}