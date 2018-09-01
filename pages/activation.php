<?php

use App\DB\UserDB;

if(isset($_GET['key']))
{
    $activationKey = ['activationKey' => htmlspecialchars($_GET['key'])];

    $user = new UserDB($db, $activationKey);
    $access = $user->getAccess($activationKey);

    if($access != UserDB::NEW_ACCOUNT)
    {
        echo '<p>Ce compte n\'existe pas ou a déjà été activé.</p>';
    }
    else
    {
        echo 'Youpi !';
    }
}
