<?php

namespace Controllers;

trait TraitFileAndDirUtils {
    function getSizeFile(int &$size): string {
        $prefix = 'bytes';
        while($size > 1024){
            $size = $size / 1024;
            $prefix = match($prefix){
                'bytes' => 'kb',
                'kb' => 'mb',
                'mb' => 'gb'
            };
        }
        return $prefix;
    }

    function LoadCatalogs(string $path, bool $topCatalog) {
        $result = [];
        $dir = dir($path);
        while(false !== ($name = $dir->read()))
            if($name != '.' && (!$topCatalog || $name != '..')){
                $fullPath = $path.'/'.$name;
                $data = ['name' => $name];

                if(filetype($fullPath) != 'dir') {
                    $size = filesize($fullPath);
                    $prefix = $this->getSizeFile($size);

                    $data['size'] = ((int)$size).' '.$prefix;
                    $data['ext'] = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $data['created_at'] = date('d.m.Y H:i', filectime($fullPath));
                    $data['type'] = filetype($fullPath);
                } else
                    $data['type'] = 'dir';

                $result[] = $data;
            }

        usort($result, function($a, $b){
            if($a['type'] == $b['type'])
                return $a['name'] <=> $b['name'];
            elseif($a['type'] == 'dir')
                return -1;
            else
                return 1;
        });

        return $result;
    }
}