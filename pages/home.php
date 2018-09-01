<h2>Bienvenue sur la page d'accueil du blog.</h2>

<?php 
if(!empty($postdb))
{
    if(!isset($_GET['a']))
    {
        $firstPost = 0;
    }
    else
    {
        $firstPost = (int) $_GET['a'];
    }

    foreach($postdb->getPosts('App\Article', $firstPost) as $post): ?>
        <h3><?= $post->getTitle(); ?></h3>
        <p><?= $post->getExcerpt(); ?></p>
        <p><a href="index.php?p=post&amp;id=<?= $post->getId(); ?>">Lire la suite</a></p>
    <?php 
    endforeach; 

    $nbPages = (int) ($postdb->countPosts() / 5);

    if($nbPages > 1)
    {
    ?>
        <p>Pages : 
            <?php
                for($i = 0; $i <= $nbPages; $i++)
                {
                    echo '<a href="index.php?home&amp;a=' . $i*5 . '">' . ($i+1) . '</a> ';
                }
            ?>
        </p>
    <?php
    }
}
else
{
    echo '<p>La page que vous avez tenté de joindre n\'existe pas.</p>';
}







