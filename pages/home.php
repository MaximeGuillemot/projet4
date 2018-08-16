<h2>Bienvenue sur la page d'accueil du blog.</h2>

<p><a href="index.php?p=post">Accéder à un article particulier.</a></p>

<?php foreach($db->getPosts() as $post): ?>

    <h3><?= $post->title; ?></h3>
    <p><?= $post->content; ?></p>

<?php endforeach; ?>