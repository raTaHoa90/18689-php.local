<?php
    include_once "DATA/usersFriends.php";
    include_once "DATA/tags.php";
    $friends = getFriendsByUserID($user['id']);
?>

<link rel="stylesheet" href="/css/friends.css">

<section>
    <h2>Мои друзья</h2>

    <?php foreach($friends as $friend): ?>
    <article class="panel -frind" onclick="location='?action=friend&f_id=<?= $friend['id'] ?>'">
        <div class="img" style="background-image: url(<?= $friend['avatar'] ?>)"></div>
        <h4><?= $friend['fio'] ?></h4>
        <b>Возраст:</b> <?= $friend['age'] ?> лет
        <?php
            $curTags = findTagsByFriend($user['id'], $friend['id']);
            if(count($curTags)):
        ?><div class="tags">
        <?php foreach($curTags as $tag):?>
            <a href="#<?= $tag ?>" class="btn btn-light"><?= $tag ?></a>
        <?php endforeach;?>
        </div>
        <?php endif; ?>
    </article>
    <?php endforeach; ?>
</section>