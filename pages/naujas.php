<?php
session_start();
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
if (isset($_SESSION['values'])) {
    $values = $_SESSION['values'];
    unset($_SESSION['values']);
}
if (isset($_SESSION['name'], $_SESSION['logged_in'])) {
    $logged_in = $_SESSION['name'];
} else {
    header ('Location: http://localhost/manophp/bank_v1/login.php');
    die;
}
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        function isValidName ($name) {
            if(strlen($name) <= 3) {
                $_SESSION['msg'] = ['type' => 'name', 'text' => 'Vardas turi buti ilgesnis nei 3 simboliai'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }
        }
        function isValidSurame ($name) {
            if(strlen($name) <= 3) {
                $_SESSION['msg'] = ['type' => 'surname', 'text' => 'Pavarde turi buti ilgesne nei 3 simboliai'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }
        }
        function isValidID ($id) {
            if(
                strlen($id) !== 11 ||
                (int) substr($id, 0 , 1) < 1 ||
                (int) substr($id, 0 , 1) > 6 ||
                (int) substr($id, 1 , 2) < 0 ||
                (int) substr($id, 1 , 2) > 99 ||
                (int) substr($id, 3 , 2) < 1 ||
                (int) substr($id, 3 , 2) > 12 ||
                (int) substr($id, 5 , 2) < 1 ||
                (int) substr($id, 5 , 2) > 31 ||
                (int) substr($id, 7 , 3) < 0 ||
                (int) substr($id, 7 , 3) > 999 
            ) {
                $_SESSION['msg'] = ['type' => 'id', 'text' => 'Netinkamas asmens kodas'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }

            $control_sum = 0;
            foreach (array_slice((str_split($id)), 0, 9) as $index => $num){
                $control_sum += ($index + 1) * (int) $num;
            }
            $control_sum += (int) substr($id, 9 , 1);

            if ($control_sum % 11 !== 10){
                $control_coef = $control_sum % 11;
            } else {
                $control_sum = 0;
                foreach (array_slice((str_split($id)), 0, 7) as $index => $num){
                    $control_sum += ($index + 3) * (int) $num;
                }
                foreach (array_slice((str_split($id)), 7, 3) as $index => $num){
                    $control_sum += ($index + 1) * (int) $num;
                }
                if ($control_sum % 11 !== 10){
                    $control_coef = $control_sum % 11;
                } else {
                    $control_coef = 0;
                }
            }

            if((int) substr($id, 10 , 1) !== $control_coef) {
                $_SESSION['msg'] = ['type' => 'id', 'text' => 'Netinkamas asmens kodas'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }
            if (count(array_filter((unserialize(file_get_contents(__DIR__ . '/../data.bank'))), fn($c) => $c['client_id'] === $id)) !== 0) {
                $_SESSION['msg'] = ['type' => 'id', 'text' => 'Asmens kodas jau egzistuoja'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }
        }
        function isValidIBAN(&$num, $arr) {
            if(
                strlen($num) !== 20 ||
                preg_replace('/\d+/', '', $num) !== 'LT'
            ) {
                $_SESSION['msg'] = ['type' => 'iban', 'text' => 'Netinkamas saskaitos numeris'];
                $_SESSION['values'] = [
                    'name' =>  $_POST['new-client-name'],
                    'surname' => $_POST['new-client-surname'],
                    'account_number' => $_POST['new-client-account-number'],
                    'id' =>  $_POST['new-client-id']];
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php');
                die;
            }
            foreach($arr as $client) {
                if ($client['client_account_number'] === $num){
                    $num = substr_replace($num, rand(0,9), rand(2, 19), 1);
                    isValidIBAN($num, $arr);
                    break;
                }
            }
        }

        $data = unserialize(file_get_contents(__DIR__ . '/../data.bank'));

        $name = $_POST['new-client-name'];
        $surname = $_POST['new-client-surname'];
        $id = $_POST['new-client-id'];
        $iban = $_POST['new-client-account-number'];

        isValidName($name);
        isValidSurame($surname);
        isValidID($id);
        isValidIBAN($iban, $data);       

        $client = [
            'client_name' =>  $name,
            'client_surname' => $surname,
            'client_account_number' => $iban,
            'client_id' =>  $id,
            'funds' => 0,
        ];
        $data[] = $client;

        file_put_contents(__DIR__ . '/../data.bank', serialize($data));

        header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?saved=1');
        die;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank ver.1 naujas klientas</title>
    <link rel="stylesheet" href="../css/pages/naujas.css">
</head>
<body>
    <?php 
        $menu_settings = ['here'=> 2, 'edit' => false];
        require __DIR__ . '/../components/menu.php';
    ?>
    <section class="new-client-box">
        <form action="" method="post">
            <?php if(isset($msg)) : ?>
                <div class="error-message err-<?= $msg['type'] ?>">
                <?= $msg['text'] ?>
                </div>
            <?php endif ?>
            <label for="">Vardas</label>
            <input class="new-client-name" name="new-client-name" type="text" 
            <?= isset($msg) && $msg['type'] == 'name' ? 'style="margin-bottom:1em" ': ''?>
            <?= isset($values) ? 'value="'.$values['name'].'"': '' ?>
            >
            <label for="">Pavarde</label>
            <input class="new-client-surname" name="new-client-surname" type="text"
            <?= isset($msg) && $msg['type'] == 'surname' ? 'style="margin-bottom:1em"': '' ?>
            <?= isset($values) ? 'value="'.$values['surname'].'"': ''?>
            >
            <label for="">Saskaitos nr.</label>
            <input class="new-client-account-number" name="new-client-account-number" type="text" value="<?= isset($values) ? $values['account_number'] : 'LT'.rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9) ?>" readonly
            <?= isset($msg) && $msg['type'] == 'iban' ? 'style="margin-bottom:1em"': ''?>
            >
            <label for="">Asmens kodas</label>
            <input class="new-client-id" name="new-client-id" type="text"
            <?= isset($values) ? 'value="'.$values['id'].'"': '' ?>
            >
            <button type="submit">Pridėti klientą</button>
            <div <?= isset($_GET['saved']) && $_GET['saved'] == 1 ? '': 'hidden' ?>>Klientas sekmingai pridetas</div>
        </form>
    </section>
</body>
</html>