<?php
    session_start();
    $data = unserialize(file_get_contents(__DIR__ . '/../data.bank'));
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    if (isset($_SESSION['name'], $_SESSION['logged_in'])) {
        $logged_in = $_SESSION['name'];
    } else {
        header ('Location: http://localhost/manophp/bank_v1/index.php');
        die;
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
            <?php
                if (isset($data) && count($data) > 0){
                    usort($data,fn($a,$b)=> $a['client_surname'] <=> $b['client_surname']);
                    foreach ($data as $client) : ?>
            <li class="client-list-item">
                <b>Vardas</b>
                <b>Pavardė</b>
                <b>Asmens kodas</b>
                <div><?= $client['client_name'] ?></div>
                <div><?= $client['client_surname'] ?></div>
                <div><?= $client['client_id'] ?></div>
                <b>Sąskaitos numeris</b>
                <b>Sąskaitos likutis</b>
                <div></div>
                <div><?= $client['client_account_number'] ?></div>
                <div><?= $client['funds'] ?></div>
                <div></div>
                <a href="http://localhost/manophp/bank_v1/pages/prideti.php?id=<?= $client['uid'] ?>">Pridėti lėšų</a>
                <a href="http://localhost/manophp/bank_v1/pages/nuskaiciuoti.php?id=<?= $client['uid'] ?>">Nuskaičiuoti lėšas</a>
                <form action="http://localhost/manophp/bank_v1/pages/istrinti.php?id=<?= $client['uid'] ?>" method="post">
                    <button type="submit">Ištrinti</button>
                </form>
            </li>
            <?php endforeach;} else {
                echo '<li class="client-list-item"><div> Nei vieno kliento nėra! </div></li>';
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