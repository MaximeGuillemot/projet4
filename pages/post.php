<h2>Billet du blog.</h2>

<p><a href="index.php">Accéder à l'accueil.</a></p>

<?php $post = $db->getPost('App\Post', $_GET['id']); ?>

<h3><?= $post->getTitle(); ?></h3>
<p><?= $post->getContent(); ?></p>

