<?php
if (!isset($_GET['id'])){
    http_response_code(400);
    die;
}

session_start();
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
$id = $_GET['id'];
$all_clients = unserialize(file_get_contents(__DIR__ . '/../data.bank'));

$client = array_filter($all_clients, fn($c) => $c['client_id'] === $id);
if (count($client) === 0) {
    http_response_code(400);
    die;
}
$client = $client[array_key_first($client)];
$all_clients = array_filter($all_clients, fn($c) => $c['client_id'] !== $id);

function truncate($val, $f="0")
{
    if(($p = strpos($val, '.')) !== false) {
        $val = floatval(substr($val, 0, $p + 1 + $f));
    }
    return $val;
}

function isValid($val)
{
    if ((float) $val <= 0 || truncate($val, 2) != $val){
        $_SESSION['msg'] = ['type' => 'negative', 'text' => 'Įveskite tinkamą sumą!', 'value' => $val];
        die;
    } else {
        return (float) $val;
    }
}

function addZeros($val)
{
    $l = strlen($val);
    if(($p = strpos($val, '.')) !== false && $l === $p + 2) {
        $val = $val.'0';
    }
    return $val;
}