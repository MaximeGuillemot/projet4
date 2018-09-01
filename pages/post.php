<?php 
if(!empty($postdb))
{
    $post = $postdb->getPost('App\Article', (int) $_GET['id']);

    if(!isset($_GET['c'])) 
    {
        $firstComment = 0;
    }
    else
    {
        $firstComment = (int) $_GET['c'];
    }

    $comments = $postdb->getComments('App\Comment', $post->getId(), $firstComment);
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

    <?php 

    endforeach; 

    $nbPages = (int) ($postdb->countComments() / 5);

    if($nbPages > 1)
    {
    ?>
        <p>Pages : 
            <?php
                for($i = 0; $i <= $nbPages; $i++)
                {
                    echo '<a href="index.php?p=post&amp;id=' . $post->getId() . '&amp;c=' . $i*5 . '">' . ($i+1) . '</a> ';
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
?>

