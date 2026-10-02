<?php

include "../DATA/Model.php";
include "../DATA/Users.php";
include "../lib/DB/DataBase.php";
include "../lib/DB/DBMySqlDriver.php";
include "../lib/DB/DBPgSqlDriver.php";
use DATA\Model;
use DATA\Users;
use lib\DB\{
    DataBase,
    DBMySqlDriver,
    DBPgSqlDriver
};

    DataBase::$debug = true;

function testDB(DataBase $driver){
    // обновить данные пользователя
    $rowAffect = $driver->queryClose("UPDATE users SET description = $? WHERE description IS NULL",
        ['Нет описания']
    );

    // получаем всех пользователей
    $users = $driver->table("SELECT * FROM users WHERE fio IS NOT NULL ORDER BY fio", className: Model::class);
    //$users = Users::all();
    
    // Получаем количество записей в таблице пользователей
    $result = $driver->table("SELECT count(*) AS count_users FROM users", typeResult: DataBase::TYPE_ASSOC);
    $countUsers = $result[0]['count_users'];
    
    // создаем новую запись пользователя
    $idUser = $driver->insertGetId("INSERT INTO users(login, password, fio, tel, description) VALUES ($?, $?, $?, $?, $?)",[
        'UserPHP_'.$countUsers,
        '123123',
        'Иванов А.А.',
        '123456789',
        'В тут какоу-то описание нового пользователя'
    ]);

    /*
    $user = new Users([
        'login' => 'UserPHP_'.$countUsers,
        'password' => '123123',
        'fio' => 'Иванов А.А.',
        'tel' => '123456789',
        'description' => 'В тут какоу-то описание нового пользователя'
    ]);
    $user->save();
    */

    //$user = Users::find($idUser);
    $result = $driver->table("SELECT * FROM users WHERE id=$? LIMIT 1", [$idUser], className: Model::class);
    $newUser = $result[0] ?? null;

    echo $driver->getDebug();
?>

<br><br>
Всего пользователей: <?= $countUsers ?><br>
ID созданного пользователя: <?= $idUser ?><br>
Изменение строк: <?= $rowAffect ?><br><br>

<table border=1 cellpadding=10>
    <?php foreach($users as $user): if(isset($user->fio)): ?>
    <tr id="user_<?= $user->id ?>">
        <td><?= $user->id ?></td>
        <td><?= $user->login ?></td>
        <td><?= $user->fio ?></td>
        <td>
            <?php if(isset($user->avatar) && $user->avatar): ?>
            <img width="200" src="/storage/avatars/<?= $user->avatar ?>">
            <?php endif; ?>
        </td>
        <td class="desc"><?= strtr($user->description ?? '', ["\n"=>'<br>']) ?></td>
        <td><b>Контакты:</b><br>
        <?php if($user->tel): ?>
            <a href="tel:<?= $user->tel ?>"><i class="fa fa-mobile"></i> <?= $user->tel ?></a><br>
        <?php endif; if($user->email): ?>
            <a href="mailto:<?= $user->email ?>"><i class="fa fa-envelope-o"></i> <?= $user->email ?></a><br>
        <?php endif; if($user->telegram): ?>
            <a href="https://t.me/<?= substr($user->telegram, 1) ?>"><i class="fa fa-telegram"></i> <?= $user->telegram ?></a><br>
        <?php endif; ?>
        </td>
    </tr>
    <?php endif; endforeach; ?>

    <tr><td colspan=5><hr></td></tr>
    <tr id="user_<?= $newUser->id ?>">
        <td><?= $newUser->id ?></td>
        <td><?= $newUser->login ?></td>
        <td><?= $newUser->fio ?></td>
        <td>
            <?php if(isset($newUser->avatar) && $newUser->avatar): ?>
            <img width="200" src="/storage/avatars/<?= $newUser->avatar ?>">
            <?php endif; ?>
        </td>
        <td class="desc"><?= strtr($newUser->description ?? '', ["\n"=>'<br>']) ?></td>
        <td><b>Контакты:</b><br>
        <?php if($newUser->tel): ?>
            <a href="tel:<?= $newUser->tel ?>"><i class="fa fa-mobile"></i> <?= $newUser->tel ?></a><br>
        <?php endif; if($newUser->email): ?>
            <a href="mailto:<?= $newUser->email ?>"><i class="fa fa-envelope-o"></i> <?= $newUser->email ?></a><br>
        <?php endif; if($newUser->telegram): ?>
            <a href="https://t.me/<?= substr($newUser->telegram, 1) ?>"><i class="fa fa-telegram"></i> <?= $newUser->telegram ?></a><br>
        <?php endif; ?>
        </td>
    </tr>
</table>

<?php }

testDB(new DBMySqlDriver('127.127.126.26', 'php_lern', 'root'));
testDB(new DBPgSqlDriver('127.127.126.22', 'php_lern', 'postgres'));
