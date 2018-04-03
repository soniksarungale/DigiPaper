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
    if(isset($_POST["cpass"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $cpass=md5($_POST["cpass"]);
            $user_id=$_SESSION["id"];
            $stmt1 = $conn->prepare("SELECT email_address FROM users where password=? AND user_id=?");
            $stmt1->bind_param('si', $cpass,$user_id);
            $stmt1->execute();
            $result = $stmt1->get_result();
            if($result->num_rows > 0) {
                $stmt1->close();
                if(isset($_POST["newpass"])){
                    $newpass=md5($_POST["newpass"]);
                    $stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id=? AND password=?");
                    if ($stmt === false) {
                      trigger_error($conn->error, E_USER_ERROR);
                      return;
                    }
                    $stmt->bind_param('sis', $newpass,$user_id,$cpass);
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
                    echo "correct";
                }
            }
            else{
                $stmt1->close();
                echo "incorrect";
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