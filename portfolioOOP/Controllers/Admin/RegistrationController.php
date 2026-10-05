<?php

namespace Controllers\Admin;

use DATA\Users;
use lib\MailAgent;
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

        $email = $_POST['email'] ?? '';
        if(!$email || !SYS::emailValidation($email)){
            SYS::$session['error'] = 'Неправильно введен Email';
            SYS::back();
        }


        $login = trim($_POST['login']);
        $password = $_POST['pass'];
        $user = Users::create([
            'password' => $password,
            'login' => $login,
            'email' => $email
        ]);

        if($user){
            $user->setPassword($password)->save();


            $mail = new MailAgent();
            $mail->addAddress($user->email);
            $mail->setMessage('Вы зарегестрировались на сайте Портфолио', <<<ENDMESSAGE
                <!DOCTYPE html>
                <html>
                    <head></head>
                    <body>
                        Вы зарегистрировались на нашем сайте портфолио!<br>
                        ваш логин: <b>$login</b><br>
                        пароль: <b>$password</b><br><br>
                        Добро пошаловать!!!
                    </body>
                </html>
            ENDMESSAGE);
            $mail->send();


        } else {
            SYS::$session['error'] = 'Не удалось создать пользователя';
            SYS::$session['login'] = $_POST['login'] ?? '';
            SYS::back();
        }

        
        SYS::redirect('/admin/auth');
    }
}