<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $all_logins = json_decode(file_get_contents(dirname(__DIR__, 1) . '/login.json'), 1);
    $login = ['name' => $_POST['login-name'], 'password' => password_hash($_POST['login-password'],PASSWORD_BCRYPT)];
    $all_logins[] = $login;
    $all_logins = json_encode($all_logins);
    file_put_contents(dirname(__DIR__, 1). '/login.json', $all_logins);
}