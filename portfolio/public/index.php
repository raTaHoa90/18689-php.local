<?php
chdir('..');

include_once "lib/core.php";

core_start_session();
routeGetScript();

//echo getcwd(); // - посмотреть текущий корневой путь PHP
//echo __DIR__.'<br>';
//echo __FILE__;