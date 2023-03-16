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
    if (isset($_GET['sort'])){
        $sort = $_GET['sort'];
    } else {
        $sort = 'sur_asc';
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
        <form class="sort" action="" method="get">
            <legend>Rūšiavimas:</legend>
            <select name="sort">
            <option value="sur_asc" <?php if ($sort == 'sur_asc') echo 'selected' ?>>Pavardė A-Z</option>
            <option value="sur_desc" <?php if ($sort == 'sur_desc') echo 'selected' ?>>Pavardė Z-A</option>
            <option value="name_asc" <?php if ($sort == 'name_asc') echo 'selected' ?>>Vardas A-Z</option>
            <option value="name_desc" <?php if ($sort == 'name_desc') echo 'selected' ?>>Vardas Z-A</option>
            <option value="id_asc" <?php if ($sort == 'id_asc') echo 'selected' ?>>Asmens kodas 1-9</option>
            <option value="id_desc" <?php if ($sort == 'id_desc') echo 'selected' ?>>Asmens kodas 9-1</option>
            <option value="acc_asc" <?php if ($sort == 'acc_asc') echo 'selected' ?>>Sąskaitos Nr. 1-9</option>
            <option value="acc_desc" <?php if ($sort == 'acc_desc') echo 'selected' ?>>Sąskaitos Nr. 9-1</option>
            <option value="fun_asc" <?php if ($sort == 'fun_asc') echo 'selected' ?>>Likutis 1-9</option>
            <option value="fun_desc" <?php if ($sort == 'fun_desc') echo 'selected' ?>>Likutis 9-1</option>
            </select>
            <button type="submit">Išrūšiuoti</button>
        </form>
        <ul class="client-list">
            <?php
                if (isset($data) && count($data) > 0){
                    if ($sort === 'sur_asc'){
                        usort($data, fn($a,$b) => $a['client_surname'] <=> $b['client_surname']);
                    } elseif ($sort === 'sur_desc') {
                        usort($data, fn($a,$b) => $b['client_surname'] <=> $a['client_surname']);
                    } elseif ($sort === 'name_asc') {
                        usort($data, fn($a,$b) => $a['client_name'] <=> $b['client_name']);
                    } elseif ($sort === 'name_desc') {
                        usort($data, fn($a,$b) => $b['client_name'] <=> $a['client_name']);
                    } elseif ($sort === 'id_asc') {
                        usort($data, fn($a,$b) => $a['client_id'] <=> $b['client_id']);
                    } elseif ($sort === 'id_desc') {
                        usort($data, fn($a,$b) => $b['client_id'] <=> $a['client_id']);
                    } elseif ($sort === 'acc_asc') {
                        usort($data, fn($a,$b) => $a['client_account_number'] <=> $b['client_account_number']);
                    } elseif ($sort === 'acc_desc') {
                        usort($data, fn($a,$b) => $b['client_account_number'] <=> $a['client_account_number']);
                    } elseif ($sort === 'fun_asc') {
                        usort($data, fn($a,$b) => $a['funds'] <=> $b['funds']);
                    } elseif ($sort === 'fun_desc') {
                        usort($data, fn($a,$b) => $b['funds'] <=> $a['funds']);
                    }
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