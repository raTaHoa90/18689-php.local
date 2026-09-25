<?php

namespace Controllers;

use lib\SYS;

class BaseController {
    function __construct($name) {
        SYS::$shared['menu'] = include "menu/main.php";
    }
}