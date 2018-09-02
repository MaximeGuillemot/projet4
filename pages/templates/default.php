<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />

		<title>Blog - Jean Forteroche</title>

		<meta name="description" content="Roman publié par billets de blog" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="application-name" content="Billet simple pour l'Alaska" />
		
		<meta property="og:description" content="Roman publié par billets de blog" />
		<meta property="og:title" content="Blog - Jean Forteroche" />
		<meta property="og:site_name" content="Billet simple pour l'Alaska" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="http://maximeguillemot.com/formation/projet4/" />
		<meta property="og:image" content="" />
		
		<meta name="twitter:description" content="Roman publié par billets de blog" />
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@J.Forteroche" />
		<meta name="twitter:title" content="Blog - Jean Forteroche" />
		<meta name="twitter:image" content="" />

		<link rel="icon" sizes="16x16" href="images/favicon.ico" />

		<link href="styles/styles.css" type="text/css" rel="stylesheet" media="all" />
		<?php
		if(strpos($_SERVER['REQUEST_URI'], 'register'))
		{
		?>
			<script src='https://www.google.com/recaptcha/api.js?render=6LemuGwUAAAAAHh2vDB1_EPxFL_ahyZabT4V2bdE'></script>
			<script>
				grecaptcha.ready(function() {
					grecaptcha.execute('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', {action: 'homepage'}); // Localhost test key
				});
			</script>
		<?php
		}
		?>
	</head>

	<body>
		<header>
			<h1>Blog de Jean Forteroche</h1>
			
			<p><a href="index.php?p=register">S'inscrire</a></p>

			<p><a href="index.php">Accéder à l'accueil.</a></p>
		</header>

        <main>
			<?php 
				if(!empty($content))
				{
					echo $content;
				}
				else
				{
					echo '<p>La page que vous avez tenté de joindre n\'existe pas.</p>';
				}
			?>
        </main>
		
		<footer>

		</footer>

	</body>
</html>
