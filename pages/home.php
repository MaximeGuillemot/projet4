<h2>Bienvenue sur la page d'accueil du blog.</h2>

<p><a href="index.php?p=post">Accéder à un article particulier.</a></p>

<?php foreach($db->getPosts('App\Post') as $post): ?>

    <h3><?= $post->getTitle(); ?></h3>
    <p><?= $post->getExcerpt(); ?></p>
    <p><a href="index.php?p=post&amp;id=<?= $post->getId(); ?>">Lire la suite</a></p>

<?php endforeach; ?>