<?php

function GET_registration(){
    $data = [
        'caption' => 'Регистрация',
        'menu' => include 'menu/auth.php'
    ];

    if(isset($_SESSION['error'])){
        $data['error'] = $_SESSION['error'];
        $data['login'] = $_SESSION['login'];
        unset($_SESSION['login']);
        unset($_SESSION['error']);
    }

    view('admin/registration', $data);
}

function POST_registration(){
    if(!isset($_POST['pass']) || !$_POST['pass'] || 
        $_POST['pass'] != ($_POST['pass_two'] ?? '')
    ){
        $_SESSION['error'] = 'Несовпадают введенные пароли';
        $_SESSION['login'] = $_POST['login'] ?? '';
        toBack();
    }

    if(!isset($_POST['login']) || !$_POST['login']){
        $_SESSION['error'] = 'Недопустимо вводить пустой логин';
        $_SESSION['login'] = $_POST['login'] ?? '';
        toBack();
    }

    $u = getUserByLogin($_POST['login']);
    if($u){
        $_SESSION['error'] = 'Пользователь с таким логином уже существует';
        $_SESSION['login'] = $_POST['login'] ?? '';
        toBack();
    }


    $user = [
        'password' => $_POST['pass'],
        'login' => $_POST['login']
    ];

    if(!createUserData($user)){
        $_SESSION['error'] = 'Не удалось создать пользователя';
        $_SESSION['login'] = $_POST['login'] ?? '';
        toBack();
    }

    redirect('/admin/auth');
}