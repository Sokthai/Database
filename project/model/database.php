<?php

/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2/23/2019
 * Time: 2:36 PM
 */
class database
{
   private $dbName = "db2ta";
   private $password = "";
   private $username = "user";
   private $serverName = "localhost";
   private $con;
   private $dsn;
   function __construct($userName, $password = "")
   {
       $this->username = $userName;
       $this->password = $password;
   }

   function connection(){
        $dsn = "mysql:host=" . $this->serverName . ";dbname=" . $this->dbName;
       try {
           $this->con = new PDO($dsn, $this->username, $this->password);
           // set the PDO error mode to exception
           $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           echo "Connected successfully";
       }catch(PDOException $e){
           echo "Connection failed: " . $e->getMessage();
       }

       return $this->con;
   }

   function __destruct()
   {
        $this->con = null; //close database connection
   }


    function registration($connection, $info){
        $sql = 'INSERT INTO users (email, password, name, phone, city , state) VALUES (?, ?, ?, ?, ?, ?)';
        $result = true;
        $password = $this->encryption($info["password"]);
        try{
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(1,  $info["email"]);
            $stmt->bindParam(2,  $password);
            $stmt->bindParam(3,  $info["name"]);
            $stmt->bindParam(4,  $info["phone"]);
            $stmt->bindParam(5,  $info["city"]);
            $stmt->bindParam(6,  $info["state"]);
            $stmt->execute();
            if ($info["role"] == "student"){
                $studentInfo = ["id" => $this->getlastInsertID($connection), "grade" => 0];
                $this->registerStudent($connection, $studentInfo);
            }

        }catch(PDOException $e){
            echo $e->getMessage();
            $result = false;
        }
        return $result;
   }


    private function getlastInsertID($connection){
        return $connection->lastInsertId();
    }

    private function registerStudent($connection, $info){
        $sql = 'INSERT INTO students (student_id, grade) VALUES (?,?)';
        try{
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(1, $info["id"]);
            $stmt->bindParam(2, $info["grade"]);
            $stmt->execute();
            //echo '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    private function encryption($word){
        return password_hash($word, PASSWORD_DEFAULT);
    }

    private function verify($word, $hash){
        return password_verify($word, $hash);
    }

}