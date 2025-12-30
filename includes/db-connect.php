<?php
$host="localhost";
$dbname="projet";
$user="root";
$pass="";
$connect=new mysqli($host,$user,$pass,$dbname);
if($connect->connect_error){
    die("connect failed");
}
?>