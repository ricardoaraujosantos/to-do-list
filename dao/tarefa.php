<?php

class Tarefa
{
    public int $totalPages;
    private \PDO $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function create(array $formTarefa)
    {
        $sql = "INSERT INTO tarefa(nome_tarefa, status_tarefa, dia_segunda, dia_terca, dia_quarta, dia_quinta, dia_sexta, dia_sabado, dia_domingo) VALUES(?,?,?,?,?,?,?,?,?);";

        $stmt = $this->connect->prepare($sql);

        $stmt->bindParam(1, $formTarefa['nome_tarefa']);
        $stmt->bindParam(2, $formTarefa['selectStatus']);
        $stmt->bindParam(3, $formTarefa['checkSegunda']);
        $stmt->bindParam(4, $formTarefa['checkTerca']);
        $stmt->bindParam(5, $formTarefa['checkQuarta']);
        $stmt->bindParam(6, $formTarefa['checkQuinta']);
        $stmt->bindParam(7, $formTarefa['checkSexta']);
        $stmt->bindParam(8, $formTarefa['checkSabado']);
        $stmt->bindParam(9, $formTarefa['checkDomingo']);

        $stmt->execute();
    }

    public function list($page, $searchName, $filterStatus)
    {
        $filter= $this->filters($searchName, $filterStatus);
       
        $countTasks = $this->connect->prepare("SELECT COUNT(*) FROM tarefa $filter");
        $countTasks->execute();
        $qtdTasks = $countTasks->fetchAll();
        $totalTasks = $qtdTasks[0][0];

        $limit = 8;
        $pagination = $this->pagination($limit, $totalTasks, $page);
        $this->totalPages = $pagination['totalPagination'];
      
        $sql = "SELECT * FROM tarefa $filter LIMIT $limit $pagination[offset]";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $tarefas = $stmt->fetchAll();
    
        return $tarefas;    
    }

    public function updateStatus(int $id, int $status)
    {
        $sql = "UPDATE tarefa SET status_tarefa = ? WHERE id_tarefa = ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $id);
        $stmt->execute();
    }

    private function filters($searchName, $filterStatus)
    {
        $filter = $filterStatus ? 'status_tarefa ='.$filterStatus : null;
        $search = $searchName ? "nome_tarefa LIKE '%$searchName%'" : null;
        $where = $search || $filterStatus ? 'WHERE' : null;
        $and = $search && $filter ? 'AND': Null;
        $resFilter = "$where $search $and $filter";

        return $resFilter;
    }

    private function pagination($limit, $totalTasks, $page)
    {
        $totalPages = ceil($totalTasks / $limit);
        $offSet = ($limit * $page) - $limit;
        $offSet = 'OFFSET '.$offSet ?? null;

        return $resPagination = [
            'offset'          => $offSet,
            'totalPagination' => $totalPages,
        ];
        
    }


}
