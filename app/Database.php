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

    public function getPosts()
    {
        $db = $this->dbConnect();
        $q = $db->prepare('SELECT * FROM posts');
        $q-> execute();
        $posts = $q->fetchAll(PDO::FETCH_OBJ);

        return $posts;
    }
}