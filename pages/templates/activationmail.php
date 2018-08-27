<?php
$subject = 'Activation de votre compte - Blog de Jean Forteroche';

$message = <<<MAILCONTENT
<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Activation de votre compte sur le blog de Jean Forteroche</title>
        <style>
            #frame {border: 6px double #1B2222; padding: 10px;}
            p {text-align: justify; color: #1B2222;}
            a:hover {color: #084B8F;}
            #disclaimer {text-align: center; font-size: 80%; color: #1B2222;}
            #welcome {text-align: center;}
        </style>
    </head>

    <body>
        <div id="frame">
            <p id="welcome">
                Bienvenue sur le blog de Jean Forteroche !<br><br>
            </p>
            <p>
                Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
                ou le copier/coller dans votre navigateur web.<br><br>

                <a href="$siteUrl/public/index.php?p=register&key=$activationKey">$siteUrl/public/index.php?p=register&key=$activationKey</a><br><br>

                Si vous n'avez pas tenté de vous inscrire sur le blog de Jean Forteroche</a>, merci d'ignorer ce message.<br><br>

                Le lien d'activation n'est valide que pendant 24 heures. Si l'activation n'est pas finalisée passé ce délai,
                veuillez cliquer sur le lien pour réinitialiser l'inscription.<br>
            </p>
            <p id="disclaimer">
                ----------------<br><br>
                Ceci est un mail automatique envoyé depuis une adresse mail fictive.<br>
                Merci de ne pas y répondre.
            </p>
        </div>
    </body>
</html>'
MAILCONTENT;
?>