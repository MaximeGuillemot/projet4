<?php

namespace App;

class SendActivationMail extends SendMail
{
    protected $activationSubject;
    protected $activationMessage;
    protected $activationKey;
    protected $login;
    
    // Info sent by $user

    protected function setLogin($login)
    {  
        if(!empty($login) && (string) $login === $login)
        {
            $this->login = htmlspecialchars($login);
        }
    }

    protected function setActivationKey($activationKey)
    {
        if(!empty($activationKey) && (string) $activationKey === $activationKey)
        {
            $this->activationKey = htmlspecialchars($activationKey);
        }
    }

    // Info sent by mailconfig

    protected function setActivationSubject($activationSubject)
    {
        if(!empty($activationSubject) && (string) $activationSubject === $activationSubject)
        {
            $this->setSubject(htmlspecialchars($activationSubject));
        }
    }
    
    protected function setActivationMessage($activationMessage)
    {
        if(!empty($activationMessage) && (string) $activationMessage === $activationMessage)
        {
            $activationMessage = str_replace("%LOGIN%", urlencode($this->getLogin()), $activationMessage);
            $activationMessage = str_replace("%ACTIVATIONKEY%", urlencode($this->getActivationKey()), $activationMessage);

            $this->setMessage($activationMessage);
        }
    }

    protected function getMessage()
    {
        return $this->message;
    }

    protected function getLogin()
    {
        return $this->login;
    }

    protected function getActivationKey()
    {
        return $this->activationKey;
    }


}