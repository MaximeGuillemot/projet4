<?php
session_start();

use App\Register;
?>

<h2>Inscription</h2>

<?php
if(isset($_POST['register']))
{
    $login = htmlspecialchars($_POST['login']);
    $email = htmlspecialchars($_POST['email']);
    
    $register = new Register(array(
        'login' => $_POST['login'],
        'email' => $_POST['email'],
        'pass' => $_POST['pass'],
        'passCheck' => $_POST['passCheck']    
    ));

    if(empty($register->getErrors()))
    {
        echo 'gg tu t\'es inscrit';
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
                <input type="submit" name="register" value="Valider l'inscription" class="submitBtn">
            </div>
        </form>
    </div>
<?php
}
?>