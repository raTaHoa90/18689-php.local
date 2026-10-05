<?php

namespace DATA;

use DATA\Traits\TraitCreatedTime;
use DATA\Traits\TraitUser;

/*
    id serial4 NOT NULL,
	caption varchar(255) NOT NULL,
	saf varchar(255) NOT NULL,
	user_id int4 NOT NULL,
	descript text NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
	deleted_at timestamp NULL,
*/

class ForumThemes extends Model {
    use TraitCreatedTime, TraitUser;
    
    static function all(): array {
        return static::allWhere("deleted_at is null ORDER BY caption");
    }

    static function findBySaf(string $saf): ?ForumThemes {
        $result = static::allWhere('deleted_at is null AND saf=$? LIMIT 1', [$saf]);
        return $result[0] ?? null;
    }

    function setCaption(string $caption){
        $this->caption = $caption;
        $this->saf = translit($caption);
    }

    function messages(): array {
        return ForumThemeMessages::allByTheme($this->id);
    }

    function countMessages(): int {
        return ForumThemeMessages::countByTheme($this->id);
    }
}