<?php

/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2/23/2019
 * Time: 2:36 PM
 */
class database
{

   private $dbName = "db2";
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
                                "grade" => rand(3, 12), "status" => $info["status"],
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
//        echo "updateStudent";
//        echo "<pre>";
//
//        print_r($info);
//        echo "</pre>";
        $sql = 'UPDATE USERS SET name = ? , email = ?, phone = ?, city = ?, state = ? WHERE id = ?';
        $result = $this->updateInfo($sql, $info);

            $this->resetMenteeMentor($info["id"]); //remove both mentor and mentee role from a student and add it back later

            if ($info["mentor"] != null) {
                $result = $this->mentor($info["id"]);
            }

            if ($info["mentee"] != null) {
                $result = $this->mentee($info["id"]);
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








    function getUserPostedMaterial($moderatorId){
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
        $sql = 'SELECT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id ORDER BY sections.sec_name ASC';
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
//        echo $sql;
//        echo "<pre>";
//        print_r($insertValue);
//        echo "</pre>";
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

    function sectionEnrollment($table, $user, $value){
        //insert into table (fielda, fieldb, ... ) values (?,?...), (?,?...)....
        $insertValue = [];
        $questionMark = "";
        foreach($value as $v) {
            $questionMark .= ",(?, ?)";
            $insertValue = array_merge($insertValue, [$v, $user]);
        }
        if ($table == "teach"){
            $sql = 'INSERT INTO teach (sec_id, mentor_id) VALUES' . substr($questionMark, 1);
        }elseif ($table == "enroll"){
            $sql = 'INSERT INTO enroll (sec_id, mentee_id) VALUES' . substr($questionMark, 1);
        }
//        echo $sql;
//        echo "<pre>";
//        print_r($insertValue);
//        echo "</pre>";
        try{
            $this->con->beginTransaction(); // also helps speed up your inserts.
            $stmt =  $this->con->prepare($sql);
            $stmt->execute($insertValue); //insert multiple row with one statement
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        $result = $this->con->commit();

        return $result;
    }

    function deleteMentorSchedule($id, $secId){
        $sql = 'DELETE FROM teach WHERE mentor_id = ?  AND sec_id = ?';
        return $this->deleteSchedule($sql, $id, $secId);
    }

    function deleteMenteeSchedule($id, $secId){
        $sql = 'DELETE FROM enroll WHERE mentee_id = ? AND sec_id = ?';
        return $this->deleteSchedule($sql, $id, $secId);
    }



    function getUserSection($moid){
        $sql = 'SELECT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, moderate WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id = moderate.sec_id and moderate.moderator_id = ? ORDER BY sections.sec_name ASC';
        return $this->getSection($sql, $moid);
    }

    function getMenteeSection($mentee){
        $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, enroll WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id in (select sec_id from enroll where enroll.mentee_id = ?) ORDER by sections.sec_name ASC';
        return $this->getSection($sql, $mentee);
    }

    function getAddableMenteeSection($mentee){ //NO PAST COURSE . NO TAKEN CLASS, NO TAKING CLASS/DUPLICATE CLASS
          $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id not in (select sec_id from enroll where enroll.mentee_id = '. $mentee .') and sections.c_id not in (select sections.c_id from sections, records where sections.sec_id = records.sec_id and records.student_id = ?) AND sections.start_date > (select curdate())';
//        $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, enroll WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id not in (select sec_id from enroll where enroll.mentee_id = ?) ORDER by sections.sec_name ASC';
        return $this->getSection($sql, $mentee);
    }

    function getMentorSection($mentor){
        $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, teach WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id in (select sec_id from teach where teach.mentor_id = ?) ORDER by sections.sec_name ASC';
        return $this->getSection($sql, $mentor);
    }

    function getAddableMentorSection($mentor){
        $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id not in (select sec_id from teach where teach.mentor_id = '. $mentor .') and sections.c_id in (select sections.c_id from sections, records where sections.sec_id = records.sec_id and records.student_id = ?)';
        //$sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses, teach WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id and sections.sec_id not in (select sec_id from teach where teach.mentor_id = ?) ORDER by sections.sec_name ASC';
        return $this->getSection($sql, $mentor);
    }


    function getMentor($secId){ //get mentor who mentoring a certain section
        $sql = 'select name, phone, email from users, teach where id = mentor_id and sec_id = ?';
        return ($this->getModeratorMentor($sql, $secId));
    }










    function getCountMentor($sesId){
        $sql = 'select count(mentor_id) as mentor from teach where sec_id = (select sec_id from sessions where ses_id = ?)';
        $result = "";
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $sesId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
//                array_push($result, [
//                    "mentorCount" => $v["mentor"],
//                    "secId" => $v["sec_id"]
//                ]);
                $result = $v["mentor"];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getCountParticipate($sesId){
        $sql = 'select count(participate) as participate from participate where ses_id = ? and participate = true';
        $result = "";
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $sesId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
//                array_push($result, [
//                    "participate" => $v["participate"],
//                    "sesId" => $v["ses_id"]
//                ]);
                $result = $v["participate"];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getParticipantAndMentor(){
        $sql = 'select sections.sec_id, title, ses_name, date, announcement, ses_id from sessions, courses, sections where sections.c_id = courses.c_id and sessions.sec_id = sections.sec_id';
        $result = [];
//        $mergeArray = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                $mergeArray = [
                    "title" => $v["title"],
                    "sesName" => $v["ses_name"],
                    "secId" => $v["sec_id"],
                    "date" => $v["date"],
                    "announcement" => $v["announcement"],
                    "sesId" => $v["ses_id"],
                    "mentorCount" => $this->getCountMentor($v["ses_id"]),
                    "participantCount" => $this->getCountParticipate($v["ses_id"])
                ];

//                array_push($mergeArray,"ok" );//["mentorCount" => $this->getCountMentor($v["ses_id"]), "participantCount" => $this->getCountParticipate($v["ses_id"])]);
//                array_push($mergeArray, $this->getCountParticipate($v["ses_id"]));

                array_push($result, $mergeArray);

            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }



    function getNotification($sesId){
          //$sql = 'select name, email from users, participate where users.id = participate.student_id and participate.ses_id = ?';
        $sql = 'select name, email, sessions.ses_id, ses_name from users, participate, sessions where users.id = participate.student_id and sessions.ses_id = participate.ses_id and  participate.ses_id = ?';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $sesId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {

                $mergeArray = [
                    "name" => $v["name"],
                    "email" => $v["email"],
                    "sesId" => $v["ses_id"],
                    "sesName" => $v["ses_name"]
                ];

                array_push($result, $mergeArray);




            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }










    function getModerator($secId){ //get all moderators for a section
        $sql = 'select name, phone, email from users , moderate where users.id = moderate.moderator_id and moderate.sec_id = ' . $secId;
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
//            $stmt->bindParam(1, $secId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result, [
                    "name" => $v["name"],
                    "phone" => $v["phone"],
                    "email" => $v["email"],
                ]);
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }

        return $result;
    }

    private function getModeratorMentor($sql, $secId){
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $secId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result, [
                    "name" => $v["name"],
                    "phone" => $v["phone"],
                    "email" => $v["email"],
                ]);
                echo $v["name"];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }


    function getAddableSection($moid){
        $sql = 'SELECT DISTINCT title, description, mentor_grade_req, mentee_grade_req, day_of_the_week, start_time, end_time, sec_name, start_date, end_date, capacity, sections.sec_id, courses.c_id FROM sections, time_slot, courses WHERE sections.time_slot_id = time_slot.time_slot_id and sections.c_id = courses.c_id  and sections.sec_id NOT IN (SELECT moderate.sec_id FROM moderate WHERE moderate.moderator_id = ?) ORDER BY sections.sec_name ASC';
        return $this->getSection($sql, $moid);
    }

    function assignMaterial($info){
        $sql = 'INSERT INTO `assign` (`sec_id`, `ses_id`, `moderator_id`, `material_id`) VALUES (?, ?, ?, ?)';
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $info["secId"]);
            $stmt->bindParam(2, $info["sesId"]);
            $stmt->bindParam(3, $info["moderatorId"]);
            $stmt->bindParam(4, $info["materialId"]);
            $result = $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getAllAssignMaterial($id){
        $sql = 'select DISTINCT assign.ses_id, title, author, type, url, assigned_date, notes from material, assign, sessions where assign.material_id = material.material_id and assign.ses_id in (select ses_id from participate where  student_id = ?)';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result, [
                        "sesId" => $v["ses_id"],
                        "title" => $v["title"],
                        "author" => $v["author"],
                        "type" => $v["type"],
                        "url" => $v["url"],
                        "assignedDate" => $v["assigned_date"],
                        "notes" => $v["notes"]
                ]);
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }


