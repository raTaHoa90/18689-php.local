<?php
chdir('..');

include_once "lib/core.php";

core_start_session();

echo $_SERVER['REQUEST_URI'];