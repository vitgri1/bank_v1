<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ver.1 sarasas</title>
    <link rel="stylesheet" href="../css/pages/sarasas.css">
</head>
<body>
    <section class="client-list-box">
        <ul class="client-list">
            <li class="client-list-item">
                <div>Vardas</div>
                <div>Pavardė</div>
                <div>Asmens kodas</div>
                <div>Sąskaitos numeris</div>
                <div>Sąskaitos likutis</div>
                <div></div>
                <a href="http://localhost/manophp/bank_v1/pages/prideti.php">Pridėti lėšų</a>
                <a href="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php">Nuskaičiuoti lėšas</a>
                <button>Ištrinti</button>
            </li>
            <?php
                $data = unserialize(file_get_contents(dirname(__DIR__, 1) . '/data.bank'));
                if (isset($data)){
                foreach ($data as $client) : ?>
            <li class="client-list-item">
                <div><?= $client['client_name'] ?></div>
                <div><?= $client['client_surname'] ?></div>
                <div><?= $client['client_id'] ?></div>
                <div><?= $client['client_account_number'] ?></div>
                <div><?= $client['funds'] ?></div>
                <div></div>
                <a href="http://localhost/manophp/bank_v1/pages/prideti.php">Pridėti lėšų</a>
                <a href="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php">Nuskaičiuoti lėšas</a>
                <form action="http://localhost/manophp/bank_v1/pages/istrinti.php?id=<?= $client['client_id'] ?>" method="post">
                    <button type="submit">Ištrinti</button>
                </form>
            </li>
            <?php endforeach;} ?>
        </ul>
    </section>
    <?php 
    if (isset($_GET['deleted'])) : ?>
        <div class="delete-popup">
        <div>Sekmingai istrynete klienta kurio asmens kodas buvo: <?= $_GET['deleted'] ?></div>
        <form action="" method="get">
            <button type="submit">OK</button>
        </form>
        </div>
    <?php endif ?>
</body>
</html>