<?php

namespace App;

class SendActivationMail extends SendMail
{
    protected $activationKey;
    protected $siteUrl;
    
    // Info sent by $user

    protected function setActivationKey($activationKey)
    {
        if(!empty($activationKey))
        {
            $this->activationKey = $activationKey;
        }
    }

    // Info sent by config

    protected function setSiteUrl($siteUrl)
    {
        if(!empty($siteUrl))
        {
            $this->siteUrl = $siteUrl;
        }
    }
    
    protected function setActivationMessage()
    {
        $activationKey = $this->getActivationKey();
        $siteUrl = $this->getSiteUrl();

        require dirname(__DIR__) . '/pages/templates/activationmail.php';

        $this->setSubject($subject);
        $this->setMessage($message);
    }

    protected function getActivationKey() { return $this->activationKey; }

    protected function getSiteUrl() { return $this->siteUrl; }
}