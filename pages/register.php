<?php
session_start();

use App\Register;
use App\SendActivationMail;
use App\DB\UserDB;
?>

<h2>Inscription</h2>

<?php

if(isset($_POST['register']) && isset($_POST['g-recaptcha-response']) && $_SESSION['activationKey'] === $_POST['activationKey'])
{
    $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Localhost test key
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $response . "&remoteip=" . $remoteip;

    $decode = json_decode(file_get_contents($api_url), true);

    if($decode['success'] === true)
    {
        $login = htmlspecialchars($_POST['login']);
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $passCheck = htmlspecialchars($_POST['passCheck']);
        $activationKey = htmlspecialchars($_POST['activationKey']);
        $user = array(
            'login' => $login,
            'email' => $email,
            'pass' => $pass,
            'passCheck' => $passCheck,
            'activationKey' => $activationKey
        );
        
        $register = new Register($user);

        if(empty($register->getErrors()))
        {
            $userdb = new UserDB($db, $user);
            $errors = $userdb->getErrors();     
              
            if(!empty($errors))
            {
                foreach($errors as $error)
                {
                    switch($error)
                    {
                        case UserDB::WRONG_INFO:
                            echo '<p>Les informations fournies pour la création d\'un nouveau compte sont erronnées.</p>';
                            break;
                        case UserDB::LOGIN_NOT_AVAILABLE:
                            echo '<p>Le pseudo choisi est déjà utilisé, veuillez en choisir un autre.</p>';
                            break;
                        case UserDB::EMAIL_NOT_AVAILABLE:
                            echo '<p>L\'adresse e-mail choisie est déjà utilisée, veuillez en choisir une autre.</p>';
                            break;
                        default:
                            $errors = [];
                    }
                }

                unset($userdb, $_POST['register']);
            }
            else
            {
                $sendMail = new SendActivationMail($user);
                $sendMail->sendMail();
                $userdb->addUser();
                echo '<p>Un e-mail vous a été envoyé pour activer votre compte et finaliser votre inscription.</p>';
            }
        }
        else
        {
            foreach($register->getErrors() as $error)
            {
                echo '<p>' . $error . '</p>';
            }

            $_POST['register'] = null;
        }
    }
    else
    {
        echo '<p>Une erreur est survenue. Veuillez recommencer l\'inscription.</p>';
    }
}

if(!isset($_POST['register']))
{
    $activationKey = md5(uniqid(rand(), TRUE));
    $_SESSION['activationKey'] = $activationKey;
?>
    <div>
        <form method="post">
            <p>
                <label for="login">Nom d'utilisateur (pseudo) :</label><br>
                <input type="text" name="login" id="login" maxlength="30" placeholder="Pseudo" <?php if(!empty($login)) echo 'value="'. $login .'"'; ?> required>
            </p>
            <p>
                <label for="email">Adresse e-mail :</label><br>
                <input type="text" name="email" id="pass" maxlength="255" placeholder="Adresse e-mail" <?php if(!empty($email)) echo 'value="'. $email .'"'; ?> required>
            </p>
            <p>
                <label for="pass">Mot de passe :</label><br>
                <input type="password" name="pass" id="pass" maxlength="255" placeholder="Mot de passe" required>
            </p>
            <p>
                <label for="passCheck">Vérification du mot de passe :</label><br>
                <input type="password" name="passCheck" id="passCheck" maxlength="255" placeholder="Même mot de passe" required>
            </p>

            <div>
                <input type="hidden" name="g-recaptcha-response" value="6LemuGwUAAAAAHh2vDB1_EPxFL_ahyZabT4V2bdE">
                <input type="hidden" name="activationKey" value="<?= $activationKey; ?>">
                <input type="submit" name="register" value="Valider l'inscription" class="submitBtn">
            </div>
        </form>
    </div>
<?php
}
?>