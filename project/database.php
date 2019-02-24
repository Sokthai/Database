<?php

/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2/23/2019
 * Time: 2:36 PM
 */
class database
{
   private $dbName = "";
   private $password = "";
   private $username = "user";
   private $serverName = "localhost";

   function __construct($userName, $password = "", $databaseName = "db2")
   {
       $this->dbName = $databaseName;
       $this->username = $userName;
       $this->password = $password;
   }

   function connection(){
       //$con = mysqli_connect($this->serverName, $this->username, $this->pw, $this->dbName);

       try {
           $con = new PDO("mysql:host=$this->serverName;dbname=myDB", $this->username, $this->password);
           // set the PDO error mode to exception
           $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           echo "Connected successfully";
       }
       catch(PDOException $e)
       {
           echo "Connection failed: " . $e->getMessage();
       }
   }


    function registration(){


    }


}