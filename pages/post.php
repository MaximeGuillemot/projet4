<?php 
$post = $db->getPost('App\Article', $_GET['id']);

if(!isset($_GET['c'])) 
{
    $firstComment = 0;
}
else
{
    $firstComment = (int) $_GET['c'];
}

$comments = $db->getComments('App\Comment', $firstComment);
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

$nbPages = (int) ($db->countComments() / 5);

if($nbPages > 1)
{
?>
    <p>Pages : 
        <?php
            for($i = 0; $i <= $nbPages; $i++)
            {
                echo '<a href="index.php?p=post&amp;id=' . $firstComment . '&amp;c=' . $i*5 . '">' . ($i+1) . '</a> ';
            }
        ?>
    </p>
<?php
}
?>

