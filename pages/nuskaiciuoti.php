<?php

require __DIR__ . '/../components/edit.php';

if (isset($_SESSION['name'], $_SESSION['logged_in'])) {
    $logged_in = $_SESSION['name'];
} else {
    header ('Location: http://localhost/manophp/bank_v1/index.php');
    die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    header ('Location: http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id='.$id);
    $extra = isValid($_POST['value']);
    if ((float) $client['funds'] - $extra < 0){
        $_SESSION['msg'] = ['type' => 'negative', 'text' => 'Negalite nuskaiciuoti daugiau lesu nei klientas turi!', 'value' => $extra];
        die;
    }
    $client['funds'] = truncate(((float) $client['funds'] - $extra), 2);
    $all_clients[] = $client;
    $all_clients = serialize($all_clients);
    $_SESSION['msg'] = ['type' => 'withdraw', 'text' => 'Sėkmingai nuskaičiavote '.$extra. '€'];
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
    <title>Bank ver.1 nuskaiciavimas</title>
    <link rel="stylesheet" href="../css/pages/nuskaiciuoti.css">
</head>
<body>
    <?php 
        $menu_settings = ['here'=> 4, 'edit' => true];
        require __DIR__ . '/../components/menu.php';
    ?>
    <section class="withdraw-client edit-block">
        <div>Vardas</div>
        <div>Pavardė</div>
        <div>Sąskaitos likutis</div>
        <div>Nuskaičiuojama suma</div>
        <div><?= $client['client_name'] ?></div>
        <div><?= $client['client_surname'] ?></div>
        <div><?= $client['funds'] ?></div>
        <form action="" method="post">
        <input name="value" type="text"
        <?= isset($msg['value'])? 'value="'.$msg['value'].'"':'' ?>
        >
            <button type="submit">Nuskaičiuoti lėšas</button>
        </form>
    </section>
    <?php if (isset($msg)) : ?>
        <div><?= $msg['text'] ?></div>
    <?php endif ?>
</body>
</html>