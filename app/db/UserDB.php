<?php

namespace App\DB;

use \PDO;

class UserDB
{
    private $id;
    private $login;
    private $password;
    private $email;
    private $access;
    private $activationKey;
    private $pdo;

    const WRONG_INFO = 1;
    const LOGIN_NOT_AVAILABLE = 2;
    const EMAIL_NOT_AVAILABLE = 3;
    const NEW_ACCOUNT = 4;
    const MEMBER_ACCOUNT = 5;
    const ADMIN_ACCOUNT = 6;
    const ACCOUNT_NOT_FOUND = 7;
    const LINK_VALID = 8;
    const LINK_EXPIRED = 9;

    public function __construct(DBConnect $db, $data)
    {
        if(!empty($db) && !empty($data))
        {
            $this->pdo = $db->getPDO();
            $this->hydrate($data);
        }
    }

    public function hydrate($data)
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

    private function setId($id)
    {
        if(!empty($id))
        {
            $this->id = (int) $id;
        }
    }

    private function setLogin($login)
    {
        if(!empty($login))
        {
            $this->login = $login;
        }
    }

    private function setPass($password)
    {
        if(!empty($password))
        {
            $this->password = password_hash($password, PASSWORD_DEFAULT, ["cost" => 10]);
        }
    }

    private function setEmail($email)
    {
        if(!empty($email))
        {
            $this->email = $email;
        }
    }

    private function setAccess($access)
    {
        if(!empty($access))
        {
            $this->access = (int) $access;
        }
    }

    private function setActivationKey($activationKey)
    {
        if(!empty($activationKey))
        {
            $this->activationKey = $activationKey;
        }
    }

    public function updateAccess(int $access)
    {
        if(!empty($access))
        {
            $q = $this->pdo->prepare("UPDATE users SET access = :access WHERE id = :id");
            $q->execute(array(
                'access' => $access,
                'id' => $this->id
            ));
        }
    }

    public function checkActivationStatus()
    {
        $q = $this->pdo->prepare("SELECT IF(DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 24 HOUR) <= added, :valid, :expired) AS valid 
                                FROM users 
                                WHERE id = :id");
        $q->execute(array(
            'valid' => self::LINK_VALID,
            'expired' => self::LINK_EXPIRED,
            'id' => $this->getId()
        ));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        return $data['valid'];
    }

    public function deleteUser(int $id)
    {
        if(!empty($id))
        {
            $q = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $q->execute(array('id' => $id));
        }
    }

    public static function getUserByKey(DBConnect $db, string $activationKey)
    {
        if(!empty($activationKey))
        {
            $q = $db->getPDO()->prepare("SELECT * FROM users WHERE activationKey = :activationKey");
            $q->execute(array('activationKey' => $activationKey));
            $data = $q->fetch(PDO::FETCH_ASSOC);

            return new UserDB($db, $data);
        }
    }

    public function addUser()
    {
        if(!empty($this->getErrors()))
        {
            return null;
        }

        $q = $this->pdo->prepare("INSERT INTO users(login, password, email, added, activationKey, access) 
                                  VALUES(:login, :password, :email, NOW(), :activationKey, :access)");
        $q->execute(array(
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email,
            'activationKey' => $this->activationKey,
            'access' => self::NEW_ACCOUNT
        ));
    }

    private function loginAvailability()
    {
        $q = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE login = :login');
        $q->execute(array('login' => $this->login));
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
        $q->execute(array('email' => $this->email));
        $emailTaken = $q->fetchColumn();

        if($emailTaken)
        {
            return false;
        }

        return true;
    }

    public function getAccess()
    {
        return $this->access;
    }

    public function getErrors()
    {
        $errors = [];

        if(empty($this->login) || empty($this->password) || empty($this->email) || empty($this->activationKey))
        {
            $errors[] = self::WRONG_INFO;
        }

        if(!$this->loginAvailability())
        {
            $errors[] = self::LOGIN_NOT_AVAILABLE;
        }

        if(!$this->emailAvailability())
        {
            $errors[] = self::EMAIL_NOT_AVAILABLE;
        }

        return $errors;
    }

    public function getId()
    {
        return $this->id;
    }
}