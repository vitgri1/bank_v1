<?php

require dirname(__DIR__, 1) . '/components/edit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $client['funds'] = (int) $client['funds'] - (int) $_POST['value'];
    $all_clients[] = $client;
    $all_clients = serialize($all_clients);
    file_put_contents(dirname(__DIR__, 1) . '/data.bank', $all_clients);
    header ('Location: http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id='.$id);
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
</body>
</html>