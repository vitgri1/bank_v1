<?php

session_start();
// require __DIR__ . '/db/signup.php';
if (isset($_SESSION['invalid'])) {
    $error = $_SESSION['invalid'];
    unset($_SESSION['invalid']);
}
if (isset($_SESSION['values'])) {
    $values = $_SESSION['values'];
    unset($_SESSION['values']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $_SESSION['values'] = ['name' =>  $_POST['login-name'], 'password' => $_POST['login-password']];
    $all_logins = json_decode(file_get_contents(__DIR__ . '/db/login.json'), 1);
    $user = array_filter($all_logins, fn($l) => $l['name'] === $_POST['login-name']);
    if (count($user) === 0){
        $_SESSION['invalid'] = 'Neteisingas prisijungimo vardas';
        header('Location: http://localhost/manophp/bank_v1/login.php');
        die;
    }
    $user = $user[array_key_first($user)];
    if (!password_verify($_POST['login-password'], $user['password'])){
        $_SESSION['invalid'] = 'Neteisingas slaptažodis';
        header('Location: http://localhost/manophp/bank_v1/login.php');
        die;
    }
    $_SESSION['logged_in'] = true;
    $_SESSION['name'] = $user['name'];
    unset($_SESSION['values']);
    header('Location: http://localhost/manophp/bank_v1/pages/sarasas.php');
    die;
}

if (isset($_SESSION['name'], $_SESSION['logged_in'])) {
    unset($_SESSION['name'], $_SESSION['logged_in']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ver.1 menu</title>
    <link rel="stylesheet" href="./css/pages/home.css">
</head>
<body>
    <section class="login">
        <form action="" method="post">
            <input name="login-name" type="text" placeholder="Name"
            <?= isset($values)? 'value="'.$values['name'].'"':'' ?>
            >
            <input name="login-password" type="text" placeholder="Password"
            <?= isset($values)? 'value="'.$values['password'].'"':'' ?>
            >
            <button type="submit">Prisijungti</button>
        </form>
        <?php if(isset($error)) : ?>
            <div><?= $error ?></div>
        <?php endif ?>
    </section>
</body>
</html>