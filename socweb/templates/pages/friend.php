
<?php
    include_once 'DATA/posts.php';
    if($curUser === null){
        include '404.php';
        exit;
    }

    $path = '/img/photos_'.$user['id'];
    $photos = getAllPhotos($user);

    $userPosts = getPostsByUser($user['id']);
?>
<link rel="stylesheet" href="/css/rss.css">

<?php if($photos): ?>
<section class="panel">
    <h2>Фотографии</h2>
    <div class="flex -fotos">
        <?php foreach($photos as $photo): ?>
        <a href="<?= $path.'/'.$photo ?>" target="_blank"><img src="<?= $path.'/'.$photo ?>"></a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="panel">
    <h2>Посты</h2>

    <?php foreach($userPosts as $post): ?>
    <article class="post">
        <?php if($post['img']): ?>
        <div class="img" style="background-image: url(<?= $post['img'] ?>)"></div>
        <?php endif; ?>
        <span class="time"><?= $post['date'] ?></span>
        <p><?= strtr($post['text'], ["\n"=>'</p><p>']) ?></p>
    </article>
    <?php endforeach; ?>
</section>