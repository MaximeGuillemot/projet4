<div>
        <form method="post">
            <p>
                <label for="login">Nom d'utilisateur (pseudo) :</label><br>
                <input type="text" name="login" id="login" maxlength="30" placeholder="Pseudo" required >
            </p>
            <p>
                <label for="email">Adresse e-mail :</label><br>
                <input type="text" name="email" id="pass" maxlength="255" placeholder="Adresse e-mail" required>
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