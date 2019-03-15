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
                $studentInfo = [
                                "id" => $this->getlastInsertID(),
                                "grade" => 0, "status" => $info["status"],
                                "parentEmail" => $info["parentEmail"]
                ];
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
        $result = $this->updateInfo($sql, $info);
            if ($info["mentor"] != null) {
                $result = $this->updateMentor($info["mentor"], $info["id"]);
            }

            if ($info["mentee"] != null) {
                $result = $this->updateMentee($info["mentee"], $info["id"]);
            }
        return $result;
    }

    function updateParent($info){
        $sql = 'UPDATE USERS SET name = ? , email = ?, phone = ?, city = ?, state = ? WHERE id = ?';
        $result = false;
        if ($this->updateInfo($sql, $info)){
            $result = $this->updateModerator($info["moderator"], $info["id"]);
        }
        return $result;
    }






    private function post($moderatorId, $materialId){
        $result = false;
       $sql = "INSERT INTO post (moderator_id, material_id) VALUES (?, ?)";
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $moderatorId);
            $stmt->bindParam(2, $materialId);
            $result = $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function postMaterial($info){
        $result = false;
        $sql = 'INSERT INTO material (title, author, type, url, assigned_date, notes) VALUES (?, ?, ?, ?, ?, ?)';
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["title"]);
            $stmt->bindParam(2, $info["author"]);
            $stmt->bindParam(3, $info["type"]);
            $stmt->bindParam(4, $info["url"]);
            $stmt->bindParam(5, $info["assignDate"]);
            $stmt->bindParam(6, $info["note"]);
            if ($result = $stmt->execute()){
                $result = $this->post($info["moderatorId"], $this->getlastInsertID());
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }


    function verfifyParent($email){
        $sql = 'SELECT * FROM parents WHERE parent_id = (SELECT id FROM users WHERE email = ?);';
        return $this->exist($sql, $email);
    }








    function getAllpostedMaterial($moderatorId){
        $sql = 'SELECT * FROM material WHERE material_id IN (SELECT material_id FROM post WHERE moderator_id = ?)';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $moderatorId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result,
                                [
                                    "materialId" => $v["material_id"],
                                    "title" => $v["title"],
                                    "author" => $v["author"],
                                    "type" => $v["type"],
                                    "url" => $v["url"],
                                    "assignDate" => $v["assigned_date"],
                                    "notes" => $v["notes"],
                                ]
                );

            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }


    function getAllSection(){
        $sql = 'SELECT title, description, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id ORDER BY sections.sec_name ASC';
        return $this->getSection($sql);
    }

    function setSection($moid, $secId){
        //insert into table (fielda, fieldb, ... ) values (?,?...), (?,?...)....
        $insertValue = [];
        $questionMark = "";
        foreach($secId as $v) {
            $questionMark .= ",(?, ?)";
            $insertValue = array_merge($insertValue, [$v, $moid]);
        }
        $sql = 'INSERT INTO moderate (sec_id, moderator_id) VALUES' . substr($questionMark, 1);
        echo $sql;
        echo "<pre>";
        print_r($insertValue);
        echo "</pre>";
        $result = [];
        try{
            $this->con->beginTransaction(); // also helps speed up your inserts.
            $stmt =  $this->con->prepare($sql);
            $stmt->execute($insertValue); //insert multiple row with one statement
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        $this->con->commit();

        return $result;

    }

    function getUserSection($moid){
        $sql = 'SELECT title, description, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, moderate WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id = moderate.sec_id and moderate.moderator_id = ? ORDER BY sections.sec_name ASC';
        return $this->getSection($sql, $moid);
    }

    function getAddableSection($moid){
        $sql = 'SELECT DISTINCT title, description, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, moderate WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id  and sections.sec_id NOT IN (SELECT moderate.sec_id FROM moderate WHERE moderate.moderator_id = ?) ORDER BY sections.sec_name ASC';
        return $this->getSection($sql, $moid);
    }

    private function getSection($sql, $moid = null){
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            if ($moid != null){
                $stmt->bindParam(1, $moid);
            }
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result,
                    [
                        "secId" => $v["sec_id"],
                        "cId" => $v["c_id"],
                        "startDate" => $v["start_date"],
                        "endDate" => $v["end_date"],
                        "capacity" => $v["capacity"],
                        "secName" => $v["sec_name"],
                        "dayOfTheWeek" => $v["day_of_the_week"],
                        "startTime" => $v["start_time"],
                        "endTime" => $v["end_time"],
                        "title" => $v["title"],
                        "description" => $v["description"]
                    ]
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }





    private function course(){
       // $sql = "INSERT INTO `courses` (`c_id`, `title`, `description`, `mentor_grade_req`, `mentee_grade_req`) VALUES ('1', 'database 2', 'mysql and project', '10', '5');"
        //INSERT INTO `time_slot` (`time_slot_id`, `day_of_the_week`, `start_time`, `end_time`) VALUES ('1', 'm, w, f', '03:00:00', '04:00:00');
    }


















//private function

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




    private function updateInfo($sql, $info){
        $result = false;
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $info["name"]);
            $stmt->bindParam(2, $info["email"]);
            $stmt->bindParam(3, $info["phone"]);
            $stmt->bindParam(4, $info["city"]);
            $stmt->bindParam(5, $info["state"]);
            $stmt->bindParam(6, $info["id"]);
            $result = $stmt->execute();
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
            if ($result = $stmt->execute()){
                if ($info["status"] == 0 || $info["status"] == 2){ //if select mentee or both
                    $this->mentee($info["id"]);
                }
                if ($info["status"] == 1 || $info["status"] == 2) { //if select mentor or both
                    $this->mentor($info["id"]);
                }
                echo "<pre>";
                print_r($this->getParentId($info["parentEmail"])["id"]);
                echo "</pre>";
                $this->parenting($this->getParentId($info["parentEmail"])["id"], $info["id"]);
            };


            //echo  '<script>console.log("student is good")</script>';
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function parenting($pid, $sid){
        $result = false;
        $sql = 'INSERT INTO parenting (parent_id, student_id) VALUES (?, ?)';
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $pid);
            $stmt->bindParam(2, $sid);
            $result = $stmt->execute();

        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;

    }

    private function updateModerator($value, $id){
        $result = false;
        if ($value == null){
            $sql = "DELETE FROM moderators WHERE moderator_id = ?";
        }else{
            $sql = "INSERT INTO moderators (moderator_id) VALUES (?)";
        }
        echo $sql;
        echo "value is " . $value;
        echo "id is " . $id;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $result = $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function updateMentor($value, $id){
        $sql = 'UPDATE mentees SET mentee_id = ' . $value . ' WHERE id = ?';
        return $this->menteeMentor($sql, $id);
    }

    private function updateMentee($value, $id){
        $sql = 'UPDATE mentors SET mentor_id = ' . $value . ' WHERE id = ?';
        return $this->menteeMentor($sql, $id);
    }

    private function mentee($id){
        $sql = 'INSERT INTO mentees (mentee_id) VALUES (?)';
        return $this->menteeMentor($sql, $id);
    }

    private function mentor($id){
        $sql = 'INSERT INTO mentors (mentor_id) VALUES (?)';
        return $this->menteeMentor($sql, $id);
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

    private function getParentId($parentEmail){
        $sql = 'SELECT parent_id FROM parents, users WHERE id = parent_id AND email = ?';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $parentEmail);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                $result = ["id" => $v["parent_id"]];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
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