<?php

require __DIR__ . '/../components/edit.php';

if (isset($_SESSION['name'], $_SESSION['logged_in'])) {
    $logged_in = $_SESSION['name'];
} else {
    header ('Location: http://localhost/manophp/bank_v1/login.php');
    die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    header ('Location: http://localhost/manophp/bank_v1/pages/prideti.php?id='.$id);
    $extra = isValid($_POST['value']);
    $client['funds'] = truncate(((float) $client['funds'] + $extra), 2);
    $all_clients[] = $client;
    $all_clients = serialize($all_clients);
    $_SESSION['msg'] = ['type' => 'deposit', 'text' => 'Sėkmingai pridėjote '.$extra. '€'];
    file_put_contents(__DIR__ . '/../data.bank', $all_clients);
    die;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ver.1 pridejimas</title>
    <link rel="stylesheet" href="../css/pages/prideti.css">
</head>
<body>
    <?php 
        $menu_settings = ['here'=> 3, 'edit' => true];
        require __DIR__ . '/../components/menu.php';
    ?>
    <section class="deposit-client edit-block">
        <div>Vardas</div>
        <div>Pavardė</div>
        <div>Sąskaitos likutis</div>
        <div>Pridedama suma</div>
        <div><?= $client['client_name'] ?></div>
        <div><?= $client['client_surname'] ?></div>
        <div><?= $client['funds'] ?></div>
        <form action="" method="post">
            <input name="value" type="text" 
            <?= isset($msg['value'])? 'value="'.$msg['value'].'"':'' ?>
            >
            <button type="submit">Pridėti lėšas</button>
        </form>
    </section>
    <?php if (isset($msg)) : ?>
        <div><?= $msg['text'] ?></div>
    <?php endif ?>
</body>
</html>