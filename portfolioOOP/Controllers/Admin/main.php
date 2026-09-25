<?php

function GET_default(){
    $user = AutoAuth();
    if($user === null)
        redirect('/admin/auth');

    $menu = include 'menu/admin.php';

    view('admin/main', [
        'caption' => 'Панель администратора',
        'menu' => $menu
    ]);
}