<?php

require dirname(__DIR__, 1) . '/components/edit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if ((int) $_POST['value'] < 0){
        $extra = 0;
    } else {
        $extra = (int) $_POST['value'];
    }
    $client['funds'] = (int) $client['funds'] + $extra;
    $all_clients[] = $client;
    $all_clients = serialize($all_clients);
    file_put_contents(dirname(__DIR__, 1) . '/data.bank', $all_clients);
    header ('Location: http://localhost/manophp/bank_v1/pages/prideti.php?id='.$id);
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
    <section class="deposit-client edit-block">
        <div>Vardas</div>
        <div>Pavardė</div>
        <div>Sąskaitos likutis</div>
        <div>Pridedama suma</div>
        <div><?= $client['client_name'] ?></div>
        <div><?= $client['client_surname'] ?></div>
        <div><?= $client['funds'] ?></div>
        <form action="" method="post">
            <input name="value" type="number">
            <button type="submit">Pridėti lėšas</button>
        </form>
    </section>
</body>
</html>