<?php
session_start();

use App\Register;
use App\DB\UserDB;
?>

<h2>Inscription</h2>

<?php
if(isset($_POST['register']) && $_SESSION['activationKey'] === $_POST['activationKey'])
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
                echo '<p>' . $error . '</p>';
            }

            unset($userdb, $_POST['register']);
        }
        else
        {
            $userdb->sendActivationMail();
            $userdb->addUser();
            echo 'gg tu t\'es inscrit';
        }
    }
    else
    {
        foreach($register->getErrors() as $error)
        {
            echo '<p>' . $error . '</p><br>';
        }

        $_POST['register'] = null;
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
                <input type="hidden" name="activationKey" value="<?= $activationKey; ?>">
                <input type="submit" name="register" value="Valider l'inscription" class="submitBtn">
            </div>
        </form>
    </div>
<?php
}
?>