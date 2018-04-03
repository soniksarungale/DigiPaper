<?php
session_start();
$connection = 0;
require_once('../../includes/conn.php');

function location(){
    header("Location: ../../../index.php");
}
if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    if(isset($_POST["pid"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $pid=$_POST["pid"];
            $stmt = $conn->prepare("DELETE FROM paper WHERE id=? AND user_id=?");
            if ($stmt === false) {
              trigger_error($conn->error, E_USER_ERROR);
              return;
            }
            $stmt->bind_param('ii',  $paper_id,$user_id);
            $paper_id=$pid;
            $user_id=$_SESSION["id"];
            $status = $stmt->execute();
            if ($status === false) {
              trigger_error($stmt->error, E_USER_ERROR);
            }
            else{
                echo "done";
            }
            $stmt->close();
        }
        else{
            location();
        }
    }   
    else{
        location();
    }
}
else{
    location();
}

?>