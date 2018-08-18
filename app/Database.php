<?php

namespace App;

use \PDO;

class Database
{
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    private function dbConnect()
    {
        if($this->pdo === null) // Condition to avoid several connections to the database, one is enough
        {
            try
            {
                $pdo = new PDO('mysql:dbname=' . $this->db_name . ';charset=utf8;host=' . $this->db_host, $this->db_user, $this->db_pass);
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }

            $this->pdo = $pdo;
        }
        
        return $this->pdo;
    }

    public function countPosts()
    {
        $db = $this->dbConnect();
        $q = $db->query('SELECT COUNT(*) FROM posts');
        return $q->fetchColumn();
    }

    public function getAllPosts($class)
    {
        $db = $this->dbConnect();
        $q = $db->prepare('SELECT * FROM posts ORDER BY added DESC');
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
        $db = $this->dbConnect();
        $q = $db->prepare('SELECT * FROM posts ORDER BY added DESC LIMIT :lowLimit, 5');
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
        $db = $this->dbConnect();
        $q = $db->prepare('SELECT * FROM posts WHERE id = :id');
        $q->execute(array('id' => $id));
        $data = $q->fetch();

        $post = new $class($data);

        return $post;
    }

    public function getComments($class, $id)
    {
        $db = $this->dbConnect();
        $q = $db->prepare('SELECT * FROM comments WHERE post_id = :id ORDER BY added DESC');
        $q->execute(array('id' => $id));
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];

        foreach($data as $comment)
        {
            $comments[] = new $class($comment);
        }

        return $comments;
    }
}