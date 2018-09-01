<?php

use App\Config;
use App\DB\DBConnect;
use App\DB\PostDB;

require '../app/Autoloader.php';
App\Autoloader::initiateAutoloader();

$page = (!empty($_GET['p'])) ? $_GET['p'] : 'home';

$config = Config::getInstance();
$db = new DBConnect(
	$config->getSetting('db_name'), 
	$config->getSetting('db_user'), 
	$config->getSetting('db_pass'), 
	$config->getSetting('db_host'));
$postdb = new PostDB($db);


ob_start(); // Stores the following content in a variable for dynamic inclusions in the default layout
switch ($page)
{
	case 'home':
		require '../pages/home.php';
		break;
	case 'post':
		require '../pages/post.php';
		break;
	case 'register':
		require '../pages/register.php';
		break;
	case 'activation':
		require '../pages/activation.php';
		break;
	default:
		require '../pages/error.php';
}
$content = ob_get_clean(); // Saves the previous content in the variable

require '../pages/templates/default.php';

