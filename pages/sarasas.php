<?php
    session_start();
    $data = unserialize(file_get_contents(__DIR__ . '/../data.bank'));
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
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
    <?php 
        $menu_settings = ['here'=> 1, 'edit' => false];
        require __DIR__ . '/../components/menu.php';
    ?>
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
                if (isset($data) && count($data) > 0){
                    usort($data,fn($a,$b)=> $a['client_surname'] <=> $b['client_surname']);
                    foreach ($data as $client) : ?>
            <li class="client-list-item">
                <div><?= $client['client_name'] ?></div>
                <div><?= $client['client_surname'] ?></div>
                <div><?= $client['client_id'] ?></div>
                <div><?= $client['client_account_number'] ?></div>
                <div><?= $client['funds'] ?></div>
                <div></div>
                <a href="http://localhost/manophp/bank_v1/pages/prideti.php?id=<?= $client['client_id'] ?>">Pridėti lėšų</a>
                <a href="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id=<?= $client['client_id'] ?>">Nuskaičiuoti lėšas</a>
                <form action="http://localhost/manophp/bank_v1/pages/istrinti.php?id=<?= $client['client_id'] ?>" method="post">
                    <button type="submit">Ištrinti</button>
                </form>
            </li>
            <?php endforeach;} else {
                echo '<li class="client-list-item"><div> Nei vieno kliento nėra :( </div></li>';
            }?>
        </ul>
    </section>
    <?php if (isset($msg)) : ?>
        <div class="delete-popup <?=  $msg['type'] === 'ok'?'not-delete':''?>">
        <div><?= $msg['text'] ?></div>
        <form action="" method="get">
            <button type="submit">OK</button>
        </form>
        </div>
    <?php endif ?>
</body>
</html>

<!-- 
    53309240061
    33309240064
    63309240062
 -->