<?php 

    /*$db_username = "tltmsdbuser";              //databse Hostname
    $db_password = "Tr@ck1ng";                  //database Password
    $db_hostname = "localhost";         //database HostName
    $db_database = "tltmsdb";  //database Name*/

    $db_username = "root";
    $db_password = "";
    $db_hostname = "localhost";
    $db_database = "location_tracker";

    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

?>