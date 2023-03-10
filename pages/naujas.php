<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        function isValidIBAN(&$num, $arr) {
            if(
                strlen($num) !== 20 ||
                preg_replace('/\d+/', '', $num) !== 'LT'
            ) {
                header('Location: http://localhost/manophp/bank_v1/pages/naujas.php?IBAN=0');
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

        $data = unserialize(file_get_contents(dirname(__DIR__, 1) . '/data.bank'));

        $name = $_POST['new-client-name'];
        $surname = $_POST['new-client-surname'];
        $id = $_POST['new-client-id'];
        $iban = $_POST['new-client-account-number'];

        isValidName($name, 'name');
        isValidName($surname, 'surname');
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

        file_put_contents(dirname(__DIR__, 1) . '/data.bank', serialize($data));

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
    <section class="new-client-box">
        <form action="" method="post">
            <label for="">Vardas</label>
            <input class="new-client-name" name="new-client-name" type="text" style="margin-bottom:<?= isset($_GET['name']) && $_GET['name'] == 0 ? '1em': '0' ?>">
            <div class="error-message err-name" <?= isset($_GET['name']) && $_GET['name'] == 0 ? '': 'hidden' ?> >Vardas turi buti ilgesnis nei 3 simboliai</div>
            <label for="">Pavarde</label>
            <input class="new-client-surname" name="new-client-surname" type="text" style="margin-bottom:<?= isset($_GET['surname']) && $_GET['surname'] == 0 ? '1em': '0' ?>">
            <div class="error-message err-surname" <?= isset($_GET['surname']) && $_GET['surname'] == 0 ? '': 'hidden' ?> >Pavarde turi buti ilgesne nei 3 simboliai</div>
            <label for="">Saskaitos nr.</label>
            <input class="new-client-account-number" name="new-client-account-number" type="text" value="<?= 'LT'.rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9) ?>" readonly>
            <label for="">Asmens kodas</label>
            <input class="new-client-id" name="new-client-id" type="text">
            <div class="error-message err-id" <?= isset($_GET['ID']) && $_GET['ID'] == 0 ? '': 'hidden' ?> >Netinkamas asmens kodas</div>
            <button type="submit">Pridėti klientą</button>
            <div <?= isset($_GET['saved']) && $_GET['saved'] == 1 ? '': 'hidden' ?>>Klientas sekmingai pridetas</div>
        </form>
    </section>
</body>
</html>