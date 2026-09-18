<?php
$globalConfigs = [];
$model = [];
$isAuth = false;
$authUser = null;

function AutoAuth(){
    global $isAuth, $authUser;
    if( $authUser === null){
        $isAuth = isset($_SESSION['hasAuth']);
        $authUser = $isAuth ? getUserById($_SESSION['UID']) : null;
    }

    return $authUser;
}

// app.paths.templates
function config(string $name, $defaultValue = null) {
    global $globalConfigs;
    $keys = explode('.', $name); // => ['app', 'paths', 'templates']
    $fileName = array_shift($keys);  // => filename = 'app', $keys = ['paths', 'templates']
    $path = "./configs/$fileName.php";

    // проверяем существует ли файл конфигурации
    if(!file_exists($path))
        return $defaultValue;

    // проверяем загружали ли мы его уже или нет
    if(isset($globalConfigs[$fileName]))
        $configs = $globalConfigs[$fileName];
    else {
        $configs = include_once $path;
        $globalConfigs[$fileName] = $configs;
    }

    // бежим по вложенностям, пока не достигнем предела вложенностей 
    // или пока не найдем нужный ключ
    while($configs !== null && count($keys) > 0){
        $key = array_shift($keys);
        $configs = $configs[$key] ?? null;
    }

    return $configs ?? $defaultValue;
}

function loadModel($name){
    global $models;
    if(!isset($models[$name])){
        $file = file_get_contents(config('app.paths.models','models')."/$name.json");
        $models[$name] = json_decode($file, true);

        include_once config('app.paths.models', 'models')."/$name.php";
    }
    return $models[$name];
}

function hasLoadCorrectFileImage(string $name): bool{
    return 
        isset($_FILES[$name]) &&
        $_FILES[$name]['error'] == 0 &&
        substr($_FILES[$name]['type'], 0, 6) == 'image/';
}

include_once "lib/session.php";
include_once "lib/Routes.php";
include_once "lib/View.php";

loadModel('users');