<?php
require './connection/index.php';
require './dao/tarefa.php';

$idUrlUser     = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);
$statusUrlUser = filter_input(INPUT_GET, 'status', FILTER_DEFAULT);

if (isset($idUrlUser)) {
    $connection = new Connection();
    $tarefa     = new Tarefa($connection->get());
    $updateStatus = $tarefa->updateStatus($idUrlUser, $statusUrlUser);
    header('Location: index.php');
}

header('Location: index.php?message=Campos obrigatorio nao foram enviados!');