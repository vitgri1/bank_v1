<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = unserialize(file_get_contents(dirname(__DIR__, 1) . '/data.bank'));

        function isValidName ($name , $what) {
            if(strlen($name) <= 3) {
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?'.$what.'=0');
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
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?ID=0');
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
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?ID=0');
                die;
            }
        }

        function isValidIBAN($num){
            if(
                strlen($num) !== 24 ||
                substr($num, 0 , 2) !== 'LT' ||
                (int) substr($num, 2 , 2) <= 99 ||
                (int) substr($num, 5 , 4) <= 9999 ||
                (int) substr($num, 10 , 4) <= 9999 ||
                (int) substr($num, 15 , 4) <= 9999 ||
                (int) substr($num, 20 , 4) <= 9999
            ) {
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?IBAN=0');
                die;
            }
        }

        isValidName($_POST['new-client-name'], 'name');
        isValidName($_POST['new-client-surname'], 'surname');
        isValidID($_POST['new-client-id']);

        $client = [
            'client_name' => $_POST['new-client-name'],
            'client_surname' => $_POST['new-client-surname'],
            'client_account_number' => $_POST['new-client-account-number'],
            'client_id' => $_POST['new-client-id'],
            'funds' => 0,
        ];
        $data[] = $client;

        file_put_contents(dirname(__DIR__, 1) . '/data.bank', serialize($data));

        header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?');
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
    <section class="new-client-box">
        <form action="" method="post">
            <label for="">Vardas</label>
            <input class="new-client-name" name="new-client-name" type="text" style="margin-bottom:<?= isset($_GET['name']) && $_GET['name'] == 0 ? '1em': '0' ?>">
            <div class="error-message err-name" <?= isset($_GET['name']) && $_GET['name'] == 0 ? '': 'hidden' ?> >Vardas turi buti ilgesnis nei 3 simboliai</div>
            <label for="">Pavarde</label>
            <input class="new-client-surname" name="new-client-surname" type="text" style="margin-bottom:<?= isset($_GET['surname']) && $_GET['surname'] == 0 ? '1em': '0' ?>">
            <div class="error-message err-surname" <?= isset($_GET['surname']) && $_GET['surname'] == 0 ? '': 'hidden' ?> >Pavarde turi buti ilgesne nei 3 simboliai</div>
            <label for="">Saskaitos nr.</label>
            <input class="new-client-account-number" name="new-client-account-number" type="text" value="<?= 'LT'.rand(0,9).rand(0,9).' '.rand(0,9).rand(0,9).rand(0,9).rand(0,9).' '.rand(0,9).rand(0,9).rand(0,9).rand(0,9).' '.rand(0,9).rand(0,9).rand(0,9).rand(0,9).' '.rand(0,9).rand(0,9).rand(0,9).rand(0,9) ?>" readonly>
            <label for="">Asmens kodas</label>
            <input class="new-client-id" name="new-client-id" type="text">
            <button type="submit">Pridėti klientą</button>
        </form>
    </section>
</body>
</html>