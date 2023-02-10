<?php
require './connection/index.php';
require './dao/tarefa.php';

$formRequest = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($formRequest['nome_tarefa'])) {
    $connection = new Connection();
    $tarefa = new Tarefa($connection->get());
    $tarefa->create($formRequest);
    header('Location: index.php');
} 

header('Location: index.php?message=Campos obrigatorio nao foram enviados!');