<?php
$tags = json_decode(file_get_contents('DATA/tags.json'), true);
/*
    user_id:        // пользователь под которым авторизованы
    user_target_id: // друг в связке
    tag:            // тег для друга
*/

function findTagsByFriend(int $authID, int $friendID): array {
    global $tags;

    $result = [];
    foreach($tags as $tag)
        if($tag['user_id'] == $authID && $tag['user_target_id'] == $friendID)
            $result[] = $tag['tag'];

    return $result;
}