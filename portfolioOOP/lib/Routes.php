<?php

namespace lib;

final class Routes {
    static private array $routes = [];
    static private ?Route $_404 = null;

    static private function _addRoute(string $type, string $path, string $binding){
        static::$routes[] = new Route($type, $path, $binding);
    }

    static function any($path, $binding){
        static::_addRoute('ANY', $path, $binding);
    }

    static function get($path, $binding){
        static::_addRoute('GET', $path, $binding);
    }

    static function post($path, $binding){
        static::_addRoute('POST', $path, $binding);
    }

    static function put($path, $binding){
        static::_addRoute('PUT', $path, $binding);
    }

    static function delete($path, $binding){
        static::_addRoute('DELETE', $path, $binding);
    }

    static function default($binding){
        static::$_404 = new Route('ANY', '', $binding);
    }

    //======================================================
    private $method, $path;

    function __construct() {
        $temp = explode('?', $_SERVER['REQUEST_URI']);
        $url = $temp[0];
        $pathes = explode('/', substr($url, 1));
        $tempPathes = [];
        foreach($pathes as $path)
            if(trim($path) != '')
                $tempPathes[] = trim($path);
                // ex//test => ex/test
                // ex / test => ex/test

        $this->method = $_SERVER['REQUEST_METHOD']; 

        if(!$tempPathes)
            $tempPathes[] = '';

        $this->path = $tempPathes;
        include_once 'configs/routes.php';
    }

    function getPath(): array{
        return $this->path;
    }

    function hasPost(): bool {
        return $this->method == 'POST' || $this->method == 'PUT';
    }

    function __invoke() {
        foreach(static::$routes as $route)
            /**
             * @var Route $route
             */
            if($route->compare($this->method, $this->path)){
                $route->Invoke();
                exit;
            }
        
        http_response_code(404);

        if(static::$_404)
            static::$_404->Invoke();
        else
            echo '<H1>404 page not found</H1>';
    }
}