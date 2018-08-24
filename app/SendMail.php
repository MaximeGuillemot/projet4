<?php

namespace App;

class SendMail
{
    protected $mailHeaders;
    protected $subject;
    protected $message;
    protected $email;
    
    public function __construct($data)
    {
        if(!empty($data))
        {
            require dirname(__DIR__) . '/config/config.php';

            foreach($siteconfig as $k => $v)
            {
                $data[$k] = $v;
            }

            $this->hydrate($data);
        }
    }

    protected function hydrate($data)
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
    
    protected function setMailHeaders($mailHeaders)
    {
        if(!empty($mailHeaders))
        {
            $this->mailHeaders = $mailHeaders;
        }
    }

    protected function setSubject($subject)
    {
        if(!empty($subject))
        {
            $this->subject = $subject;
        }
    }

    protected function setEmail($email)
    {
        if(!empty($email))
        {
            $this->email = $email;
        }
    }

    protected function setMessage($message)
    {
        if(!empty($message))
        {
            $this->message = $message;
        }
    }

    public function sendMail($subject = null, $message = null)
    {
        if(!empty($subject))
        {
            $this->setSubject($subject);
        }
        if(!empty($message))
        {
            $this->setMessage($message);
        }

        if(empty($this->getEmail()) || empty($this->getSubject()) || empty($this->getMessage()) || empty($this->getMailHeaders()))
        {
            return null;
        }


        mail($this->getEmail(), $this->getSubject(), $this->getMessage(), $this->getMailHeaders());
    }

    protected function getMailHeaders() { return $this->mailHeaders; }

    protected function getSubject() { return $this->subject; }

    protected function getEmail() { return $this->email; }

    protected function getMessage() { return $this->message; }
}

