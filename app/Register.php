<?php
namespace App;

class Register
{
    private $login;
    private $email;
    private $pass;
    private $passCheck;
    private $errors = [];

    const WRONG_LOGIN = 1;
    const WRONG_EMAIL = 2;
    const WRONG_PASS = 3;
    const WRONG_PASS_CHECK = 4;
    const MISSING_INFO = 5;

    public function __construct($data)
    {
        if(!empty($data))
        {
            $this->hydrate($data);
            $this->checkPass($this->pass, $this->passCheck);
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
        if(empty($login))
        {
            $this->errors[] = self::MISSING_INFO;
        }
        elseif(!preg_match('#^[a-zA-Zàâäéèêëîïôöûü0-9]{2,30}$#', $login))
        {
            $this->errors[] = self::WRONG_LOGIN;
        }

        $this->login = $login;
    }

    private function setEmail($email)
    {
        if(empty($email))
        {
            $this->errors[] = self::MISSING_INFO;
        }
        elseif(strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = self::WRONG_EMAIL;
        }

        $this->email = $email;
    }

    private function setPass($pass)
    {
        if(empty($pass))
        {
            $this->errors[] = self::MISSING_INFO;
        }
        elseif(!preg_match('#^(?=.{8,})(?=.*[a-z])(?=.*[0-9]).*$#', $pass)) // At least 1 capital letter, 1 number, and 8+ characters total
        {
            $this->errors[] = self::WRONG_PASS;
        }

        $this->pass = $pass;
    }

    private function setPassCheck($passCheck)
    {
        if(empty($passCheck))
        {
            $this->errors[] = self::MISSING_INFO;
        }
        else
        {
            $this->passCheck = $passCheck;
        }
    }

    private function checkPass($pass, $passCheck)
    {
        if(!empty($pass) && $pass !== $passCheck)
        {
            $this->errors[] = self::WRONG_PASS_CHECK;
        }
    }

    public function getErrors()
    {
        $errors = [];
        foreach($this->errors as $error)
        {
            switch($error)
            {
                case self::WRONG_LOGIN:
                    $errors[] = 'Le pseudo ne peut excéder 30 caractères et ne doit comprendre que des caractères alphanumériques.';
                    break;
                case self::WRONG_EMAIL:
                    $errors[] = 'L\'adresse e-mail renseignée doit être valide.';
                    break;
                case self::WRONG_PASS:
                    $errors[] = 'Le mot de passe doit comporter au moins 8 caractères dont au moins une lettre et un chiffre.';
                    break;
                case self::WRONG_PASS_CHECK:
                    $errors[] = 'Les mots de passe entrés sont différents. Veuillez taper deux fois le même mot de passe.';
                    break;
                case self::MISSING_INFO:
                    $errors[] = 'Vous devez compléter tous les champs du formulaire pour pouvoir vous inscrire.';
                    break;
                default:
                    $errors = [];
            }
        }

        return $errors;
    }
}