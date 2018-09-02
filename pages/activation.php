<?php

use App\DB\UserDB;

if(isset($_GET['key']))
{
    $activationKey = htmlspecialchars($_GET['key']);
    $keyData = ['activationKey' => $activationKey];

    $user = UserDB::getUserByKey($db, $activationKey);

    if($user->getAccess() != UserDB::NEW_ACCOUNT)
    {
        echo '<p>Ce compte n\'existe pas ou a déjà été activé.</p>';
    }
    else
    {
        $user->updateAccess(UserDB::MEMBER_ACCOUNT);
        echo '<p>Votre compte a bien été activé. Vous pouvez désormais vous connecter.</p>';
    }
}
