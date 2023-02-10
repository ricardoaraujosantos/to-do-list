<?php

require './connection/index.php';
require './dao/tarefa.php';

$connection = new Connection();
$tarefa     = new Tarefa($connection->get());

$pages        = filter_input(INPUT_GET, 'page', FILTER_DEFAULT) ?? 1;

$searchUrlGet = filter_input(INPUT_GET, 'search', FILTER_DEFAULT) ?? null;
$filterUrlGet = filter_input(INPUT_GET, 'filterStatus', FILTER_DEFAULT) ?? null;
$listTarefas  = $tarefa->list($pages, $searchUrlGet ?? null, $filterUrlGet ?? null);

$totalPages   = $tarefa->totalPages;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <!-- CDN remix icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- CDn bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <title>Document</title>
</head>

<body style="background:#2C3E50;">

    <!-- Modal Cadastro-->
    <div class="modal fade" id="modalCadastro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Nova Tarefa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/desafio1/create.php" method="POST">
                        <div class="form-group my-3">
                            <label for="name">Nome da Tarefa</label>
                            <input class="form-control" type="text" id="nome_tarefa" name="nome_tarefa" placeholder="Digite..." required>
                        </div>

                        <div class="col my-4">
                            <select class="form-select" aria-label="Default select example" id="selectStatus" name="selectStatus" required>

                                <option value="1">Ativo</option>
                                <option value="2">Inativo</option>
                            </select>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkSegunda" name="checkSegunda">
                            <label class="form-check-label" for="checkSegunda">
                                Segunda-feira
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkTerca" name="checkTerca">
                            <label class="form-check-label" for="checkTerca">
                                Terça-feira
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkQuarta" name="checkQuarta">
                            <label class="form-check-label" for="checkQuarta">
                                Quarta-feira
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkQuinta" name="checkQuinta">
                            <label class="form-check-label" for="checkQuinta">
                                Quinta-feira
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkSexta" name="checkSexta">
                            <label class="form-check-label" for="checkSexta">
                                Sexta-feira
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkSabado" name="checkSabado">
                            <label class="form-check-label" for="checkSabado">
                                Sabado
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkDomingo" name="checkDomingo">
                            <label class="form-check-label" for="checkDomingo">
                                Domingo
                            </label>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success" name="cadastrarTarefa" id="cadastrarTarefa">Cadastrar Tarefa</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <section class="container border border-2 mt-4">
        <div class="row border-bottom py-3">
            <div class="col ">
                <h1 class="text-white">TO-DO</h1>
            </div>
        </div>

        <div class="row my-4">
            <div class="col">
                <h4 class="text-white">Minhas Tarefas</h4>
            </div>
            <div class="col text-end">
                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalCadastro"><i class="ri-add-line"></i> Adicionar</button>
            </div>
        </div>

        <form action="" method="GET">
            <div class="row">
                <div class="col-3">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Filtrar tarefa...">
                </div>
                <div class="col-3">
                    <select class="form-select" aria-label="Default select example" name="filterStatus" id="filterStatus">
                        <option value="" selected>Status</option>
                        <option value="1">Ativo</option>
                        <option value="2">Inativo</option>
                    </select>
                </div>
                <div class="col-3">
                    <button class="btn btn-outline-primary" type="submit">Buscar todos<i class="ri-search-eye-line"></i></button>
                </div>
            </div>
        </form>

        <?php if (isset($_GET['message'])) { ?>
            <div class="text-white my-4"><?php echo $_GET['message']; ?></div>
        <?php } ?>

        <table id="content" class="table table-dark table-striped my-4">
            <thead>
                <tr>
                    <th class="fs-5" scope="col"><i class="ri-file-list-line"></i> Tarefas</th>
                    <th class="fs-5" scope="col"><i class="ri-task-line"> Status</i></th>
                    <th class="fs-5" scope="col"><i class="ri-history-fill"> Recorrência</i></th>
                    <th class="text-center fs-5" scope="col"><i class="ri-flashlight-line"></i> Ação</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($listTarefas as $tarefa) { ?>

                    <tr>
                        <th scope="row"> <?= $tarefa['nome_tarefa'] ?> </th>

                        <?php if($tarefa['status_tarefa'] === 1) { ?>
                            <td class="text-success fw-bolder fs-6" style="width: 25%;">Ativo  <i class="ri-check-line "></i></td>
                        <?php } else { ?>
                            <td class="text-danger fw-bolder fs-6" style="width: 25%;">Inativo</td>
                        <?php } ?>

                        <td style="width: 25%;">
                            <?= $tarefa['dia_segunda'] === 1 ? "Segunda, " : null ?>
                            <?= $tarefa['dia_terca'] === 1 ? "Terça, " : null ?>
                            <?= $tarefa['dia_quarta'] === 1 ? "Quarta, " : null ?>
                            <?= $tarefa['dia_quinta'] === 1 ? "Quinta, " : null ?>
                            <?= $tarefa['dia_sexta'] === 1 ? "Sexta, " : null ?>
                            <?= $tarefa['dia_sabado'] === 1 ? "Sabado, " : null ?>
                            <?= $tarefa['dia_domingo'] === 1 ? "Domingo " : null ?>
                        </td>

                        <?php if ($tarefa['status_tarefa'] === 1) { ?>
                            <td class="text-center" style="width: 25%;">
                                <a 
                                    data-id="<?=$tarefa['id_tarefa']?>" 
                                    data-status="<?=$tarefa[2]?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modal-active" 
                                    style="width: 6rem;" 
                                    type="button" 
                                    class="btn btn-outline-danger btn-modal">
                                    Inativar
                                </a>
                            </td>
                        <?php } else { ?>
                            <td class="text-center" style="width: 25%;">
                                <a 
                                    data-id="<?= $tarefa['id_tarefa'] ?>"
                                    data-status="<?=$tarefa[2]?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modal-active"  
                                    style="width: 6rem;" 
                                    type="button" 
                                    class="btn btn-outline-success btn-modal">
                                    Ativar
                                </a>
                            </td>
                        <?php } ?>

                    </tr>

                <?php } ?>

            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-start">
                
                <?php $search = $searchUrlGet ? '&search='.$searchUrlGet : null;?>
                <?php $filter = $filterUrlGet ? '&filterStatus='.$filterUrlGet : null;?>

                <?php 
                    $pagePrev = $pages - 1; 

                    if($pagePrev > 0) { ?>

                        <li class="page-item me-2">
                            <a class="page-link text-danger fw-bolder" href="index.php?page=<?php echo $pagePrev .$search .$filter ?>" aria-label="Previous">
                                <span aria-hidden="true"><i class="ri-skip-back-line"></i></span>
                            </a>
                        </li>

                <?php } ?>

                <?php for($i = 0; $i < $totalPages; $i++){ ?>

                        <?php $classActive = $i + 1 == $pages ? "bg-primary-subtle" : "bg-light";?>

                        <?php if($search || $filter){ ?>

                            <li class="page-item">
                                <a class="page-link text-black <?php echo $classActive ?>" href="index.php?page=<?php echo $i + 1 .$search .$filter ?>"><?php echo $i + 1 ?></a>
                            </li>

                        <?php } else { ?>

                            <li class="page-item">
                                <a class="page-link text-black <?php echo $classActive ?>" href="index.php?page=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a>
                            </li>

                       <?php } ?>
                <?php } ?>
               
               <?php
                    $pageNext = $pages + 1;

                    if($pageNext <= $totalPages) { ?>

                        <li class="page-item ms-2">
                            <a class="page-link text-success fw-bolder" href="index.php?page=<?php echo $pageNext .$search .$filter ?>" aria-label="Next">
                                <span aria-hidden="true"><i class="ri-skip-forward-line"></i></span>
                            </a>
                        </li>

                <?php } ?>
            </ul>
        </nav>

    </section>

    <section>
       

        <!-- Modal Active -->
        <div class="modal fade" id="modal-active" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-4 fw-bolder" id="exampleModalLabel">Confirme a ação!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input class="border border-0 text-center text-success fw-bolder fs-5" type="text" name="status" id="status" value=""> 
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a id="btnAction" href="" type="button" class="btn btn-success">Confimar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

    <script type="text/javascript">
        $('.btn-modal').on('click', function(event) {
            var modal = $(this).data('target'),
                id = $(this).data('id');
                status = $(this).data('status');

            $(modal).find('id').val(id);
            $(modal).find('status').val(status);
           
            $('#status').attr('value', status);
            
            if(status == 1){
                $('#btnAction').attr('href', 'update.php?id=' + id + '&status=' + 2);
                $('#status').attr('value', 'Deseja inativar a tarefa?');
            }else{
                $('#btnAction').attr('href', 'update.php?id=' + id + '&status=' + 1);
                $('#status').attr('value', 'Deseja ativar a tarefa?');
            }  
        });

    </script>
</body>

</html>