<?php

require dirname(__DIR__, 1) . '/components/edit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if ((int) $_POST['value'] < 0){
        $extra = 0;
    } else {
        $extra = (int) $_POST['value'];
    }
    if ((int) $client['funds'] - $extra < 0){
        header ('Location: http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id='.$id.'&negative=1');
        die;
    } else {
        header ('Location: http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id='.$id);
    }
    $client['funds'] = (int) $client['funds'] - $extra;
    $all_clients[] = $client;
    $all_clients = serialize($all_clients);
    file_put_contents(dirname(__DIR__, 1) . '/data.bank', $all_clients);
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
    <section class="withdraw-client edit-block">
        <div>Vardas</div>
        <div>Pavardė</div>
        <div>Sąskaitos likutis</div>
        <div>Nuskaičiuojama suma</div>
        <div></div>
        <div><?= $client['client_name'] ?></div>
        <div><?= $client['client_surname'] ?></div>
        <div><?= $client['funds'] ?></div>
        <form action="" method="post">
            <input name="value" type="number">
            <button type="submit">Nuskaičiuoti lėšas</button>
        </form>
    </section>
    <?php 
    if (isset($_GET['negative'])) : ?>
        <div class="delete-popup">
        <div>Negalite nuskaiciuoti daugiau lesu nei klientas turi!</div>
        <form action="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id=<?= $_GET['id'] ?>" method="post">
            <button type="submit">OK</button>
        </form>
        </div>
    <?php endif ?>
</body>
</html>