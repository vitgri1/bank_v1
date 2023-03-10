<?php
if (!isset($_GET['id'])){
    http_response_code(400);
    die;
}

$id = $_GET['id'];
$all_clients = unserialize(file_get_contents(dirname(__DIR__, 1) . '/data.bank'));

$client = array_filter($all_clients, fn($c) => $c['client_id'] === $id);
$client = $client[array_key_first($client)];
$all_clients = array_filter($all_clients, fn($c) => $c['client_id'] !== $id);