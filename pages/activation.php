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
    elseif($user->getAccess() == UserDB::NEW_ACCOUNT && $user->checkActivationStatus() == UserDB::LINK_EXPIRED)
    {
        echo '<p>Le lien d\'activation pour ce compte a expiré. Veuillez recommencer la procédure d\'inscription.</p>';
        $user->deleteUser($user->getId());
        unset($user);
    }
    else
    {
        $user->updateAccess(UserDB::MEMBER_ACCOUNT);
        echo '<p>Votre compte a bien été activé. Vous pouvez désormais vous connecter.</p>';
    }   
}
