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
//       echo "connected successfully";
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
            if ($info["role"] == "student"){ //for student
                $studentInfo = ["id" => $this->getlastInsertID(), "grade" => 0, "status" => $info["status"]];
                $this->registerStudent($studentInfo);
            }else{ //for parent
                $parentInfo = ["id" => $this->getlastInsertID(), "moderator" => $info["moderator"]];
                $this->registerParent($parentInfo);
            }

        }catch(PDOException $e){
            echo $e->getMessage();
            $result = false;
        }
        return $result;
   }


   function login($info){ //return the name of user if login successfully or false otherwise

       $sql = 'SELECT id, name, email, phone, city, state, password  FROM USERS WHERE email = ? LIMIT 1';
       $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["email"]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                if ($this->verify($info["password"], $v["password"])){
                    $result = $this->setUpUserInfo($info["email"], $v);
                }
            }
        }catch (PDOException $e){
           echo $e->getMessage();
       }
       return $result;
   }

   private function setUpUserInfo($email, $v){
       if ($this->isParentLogin($email)){ //if parent login
           $result = [
               "id" => $v["id"],
               "name" => $v["name"],
               "email" => $v["email"],
               "phone" => $v["phone"] ,
               "city" => $v["city"],
               "state" => $v["state"],
               "role" => "parent",
               "moderator" => $this->isModertor($v["id"]),
               "child" => $this->getChild($v["email"])
           ];
       }else{ //if student login
           $result = [
               "id" => $v["id"],
               "name" => $v["name"],
               "email" => $v["email"],
               "phone" => $v["phone"] ,
               "city" => $v["city"],
               "state" => $v["state"],
               "role" => "student",
               "mentee" => $this->isMentee($v["id"]),
               "mentor" => $this->isMentor($v["id"]),
               "parent" => $this->getParent($v["email"])
           ];
       }
       return $result;
   }


   function getAllUserInfo($id){
       $sql  = 'SELECT id, name, email, phone, city, state, password  FROM USERS WHERE id = ? LIMIT 1';
       $result = [];
       try{
           $stmt =  $this->con->prepare($sql);
           $stmt->bindParam(1, $id);
           $stmt->execute();
           $stmt->setFetchMode(PDO::FETCH_ASSOC);
           foreach($stmt->fetchAll() as $k => $v) {
               $result = $this->setUpUserInfo($v["email"], $v);
           }
       }catch(PDOException $e){
           echo $e->getMessage();
       }
       return $result;

   }

    function notExistEmail($email){ //check if the email is not exist in the db
       $sql = 'SELECT COUNT(*) FROM users WHERE email = ?';
       return !$this->exist($sql, $email);
    }

    function updateStudent($info){
        $sql = 'UPDATE USERS SET name = ? , email = ?, phone = ?, city = ?, state = ? WHERE id = ?';
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["name"]);
            $stmt->bindParam(2, $info["email"]);
            $stmt->bindParam(3, $info["phone"]);
            $stmt->bindParam(4, $info["city"]);
            $stmt->bindParam(5, $info["state"]);
            $stmt->bindParam(6, $info["id"]);
            $result = $stmt->execute();
            if ($info["mentor"] != null) {
                $this->updateMentor($info["mentor"], $info["id"]);
            }

            if ($info["mentee"] != null) {
                $this->updateMentor($info["mentee"], $info["id"]);
            }

        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }


    private function exist($sql, $info){
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info);
            $stmt->execute();
            $result = ($stmt->fetchColumn() == 0)? false : true;
        }catch(PDOException $e){
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
            if ($info["status"] == 0 || $info["status"] == 2){ //if select mentee or both
                    $this->mentee($info["id"]);
            }
            if ($info["status"] == 1 || $info["status"] == 2) { //if select mentor or both
                    $this->mentor($info["id"]);
            }
            //echo '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function updateMentor($value, $id){
        $sql = 'UPDATE mentees SET mentee_id = ' . $value . ' WHERE id = ?';
        $this->menteeMentor($sql, $id);
    }

    private function updateMentee($value, $id){
        $sql = 'UPDATE mentors SET mentor_id = ' . $value . ' WHERE id = ?';
        $this->menteeMentor($sql, $id);
    }

    private function mentee($id){
        $sql = 'INSERT INTO mentees (mentee_id) VALUES (?)';
        $this->menteeMentor($sql, $id);
    }

    private function mentor($id){
        $sql = 'INSERT INTO mentors (mentor_id) VALUES (?)';
        $this->menteeMentor($sql, $id);
    }


    private function menteeMentor($sql, $id){
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $result = true;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function isMentee($id){
        $sql = "SELECT * FROM mentees WHERE mentee_id = ?";
        return $this->exist($sql, $id);
    }

    private function isMentor($id){
        $sql = "SELECT * FROM mentors WHERE mentor_id = ?";
        return $this->exist($sql, $id);
    }

    private function registerParent($info){
        $result = false;
        $sql = 'INSERT INTO parents (parent_id) VALUES (?)';
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["id"]);
            $stmt->execute();
            $result = true;
            if ($info["moderator"] != false){ //register if parent want to be moderator
                $this->moderator($info["id"]);
            }
            //echo '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function moderator($id){
        $result = false;
        $sql = 'INSERT INTO moderators (moderator_id) VALUES (?)';
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $result = true;
            //echo '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function isModertor($id){
        $sql = "SELECT * FROM moderators WHERE moderator_id = ?";
        return $this->exist($sql, $id);
    }

//select * from users where id in (select p.student_id from parenting p, users u where u.email = "test@gmail.com" and u.id = p.parent_id)



    private function encryption($word){
        return password_hash($word, PASSWORD_DEFAULT);
    }

    private function verify($word, $hash){
        return password_verify($word, $hash);
    }

    private function getlastInsertID(){
        return  $this->con->lastInsertId();
    }

    private function getChild($email){
        $sql = 'SELECT id, name FROM users WHERE id IN (SELECT student_id FROM users, parenting WHERE id = parent_id AND email = ?);';

        return $this->getInfo($sql, $email);
    }


    private function getParent($email){
        $sql = 'SELECT id, name FROM users WHERE id = (SELECT parent_id FROM users, parenting WHERE id = student_id AND email = ?);';
        return $this->getInfo($sql, $email);

    }

    private function getInfo($sql, $email){
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result, ["id" => $v["id"],"name" => $v["name"]] );
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function isParentLogin($email){
        $sql = 'SELECT parent_id FROM users, parents WHERE id = parent_id AND email = ?';
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $result = ($stmt->fetchColumn() == 0)? false : true;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function isStudentLogin($email){
        return !$this->isParentLogin($email); //if not parent, then must be student
    }



    function __destruct()
    {
//        echo "   destruct connection is closed";
        $this->con = null; //close database connection
    }

}