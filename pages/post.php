<p><a href="index.php">Accéder à l'accueil.</a></p>

<?php 
$post = $db->getPost('App\Article', $_GET['id']); 
$comments = $db->getComments('App\Comment', $_GET['id']); 
?>

<article>
    <h2><?= $post->getTitle(); ?></h2>
    <p><?= $post->getContent(); ?></p>

    <footer>
        <p>Ecrit par <?= $post->getAuthor(); ?> le <?= $post->getDate(); ?></p>
    </footer>
</article>

<?php foreach($comments as $comment): ?>

<p>Commentaire de <?= $comment->getAuthor(); ?> le <?= $comment->getDate() . ' à ' . $comment->getTime(); ?></p>
<p><?= $comment->getContent(); ?></p>

<?php endforeach; ?>

