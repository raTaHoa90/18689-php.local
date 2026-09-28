<?php

namespace Controllers\Admin;

use Controllers\TraitFileAndDirUtils;
use lib\SYS;

class CatalogsController extends BaseAdminController {

    use TraitFileAndDirUtils;

    public string $path;

    function ajax_init_catalog(): bool {
        $this->path = $this->user->pathPublic();
        if(!is_dir($this->path))
            mkdir($this->path);

        $topCatalog = true;
        if(isset($_POST['path']) && $_POST['path']){
            $this->path .= '/'.$_POST['path'];
            $topCatalog = false;
        }

        return $topCatalog;
    }

    function delete_dir($path){
        if(!is_dir($path)) return;

        $entries = scandir($path); // альтернативный способ получить все содержимое каталога разом в виде массива
        foreach($entries as $entry)
            if($entry != '.' && $entry != '..'){
                $fullPath = $path.'/'.$entry;
                if(filetype($fullPath) == 'dir')
                    $this->delete_dir($fullPath); // рекурсивно удаляем содержимое вложенного каталога
                else
                    unlink($fullPath); // удалить файл
            }
        rmdir($path); // удаляем каталог
    }

    //=================================================
    // GET
    //=================================================

    function index(){
        SYS::view('admin/catalogs', [
            'caption' => 'Портфолио: '.$this->user->getName(),
        ]);
    }

    //=================================================
    // POST, AJAX
    //=================================================

    function getCatalogs(){
        $topCatalog  = $this->ajax_init_catalog();

        if(!is_dir($this->path)){
            echo json_encode([]);
            exit;
        }

        echo json_encode($this->LoadCatalogs($this->path, $topCatalog));
    }

    function createDir(){
        $this->ajax_init_catalog();

        $dirname = ($_POST['dirname'] ?? ''); 
        if(!preg_match('/^[\wа-яА-ЯёЁ_+=\(\) !\.-]+$/i', $dirname))
            $this->ajax_error('каталог с таким именем недопустим');

        $fullpath = $this->path.'/'.($_POST['dirname'] ?? '');
        if(is_dir($fullpath))
            $this->ajax_error('такой каталог уже существует');

        mkdir($fullpath);

        echo json_encode(['ok'=>true]); 
    }

    function uploadFile(){
        $this->ajax_init_catalog();
        if(!isset($_FILES['upFile']))
            $this->ajax_error('Нет файлов для загрузки');

        if(!is_dir($this->path))
            $this->ajax_error('Такого каталога не существует');

        $result = [];
        foreach($_FILES['upFile']['error'] as $i => $error)
            if($error == 0){
                $name = basename($_FILES['upFile']['name'][$i]);
                $fullPath = $this->path.'/'.$name;
                move_uploaded_file($_FILES['upFile']['tmp_name'][$i], $fullPath);

                $size = filesize($fullPath);
                $prefix = $this->getSizeFile($size);

                $result[] = [
                    'name' => $name,
                    'size' => ((int)$size).' '.$prefix,
                    'ext'  => pathinfo($fullPath, PATHINFO_EXTENSION),
                    'created_at' => date('d.m.Y H:i', filectime($fullPath)),
                    'type' => filetype($fullPath)
                ];
            }

        echo json_encode($result);
    }

    function deleteDir(){
        $this->ajax_init_catalog();

        $fullpath = $this->path.'/'.($_POST['dirname'] ?? '');
        if(!is_dir($fullpath))
            $this->ajax_error('такой каталог отсутствует');

        $this->delete_dir($fullpath);

        echo json_encode(['ok'=>true]);
    }

    function deleteFile(){
        $this->ajax_init_catalog();
        $fullPath = $this->path.'/'.($_POST['filename'] ?? '');
        if(!file_exists($fullPath))
            $this->ajax_error('такой файл отсутствует');

        unlink($fullPath);

        echo json_encode(['ok'=>true]);
    }
}