<?php

// https://www.php.net/manual/ru/ref.pcre.php

//доступные флаги:  https://www.php.net/manual/ru/reference.pcre.pattern.modifiers.php

/*
preg_match('/(foo)(bar)(baz)/', 'foobarbaz') // true - шаблон совпал, false - нет
preg_match('/(foo)(bar)(baz)/', 'foobarbaz', $matches);
preg_match('/(foo)(bar)(baz)/', 'foobarbaz', $matches, PREG_OFFSET_CAPTURE);
preg_match('/(foo)(bar)(baz)/', 'foobarbaz', $matches, PREG_UNMATCHED_AS_NULL);



$str = <<<FOO
a: 1
b: 2
c: 3
FOO;

preg_match_all('/(?P<name>\w+): (?P<digit>\d+)/', $str, $matches);


// разбиваем строку по произвольному числу запятых и пробельных символов,
// которые включают в себя  " ", \r, \t, \n и \f
$keywords = preg_split("/[\s,]+/", "hypertext language, programming");
*/