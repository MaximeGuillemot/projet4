<?php

namespace App\DB;

use \PDO;

class PostDB
{
    private $pdo;

    public function __construct(DBConnect $db)
    {
        if(!empty($db))
        {
            $this->pdo = $db->getPDO();
        }
    }

    public function countPosts()
    {
        $q = $this->pdo->query('SELECT COUNT(*) FROM posts');
        return $q->fetchColumn();
    }

    public function countComments()
    {
        $q = $this->pdo->query('SELECT COUNT(*) FROM comments');
        return $q->fetchColumn();
    }

    public function getAllPosts($class)
    {
        $q = $this->pdo->prepare('SELECT * FROM posts ORDER BY added DESC');
        $q->execute();
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];

        foreach($data as $post)
        {
            $posts[] = new $class($post);
        }

        return $posts;
    }

    public function getPosts($class, $lowLimit = 0)
    {
        $q = $this->pdo->prepare('SELECT * FROM posts ORDER BY added DESC LIMIT :lowLimit, 5');
        $q->bindValue(':lowLimit', intval($lowLimit), PDO::PARAM_INT);
        $q->execute();
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];

        foreach($data as $post)
        {
            $posts[] = new $class($post);
        }

        return $posts;
    }

    public function getPost($class, $id)
    {
        $q = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $q->execute(array('id' => $id));
        $data = $q->fetch();

        $post = new $class($data);

        return $post;
    }

    public function getAllComments($class, $id)
    {
        $q = $this->pdo->prepare('SELECT * FROM comments WHERE post_id = :id ORDER BY added DESC');
        $q->execute(array('id' => $id));
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];

        foreach($data as $comment)
        {
            $comments[] = new $class($comment);
        }

        return $comments;
    }

    public function getComments($class, $id, $lowLimit = 0)
    {
        $q = $this->pdo->prepare('SELECT * FROM comments WHERE post_id = :id ORDER BY added DESC LIMIT :lowLimit, 5');
        $q->bindValue(':id', intval($id), PDO::PARAM_INT);
        $q->bindValue(':lowLimit', intval($lowLimit), PDO::PARAM_INT);
        $q->execute();
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];

        foreach($data as $comment)
        {
            $comments[] = new $class($comment);
        }

        return $comments;
    }
}