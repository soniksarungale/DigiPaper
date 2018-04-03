<?php
session_start();
$connection = 0;
require_once('../../includes/conn.php');

function location(){
    header("Location: ../../../index.php");
}
if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    if(isset($_POST["name"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $name=$_POST["name"];
            $user_id=$_SESSION["id"];
            $stmt = $conn->prepare("DELETE FROM dir WHERE name=? AND user_id=?");
            if ($stmt === false) {
              trigger_error($conn->error, E_USER_ERROR);
              return;
            }
            $stmt->bind_param('si',  $name,$user_id);
            $status = $stmt->execute();
            if ($status === false) {
              trigger_error($stmt->error, E_USER_ERROR);
                $stmt->close();
            }
            else{
                $stmt->close();
                $stmt2 = $conn->prepare("DELETE FROM paper WHERE dir=? AND user_id=?");
                if ($stmt2 === false) {
                  trigger_error($conn->error, E_USER_ERROR);
                  return;
                }
                $stmt2->bind_param('si',  $name,$user_id);
                $status = $stmt2->execute();
                if ($status === false) {
                  trigger_error($stmt2->error, E_USER_ERROR);
                }
                else{
                    echo "done";
                }
                $stmt2->close();
            }
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