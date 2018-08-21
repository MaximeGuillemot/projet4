<?php

namespace App\DB;

class UserDB
{
    private $login;
    private $password;
    private $email;
    private $access;
    private $pdo;

    const WRONG_INFO = 1;
    const LOGIN_NOT_AVAILABLE = 2;
    const EMAIL_NOT_AVAILABLE = 3;

    public function __construct(DBConnect $db, $data)
    {
        if(!empty($db) && !empty($data))
        {
            $this->pdo = $db->getPDO();
            $this->hydrate($data);
        }
    }

    private function hydrate($data)
    {
        foreach($data as $k => $v)
        {
            $method = 'set' . ucfirst($k);
            if(is_callable([$this, $method]))
            {
                $this->$method($v);
            }
        }
    }

    private function setLogin($login)
    {
        if(!empty($login) && (string) $login === $login)
        {
            $this->login = htmlspecialchars($login);
        }
    }

    private function setPass($password)
    {
        if(!empty($password) && (string) $password === $password)
        {
            $this->password = password_hash(htmlspecialchars($password), PASSWORD_DEFAULT, ["cost" => 10]);
        }
    }

    private function setEmail($email)
    {
        if(!empty($email) && (string) $email === $email)
        {
            $this->email = htmlspecialchars($email);
        }
    }

    private function setAccess($access)
    {
        if(!empty($access) && (int) $access === $access)
        {
            $this->access = htmlspecialchars($access);
        }
    }

    public function addUser()
    {
        if(empty($this->login) && empty($this->password) && empty($this->email))
        {
            return self::WRONG_INFO;
        }

        if(!$this->loginAvailability())
        {
            return self::LOGIN_NOT_AVAILABLE;
        }

        if(!$this->emailAvailability())
        {
            return self::EMAIL_NOT_AVAILABLE;
        }

        $q = $this->pdo->prepare('INSERT INTO users(login, password, email, access) VALUES(:login, :password, :email, 0)');
        $q->execute(array(
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email
        ));
    }

    private function loginAvailability()
    {
        $q = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
        $q->execute(array(
            'login' => $this->login
        ));
        $loginTaken = $q->fetchColumn();

        if($loginTaken)
        {
            return false;
        }

        return true;
    }

    private function emailAvailability()
    {
        $q = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $q->execute(array(
            'email' => $this->email
        ));
        $emailTaken = $q->fetchColumn();

        if($emailTaken)
        {
            return false;
        }

        return true;
    }
}