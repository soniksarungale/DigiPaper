<?php
session_start();
$connection = 0;
$timereq = 0;
require_once('../../includes/conn.php'); 
require_once('../../includes/time.php'); 

function location(){
    header("Location: ../../../index.php");
}

function generateRand_md5uid(){
	$better_token = md5(uniqid(rand(), true));
	return $better_token;
}
if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    if(isset($_POST["email"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $email=substr(preg_replace('/\s+/', '', $_POST["email"]), 0, 40);
            $user_id=$_SESSION["id"];
            $stmt1 = $conn->prepare("SELECT email_address FROM users where email_address=?");
            $stmt1->bind_param('s', $email);
            $stmt1->execute();
            $result = $stmt1->get_result();
            if($result->num_rows > 0) {
                echo "invalid";
            }
            else{
            $stmt1->close();
                $stmt = $conn->prepare("UPDATE users SET email_address=? WHERE user_id=?");
                if ($stmt === false) {
                  trigger_error($conn->error, E_USER_ERROR);
                  return;
                }
                $stmt->bind_param('si', $email,$user_id);
                $status = $stmt->execute();
                if ($status === false) {
                  trigger_error($stmt->error, E_USER_ERROR);
                }
                else{
                    $_SESSION["email"]=$email;
                    echo "done";
                }
                
                $stmt->close();
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