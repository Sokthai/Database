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
   function __construct($userName, $password = "")
   {
       $this->username = $userName;
       $this->password = $password;
   }

   function connection(){
        $result = true;
        $dsn = "mysql:host=" . $this->serverName . ";dbname=" . $this->dbName;
       try {
           $this->con = new PDO($dsn, $this->username, $this->password);
           // set the PDO error mode to exception
           $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           //echo "Connected successfully";
       }catch(PDOException $e){
           $result = false;
           echo "Connection failed: " . $e->getMessage();

       }
       return $result;
   }




    function registration($info){
        $sql = 'INSERT INTO users (email, password, name, phone, city , state) VALUES (?, ?, ?, ?, ?, ?)';
        $result = true;
        $password = $this->encryption($info["password"]);
        try{
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1,  $info["email"]);
            $stmt->bindParam(2,  $password);
            $stmt->bindParam(3,  $info["name"]);
            $stmt->bindParam(4,  $info["phone"]);
            $stmt->bindParam(5,  $info["city"]);
            $stmt->bindParam(6,  $info["state"]);
            $stmt->execute();
            if ($info["role"] == "student"){
                $studentInfo = ["id" => $this->getlastInsertID(), "grade" => 0];
                $this->registerStudent($studentInfo);
            }

        }catch(PDOException $e){
            echo $e->getMessage();
            $result = false;
        }
        return $result;
   }


   function login($info){

       $sql = 'SELECT password FROM users WHERE email = ? LIMIT 1';
       $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["email"]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                if ($this->verify($info["password"], $v["password"])){
                        $result = true;
                }
            }
        }catch (PDOException $e){
           echo $e->getMessage();
       }
       return $result;
   }




    private function registerStudent($info){
       $result = false;
        $sql = 'INSERT INTO students (student_id, grade) VALUES (?,?)';
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["id"]);
            $stmt->bindParam(2, $info["grade"]);
            $stmt->execute();
            $result = true;
            //echo '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function encryption($word){
        return password_hash($word, PASSWORD_DEFAULT);
    }

    private function verify($word, $hash){
        return password_verify($word, $hash);
    }

    private function getlastInsertID(){
        return  $this->con->lastInsertId();
    }

    function __destruct()
    {
        //echo "   class is close in destruct function";
        $this->con = null; //close database connection
    }

}