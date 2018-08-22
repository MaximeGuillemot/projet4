<?php

namespace App;

class SendMail
{
    protected $config;
    protected $headers;
    protected $subject;
    protected $message;
    protected $email;
    
    public function __construct($data)
    {
        if(!empty($data))
        {
            $config = require dirname(__DIR__) . '/config/mailconfig.php';

            foreach($config as $k => $v)
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
    
    protected function setHeaders($headers)
    {
        if(!empty($headers) && (string) $headers === $headers)
        {
            $this->headers = htmlspecialchars($headers);
        }
    }

    protected function setSubject($subject)
    {
        if(!empty($subject) && (string) $subject === $subject)
        {
            $this->subject = htmlspecialchars($subject);
        }
    }

    protected function setEmail($email)
    {
        if(!empty($email) && (string) $email === $email)
        {
            $this->email = htmlspecialchars($email);
        }
    }

    protected function setMessage($message)
    {
        if(!empty($message) && (string) $message === $message)
        {
            $this->message = $message;
        }
    }

    public function sendMail($subject = null, $message = null)
    {
        if(empty($subject) || (string) $subject !== $subject)
        {
            $subject = $this->getSubject();
        }
        if(empty($message) || (string) $message !== $message)
        {
            $message = $this->getMessage();
        }

        if(empty($this->getEmail()) || empty($subject) || empty($message) || empty($this->getHeaders()))
        {
            return null;
        }

        mail($this->getEmail(), $subject, $message, $this->getHeaders());
    }

    protected function getHeaders() { return $this->headers; }

    protected function getSubject() { return $this->subject; }

    protected function getEmail() { return $this->email; }

    protected function getMessage() { return $this->message; }

}

