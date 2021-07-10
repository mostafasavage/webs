<?php
$dsn = "mysql:host=localhost;dbname=phps";
$username = "root";
$password ="";
try{
    $con = new PDO($dsn , $username , $password);
}catch(PDOException $error){
  echo $error->getMessage();
}

?>