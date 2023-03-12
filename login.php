<?php

// require __DIR__ . '/components/signup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $all_logins = json_decode(file_get_contents(__DIR__ . '/login.json'), 1);
    $user = array_filter($all_logins, fn($l) => $l['name'] === $_POST['login-name']);
    if (count($user) === 0){
        header('Location: http://localhost/manophp/bank_v1/login.php?name=invalid');
        die;
    }
    $user = $user[array_key_first($user)];
    if (!password_verify($_POST['login-password'] ,$user['password'])){
        header('Location: http://localhost/manophp/bank_v1/login.php?password=invalid');
        die;
    }
    echo 'Password is valid!';
// baigiau cia ^^^^^^^^-------------------------------
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
            <input name="login-name" type="text" placeholder="Name">
            <input name="login-password" type="text" placeholder="Password">
            <button type="submit">Prisijungti</button>
        </form>
        <?php if(isset($_GET['name'])) : ?>
            <div>Neegzistuoja toks prisijungimo vardas</div>
        <?php endif ?>
        <?php if(isset($_GET['password'])) : ?>
            <div>Neteisingas slaptazodis</div>
        <?php endif ?>
    </section>
</body>
</html>