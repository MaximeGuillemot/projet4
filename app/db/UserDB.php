<?php

namespace App\DB;

class UserDB
{
    private $login;
    private $password;
    private $email;
    private $access;
    private $activationKey;
    private $pdo;
    private $errors = [];

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

    private function setActivationKey($activationKey)
    {
        if(!empty($activationKey) && (string) $activationKey === $activationKey)
        {
            $this->activationKey = htmlspecialchars($activationKey);
        }
    }

    public function addUser()
    {
        if(!empty($this->errors))
        {
            return null;
        }

        $q = $this->pdo->prepare("INSERT INTO users(login, password, email, added, activationKey, access) 
                                  VALUES(:login, :password, :email, NOW(), :activationKey, 0)");
        $q->execute(array(
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email,
            'activationKey' => $this->activationKey
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

    public function getErrors()
    {
        $errors = [];

        if(empty($this->login) || empty($this->password) || empty($this->email) || empty($this->activationKey))
        {
            $this->errors[] = self::WRONG_INFO;
        }

        if(!$this->loginAvailability())
        {
            $this->errors[] = self::LOGIN_NOT_AVAILABLE;
        }

        if(!$this->emailAvailability())
        {
            $this->errors[] = self::EMAIL_NOT_AVAILABLE;
        }

        foreach($this->errors as $error)
        {
            switch($error)
            {
                case self::WRONG_INFO:
                    $errors[] = 'Les informations fournies pour la création d\'un nouveau compte sont erronnées.';
                    break;
                case self::LOGIN_NOT_AVAILABLE:
                    $errors[] = 'Le pseudo choisi est déjà utilisé, veuillez en choisir un autre.';
                    break;
                case self::EMAIL_NOT_AVAILABLE:
                    $errors[] = 'L\'adresse e-mail choisie est déjà utilisée, veuillez en choisir une autre.';
                    break;
                default:
                    $errors = [];
            }
        }

        return $errors;
    }
}