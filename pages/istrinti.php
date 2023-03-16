<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_GET['id'])) {
    http_response_code(400);
    die;
}

$id = $_GET['id'];

$clients = unserialize(file_get_contents(__DIR__ . '/../data.bank'));

$client = array_filter($clients, fn($c) => $c['uid'] === $id);
$client = $client[array_key_first($client)];
$clients = array_filter($clients, fn($c) => $c['uid'] != $id);

if ($client['funds'] > 0) { // if not deleted
    $_SESSION['msg'] = ['type' => 'error', 'text' => 'Negalite istrinti saskaitoje pinigu turincio kliento kurio asmens kodas yra: '.$id];
} else { // if deleted
    $_SESSION['msg'] = ['type' => 'ok', 'text' => 'Sėkmingai ištrynėte klientą kurio asmens kodas buvo: '.$id];
    $clients = serialize($clients);
    file_put_contents(__DIR__ . '/../data.bank', $clients);
}

header ('Location: http://localhost/manophp/bank_v1/pages/sarasas.php');