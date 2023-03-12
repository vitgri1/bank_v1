<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_GET['id'])) {
    http_response_code(400);
    die;
}

$id = $_GET['id'];

$clients = unserialize(file_get_contents(dirname(__DIR__, 1) . '/data.bank'));

$client = array_filter($clients, fn($c) => $c['client_id'] === $id);
$client = $client[array_key_first($client)];
$clients = array_filter($clients, fn($c) => $c['client_id'] != $id);

if ($client['funds'] > 0) { // if not deleted
    $clients[] = $client;
    header ('Location: http://localhost/manophp/bank_v1/pages/sarasas.php?notdeleted='.$id.'');
} else { // if deleted
    header ('Location: http://localhost/manophp/bank_v1/pages/sarasas.php?deleted='.$id.'');
}

$clients = serialize($clients);
file_put_contents(dirname(__DIR__, 1) . '/data.bank', $clients);