    function getAllSession(){
        $sql = 'SELECT * FROM sessions';
        return $this->getSession($sql);
    }

    function getUserSession($moid){
        $sql = 'SELECT * FROM sessions WHERE moderator_id = ?';
        return $this->getSession($sql, $moid);
    }

    /**
     * @param $info
     * An array of materialId and moderatorId
     * return the sessions that are not been assign to the same assigned material
     */

    function getAssignableSession($info){
        $sql = 'SELECT * FROM sessions WHERE ses_id NOT IN (SELECT ses_id FROM assign WHERE moderator_id = ? AND material_id = ?)';
        return $this->getSession($sql, $info["moid"], $info["maid"]);
    }


    function getAssignedSession($moid){

        $sql = 'SELECT DISTINCT material_id FROM assign WHERE moderator_id = ? ORDER BY material_id ASC';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $moid);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
               array_push($result, $this->getAssignSessionOnly($v["material_id"]));
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }




    private function getAssignSessionOnly($maid){
       // $sql = "INSERT INTO `courses` (`c_id`, `title`, `description`, `mentor_grade_req`, `mentee_grade_req`) VALUES ('1', 'database 2', 'mysql and project', '10', '5');"
        //INSERT INTO `time_slot` (`time_slot_id`, `day_of_the_week`, `start_time`, `end_time`) VALUES ('1', 'm, w, f', '03:00:00', '04:00:00');

        $sql = 'select material_id, title, author, type, url, assigned_date, notes, ses_name, date, announcement from sessions, material where material_id =  ? and ses_id in (select ses_id from assign where material_id = ?);';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $maid);
            $stmt->bindParam(2, $maid);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result,
                    [
                        "maid" => $v["material_id"],
                        "secId" => $v["title"],
                        "sesId" => $v["author"],
                        "type" => $v["type"],
                        "url" => $v["url"],
                        "assignedDate" => $v["assigned_date"],
                        "notes" => $v["notes"],
                        "sesName" => $v["ses_name"],
                        "date" => $v["date"],
                        "announcement" => $v["announcement"]
                    ]
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }











