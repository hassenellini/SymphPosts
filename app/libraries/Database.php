<?php
/*
 * pdo database class
 * connect to db
 * create prep statments
 * bind values
 * return rows
 */
class Database{
    private $host=DB_HOST;
    private $user=DB_USER;
    private $pass=DB_PASS;
    private $dbname=DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        $dsn='mysql:host='.$this->host.';dbname='.$this->dbname;
        $options=array(
          PDO::ATTR_PERSISTENT=>true, //persistent connection to db
          PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        );
        try {
        $this->dbh=new PDO($dsn,$this->user,$this->pass,$options);
        }catch (PDOException $e){
            $this->error=$e->getMessage();
            echo $this->error;
        }
    }

    //prepare statement
    public function query($sql){
        $this->stmt=$this->dbh->prepare($sql);
    }
    //bind values
    public function bind($param,$value,$type=null){
        if(is_null($type)){
                switch (true){
                    case is_int($value):
                        $type=PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type=PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type=PDO::PARAM_NULL;
                        break;
                    default:
                        $type=PDO::PARAM_STR;

                }
        }
        $this->stmt->bindValue($param,$value,$type);
    }

    //execute prepare statement
    public function execute(){
        return $this->stmt->execute();
    }

    //get result as array of object
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    // get single record
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }
}