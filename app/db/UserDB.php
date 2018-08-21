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

    public function sendActivationMail()
    {
        if(empty($this->getErrors()))
        {
            $subject = 'Activez votre compte - Blog Jean Forteroche';

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: Jean Forteroche <no-reply@jeanforteroche.fr>'  . "\r\n";
            $headers .= 'Reply-To: no-reply@jeanforteroche.fr'  . "\r\n";
            $headers .= 'Return-Path: no-reply@jeanforteroche.fr'  . "\r\n";

            $message = '
                <html>
                    <head>
                        <title>Activation de votre compte sur le blog de Jean Forteroche</title>
                        <style>
                            #fond {border: 6px double #1B2222; padding: 10px;}
                            p {text-align: justify; color: #1B2222;}
                            a:hover {color: #DCE9F2;}
                            #disclaimer {text-align: center; font-size: 80%; color: #1B2222;}
                            #bienvenue {text-align: center;}
                        </style>
                    </head>
                    <body>
                        <div id="fond">
                            <p id="bienvenue">
                                Bienvenue sur le blog de Jean Forteroche, ' . $this->login . ' !<br><br>
                            </p>
                            <p>
                                Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
                                ou le copier/coller dans votre navigateur web.<br><br>

                                <a href="http://localhost/projet4/public/index.php?p=register&amp;log=' . urlencode($this->login) .'&amp;key=' . urlencode($this->activationKey) .'">
                                http://localhost/projet4/public/index.php?p=register&amp;log=' . urlencode($this->login) .'&amp;key=' . urlencode($this->activationKey) . '</a><br><br>

                                Si vous n\'avez pas tenté de vous inscrire sur le <a href="https://maximeguillemot.com/formation/projet4/">Blog de Jean Forteroche</a>, merci d\'ignorer ce message.<br><br>

                                Le lien d\'activation n\'est valide que pendant 24 heures. Si l\'activation n\'est pas finalisée passé ce délai,
                                veuillez cliquer sur le lien pour réinitialiser l\'inscription.<br>
                            </p>
                            <p id="disclaimer">
                                ----------------<br><br>
                                Ceci est un mail automatique envoyé depuis une adresse mail fictive.<br>
                                Merci de ne pas y répondre.
                            </p>
                        </div>
                    </body>
                </html>';

            mail($this->email, $subject, $message, $headers);
        }
    }
}