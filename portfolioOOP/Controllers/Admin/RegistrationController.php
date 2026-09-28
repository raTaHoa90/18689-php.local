<?php

namespace Controllers\Admin;

use DATA\Users;
use lib\SYS;

class RegistrationController extends BaseAuthController {

    function index(){
        if(isset(SYS::$session['login'])){
            SYS::$shared['login'] = SYS::$session['login'];
            unset(SYS::$session['login']);
        }

        SYS::view('admin/registration', [
            'caption' => 'Регистрация',
        ]);
    }

    // POST

    function registers(){
        if(!isset($_POST['pass']) || !$_POST['pass'] || 
            $_POST['pass'] != ($_POST['pass_two'] ?? '')
        ){
            SYS::$session['error'] = 'Несовпадают введенные пароли';
            SYS::$session['login'] = $_POST['login'] ?? '';
            SYS::back();
        }

        if(!isset($_POST['login']) || !$_POST['login']){
            SYS::$session['error'] = 'Недопустимо вводить пустой логин';
            SYS::$session['login'] = $_POST['login'] ?? '';
            SYS::back();
        }

        $u = Users::getUserByLogin($_POST['login']);
        if($u){
            SYS::$session['error'] = 'Пользователь с таким логином уже существует';
            SYS::$session['login'] = $_POST['login'] ?? '';
            SYS::back();
        }

        $user = Users::create([
            'password' => $_POST['pass'],
            'login' => $_POST['login']
        ]);

        if(!$user){
            SYS::$session['error'] = 'Не удалось создать пользователя';
            SYS::$session['login'] = $_POST['login'] ?? '';
            SYS::back();
        }
        
        SYS::redirect('/admin/auth');
    }
}