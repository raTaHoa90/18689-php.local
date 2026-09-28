<?php

namespace Controllers;

use DATA\Users;
use lib\SYS;

class UsersController extends BaseController {
    use TraitFileAndDirUtils;

    function getCatalogs($path, $pathGET){

        if(!is_dir($path))
            mkdir($path);

        $topCatalog = true;
        if($pathGET) {
            $path .= '/'.$pathGET;
            $topCatalog = false;
        }

        $path = strtr($path, ['..' => '', '//' => '/']);

        if(!is_dir($path))
            return false;

        return $this->LoadCatalogs($path, $topCatalog);
    }

    //=================================================
    // GET
    //=================================================

    function table(){
        SYS::view('users/table', [
            'caption' => 'Все пользователи',
            'users' => Users::all()
        ]);
    }

    function user($params){
        $user = Users::getUserByLogin($params['login']);
        if($user === null)
            SYS::redirect('/users');

        $currentPath = ($_GET['path'] ?? '');
        $currentPath = strtr($currentPath, ['..' => '']);
        $currentPath = strtr($currentPath, ['//' => '/', '\\\\'=>'\\']);
        if($currentPath == '/' || $currentPath == '\\')
            $currentPath = '';

        $catalog = $this->getCatalogs($user->pathPublic(), $currentPath);

        if($catalog === false)
            SYS::redirect('/users/'.$user->login);

        if($currentPath){
            $topPath = pathinfo($currentPath, PATHINFO_DIRNAME);
            if($topPath == '/' || $topPath == '//')
                $topPath = '';
        }else 
            $topPath = '';

        SYS::view('users/simple', [
            'caption' => 'Профиль: '. $user->getName(),
            'user' => $user,
            'catalogs' => $catalog,
            'userpath' => '/storage/'.$user->id.'_catalog/'.$currentPath.'/',
            'currentPath' => $currentPath,
            'topPath' => $topPath,
            'EXT_PIC' => ['png','jpg','jpeg','gif','webp','ico'],
            'EXT_DOC' => ['doc','docx','odt','pdf','xml']
        ]);
    }
}