//private function

    private function getSession($sql, $moid = null, $maid = null){
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);

            if ($moid != null && $maid != null){
                $stmt->bindParam(1, $moid);
                $stmt->bindParam(2, $maid);
            }elseif ($moid != null){
                $stmt->bindParam(1, $moid);
            }
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result,
                    [
                        "secId" => $v["sec_id"],
                        "sesId" => $v["ses_id"],
                        "sesName" => $v["ses_name"],
                        "date" => $v["date"],
                        "announcement" => $v["announcement"]
                    ]
                );
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
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
                        "description" => $v["description"],
                        "mentorGradeReq" => $v["mentor_grade_req"],
                        "menteeGradeReq" => $v["mentee_grade_req"]
                    ]
                );
            }
        }catch(PDOException $e){
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
//            echo $sql . "  good";

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
//                echo "<pre>";
//                print_r($this->getParentId($info["parentEmail"])["id"]);
//                echo "</pre>";
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
//        echo $sql;
//        echo "value is " . $value;
//        echo "id is " . $id;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $result = $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

//    private function updateMentee($value, $id){
//        $sql = 'DELETE FROM mentees WHERE mentee_id = ?';
////        $sql = 'UPDATE mentees SET mentee_id = ' . $value . ' WHERE id = ?';
//        return $this->menteeMentor($sql, $id);
//    }
//
//    private function updateMentor($value, $id){
////        $sql = 'DELETE FROM mentors WHERE mentor_id = ?';
////        $sql = 'UPDATE mentors SET mentor_id = ' . $value . ' WHERE id = ?';
//        return $this->menteeMentor($sql, $id);
//    }

    private function mentee($id){
        $sql = 'INSERT INTO mentees (mentee_id) VALUES (?)';
        return $this->menteeMentor($sql, $id);
    }

    private function mentor($id){
        $sql = 'INSERT INTO mentors (mentor_id) VALUES (?)';
        return $this->menteeMentor($sql, $id);
    }


    private function resetMenteeMentor($id){
        $menteeSql = 'DELETE FROM mentees WHERE mentee_id = ?';
        $mentorSql = 'DELETE FROM mentors WHERE mentor_id = ?';
        return ($this->updateMenteeMentor($menteeSql, $id) && $this->updateMenteeMentor($mentorSql, $id));
    }

    private function updateMenteeMentor($sql, $id){
        $result = false;
//        echo $sql;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $result = true;
        }catch (PDOException $e){
            echo $e->getMessage();
        }
        return $result;
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

    function isMentee($id){
        $sql = "SELECT * FROM mentees WHERE mentee_id = ?";
        return $this->exist($sql, $id);
    }

    function isMentor($id){
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

    function isStudent($id){
        $sql = 'SELECT email FROM users WHERE id = ?';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                $result = ["email" => $v["email"]];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $this->isStudentLogin($result["email"]);

    }

    private function isStudentLogin($email){
        return !$this->isParentLogin($email); //if not parent, then must be student
    }


    function isTakenClass($id,  $c_id){
        $sql = 'select sec_name, c_id from sections, records where sections.sec_id = records.sec_id and sections.c_id = ' . $c_id . ' and records.student_id = ?';
//        $sql = 'select sections.c_id from sections, records where records.sec_id = sections.sec_id and records.student_id = ?';
//        $sql = 'select sections.c_id from sections, records where sections.c_id = 3 and records.student_id = 76 and records.sec_id = sections.sec_id';
        return $this->exist($sql, $id);
    }

//    function getTakenClass($id, $c_id){
//        $sql = 'SELECT student_id, grade, c_id FROM records WHERE student_id = ? AND c_id = ?';
//        return $this->getRecordClass($sql, $id);
//    }

    function getAllTakenClass($id){
        $sql = 'select grade, title, description, start_date from records, courses, sections where sections.sec_id = records.sec_id and sections.c_id = courses.c_id and records.student_id = ?;';
        return $this->getRecordClass($sql, $id);
    }



//    function getAllNotTakenClass($id){
//        $sql = 'SELECT student_id, grade, c_id FROM records WHERE student_id = ?';
//        return $this->getRecordClass($sql, $id);
//    }





    private function getRecordClass($sql, $id){
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
//            $stmt->bindParam(2, $c_id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                array_push($result, [
                            "grade" => $v["grade"],
                            "title" => $v["title"],
                            "description" => $v["description"],
                            "startDate" => $v["start_date"]
                ]);
//                $result = ["grade" => $v["grade"], "studentId" => $v["student_id"], "c_id" => $v["c_id"]];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getParticipation($id){
        $sql = 'select DISTINCT ses_id, sessions.sec_id, ses_name, date, announcement, title from sessions, courses, sections where sections.c_id = courses.c_id and sessions.sec_id = sections.sec_id and sessions.sec_id in (select sections.sec_id from enroll, sections where enroll.sec_id = sections.sec_id and enroll.mentee_id = ?)';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                $mergeArray = [
                    "sesId" => $v["ses_id"],
                    "secId" => $v["sec_id"],
                    "sesName" => $v["ses_name"],
                    "date" => $v["date"],
                    "announcement" => $v["announcement"],
                    "title" => $v["title"]
                ];

                if ($this->getCountParticipate($v["ses_id"]) < 6){
                    array_push($result, $mergeArray);
                }

            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function getUnparticipating(){ //need to work on this one

    }

    function participate($info, $studentId){
        //insert into table (fielda, fieldb, ... ) values (?,?...), (?,?...)....
        $insertValue = [];
        $questionMark = "";
        foreach($info as $v) {
            $questionMark .= ",(?, ?, ?, ?)";
            $insertValue = array_merge($insertValue, [$studentId, unserialize(base64_decode($v))["secId"], unserialize(base64_decode($v))["sesId"], 1]);
        }
        $sql = 'INSERT INTO participate (student_id, sec_id, ses_id, participate) VALUES ' . substr($questionMark, 1);
//        echo $sql;
//        echo "<pre> my insert value";
//        print_r($insertValue);
//        echo "</pre>";
        $result = false;
        try{
            $this->con->beginTransaction(); // also helps speed up your inserts.
            $stmt =  $this->con->prepare($sql);
            $result = $stmt->execute($insertValue); //insert multiple row with one statement
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        $this->con->commit();

        return $result;
    }



    function getStudentGrade($id){
        $sql = 'SELECT grade, student_id FROM students WHERE student_id = ?';
        $result = [];
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach($stmt->fetchAll() as $k => $v) {
                $result = ["grade" => $v["grade"], "studentId" => $v["student_id"]];
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    private function deleteSchedule($sql, $id, $secId){
        $result = false;
        try{
            $stmt =  $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $secId);
            $result = $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $result;
    }

    function __destruct()
    {
//        echo "   destruct connection is closed";
        $this->con = null; //close database connection
    }

}