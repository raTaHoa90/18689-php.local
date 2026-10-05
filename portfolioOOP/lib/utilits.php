<?php 

// app.paths.templates

use lib\SYS;

const CONVERTER_CHARS = [
    'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
    'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
    'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
    'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
    'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
    'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
    'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

    'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
    'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
    'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
    'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
    'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
    'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
    'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
];

function config(string $name, $defaultValue = null) {
    $keys = explode('.', $name); // => ['app', 'paths', 'templates']
    $fileName = array_shift($keys);  // => filename = 'app', $keys = ['paths', 'templates']
    $path = "./configs/$fileName.php";

    // проверяем существует ли файл конфигурации
    if(!file_exists($path))
        return $defaultValue;

    // проверяем загружали ли мы его уже или нет
    if(isset(SYS::$configs[$fileName]))
        $configs = SYS::$configs[$fileName];
    else {
        $configs = include_once $path;
        SYS::$configs[$fileName] = $configs;
    }

    // бежим по вложенностям, пока не достигнем предела вложенностей 
    // или пока не найдем нужный ключ
    while($configs !== null && count($keys) > 0){
        $key = array_shift($keys);
        $configs = $configs[$key] ?? null;
    }

    return $configs ?? $defaultValue;
}

function hasLoadCorrectFileImage(string $name): bool{
    return 
        isset($_FILES[$name]) &&
        $_FILES[$name]['error'] == 0 &&
        substr($_FILES[$name]['type'], 0, 6) == 'image/';
}

function translit(string $value): string {
	return strtr($value, CONVERTER_CHARS);
}