<?php
/*
    id:
    login:
    password:
    avatar:
    desc: 
    fio:
    city:
    job:
    tel:
    age:
    role:
*/

namespace DATA;

use DATA\Traits\TraitCreatedTime;

class Users extends Model {
    use TraitCreatedTime;
    
    const 
        CONTINUE_ARRAY = ['.','..'],
        FILE_NAME = 'users',

        ROLE_DEFAULT = 0,
        ROLE_ADMIN = 1,

        ROLES = [
            self::ROLE_DEFAULT => 'Пользователь',
            self::ROLE_ADMIN => 'Администратор'
        ];

    static function all(): array {
        return static::allWhere("deleted_at is null AND fio <> '' ORDER BY fio");
    }

    // функция поиска пользователя по его Login, возвращает данные пользователя или Null
    static function getUserByLogin(string $login): ?Users {
        $result = static::allWhere('login=$1 LIMIT 1', [$login]);
        return $result[0] ?? null;
    }

    //==============================================================

    function setPassword(string $password): Users {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    function testPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    function getAllPhotos(): array{
        $path = 'img/photos_'.$this->id;

        $result = [];
        if(!is_dir($path))
            return $result;

        $catalog = dir($path);
        while(false !== ($entry = $catalog->read())){
            if(!in_array($entry, static::CONTINUE_ARRAY) && !is_dir($path.'/'.$entry))
                $result[] = $entry;
        }

        return $result;
    }

    function pathPublic(): string {
        return 'public/storage/'.$this->id.'_catalog';
    }

    function getDesc(): string {
        return strtr($this->desc ?? '', ["\n" => '<br>']);
    }

    function roleCaption(): string {
        return static::ROLES[$this->role ?? static::ROLE_DEFAULT];
    }

    function getName(): string {
        return $this->fio ?: $this->login ?: $this->email;
    }
}
