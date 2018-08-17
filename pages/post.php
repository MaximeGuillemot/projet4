<h2>Billet du blog.</h2>

<p><a href="index.php">Accéder à l'accueil.</a></p>

<?php 

$post = $db->getPost('App\Article', $_GET['id']); 
$comments = $db->getComments('App\Comment', $_GET['id']); 
?>

<h3><?= $post->getTitle(); ?></h3>
<p><?= $post->getContent(); ?></p>

<?php foreach($comments as $comment): ?>

<p>Commentaire de <?= $comment->getAuthor(); ?> :</p>
<p><?= $comment->getContent(); ?></p>

<?php endforeach; ?>

