

<?php
    
class Connection
{
    protected \PDO $conn;

    public function __construct()
    {
        $userName= "ricardo";
        $password= "#suasenha";

        try{
            $this->conn = new PDO("mysql:host=127.0.0.1:3306;dbname=to_do_list", $userName , $password );

        }catch(PDOException $ex){
            die("Erro na Conecxão: " .$ex->getMessage());
        }
    }

    public function get(): \PDO
    {
        return $this->conn;
    }
    
}