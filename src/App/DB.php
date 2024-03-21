<?php 

namespace App;

class DB 
{
  private $pdo_object;
  private $connection;
  private static $instance = null;

  private function __construct(){}

  public static function getInstance(){
    if(self::$instance === null){
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getConnection(array $db_config){
    if($this->connection instanceof \PDO){
      return $this;
    };

    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";



    try{
      $this->connection = new \PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
    }catch(\PDOException $e){
      die;
      throw new \Exception($e->getMessage());
    }

    return $this;
  }

  public function query($query, $params = []){

    try{
      $this->pdo_object = $this->connection->prepare($query);
      $this->pdo_object->execute($params);


      return $this;
    }catch(\PDOExeption $e){
      throw new \Exception($e->getMessage());
    }
  }

  public function findAll(){
    return $this->pdo_object->fetchAll();
  }
}