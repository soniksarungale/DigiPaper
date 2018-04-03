<?php 

if(isset($connection)){
    
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "E-Paper";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        $connection = 1;
    }

}
else{
    header("Location: ../../index.php");
}

?>