<?php

require '../app/Autoloader.php';
App\Autoloader::initiateAutoloader();

$page = (!empty($_GET['p'])) ? $_GET['p'] : 'home';

switch ($page)
{
	case 'home':
		require '../pages/home.php';
		break;
	case 'post':
		require '../pages/post.php';
		break;
	default:
		echo 'Erreur : page non trouvée.';
}


