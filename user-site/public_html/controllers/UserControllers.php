<?php
include_once SITE_ROOT . '/database/db.php';
//include_once SITE_ROOT . '/flashMessage.php';

$email = '';
$password = '';
$admin = 0;
$errMsg = [];
//Авторизация пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        array_push($errMsg,'Не все поля заполнены');
    } else {
        $user = get_user_by_email('users', ['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            userAuth($user);
        } else {
            array_push($errMsg,'Почта либо пароль введены неверно');
        }

    }
}

//Добавление юзера в сессию
function userAuth($user)
{
    $_SESSION['email'] = $user['email'];
    $_SESSION['admin'] = $user['admin'];
    if ($_SESSION['admin']) {
        // header();

    } else {
        header('location: ' . BASE_URL);

    }
}
//Регистрация пользваотеля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registr'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === '' || $password === '') {
        array_push($errMsg,'Не все поля заполнены');
    } else {

        $user = get_user_by_email('users',['email'=>$email]);

        if($user){
            array_push($errMsg,'Такой пользователь уже есть');
        }else{
            $password = password_hash($password,PASSWORD_DEFAULT);
            $user = [
                'email'=>$email,
                'password' => $password,
                'admin' => $admin
            ];
            add_user('users',$user);
            $user = get_user_by_email('users',['email'=>$email]);
            userAuth($user);
        }
    }
}