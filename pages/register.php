<h2>Inscription</h2>

<?php

use App\Register;

if(isset($_POST['register']))
{
    $register = new Register(array(
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'pass' => $_POST['pass'],
        'passCheck' => $_POST['passCheck']    
    ));

    if(empty($register->getErrors()))
    {
        echo 'gg tu t\'es inscrit';
        die();
    }
    else
    {
        foreach($register->getErrors() as $error)
        {
            echo $error . '<br>';
        }
    }
}


    require 'registerform.php';

?>