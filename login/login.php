<?php

session_start();
$connection = 0;
require_once('../files/includes/conn.php'); 

function location(){
    header("Location: ../index.php");
}

if($connection){  
    if(isset($_SESSION['token']) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $email = trim($_POST["email"]);
            $cpass = trim($_POST["pass"]);
            $pass=md5($cpass);
            if(!empty($email) && !empty($cpass)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                        if ($stmt4 = $conn->prepare("SELECT user_id, profile_picture, active, level FROM users WHERE email_address=? AND password=?")) {
                            $stmt4->bind_param("ss", $email,$pass);
                            $stmt4->execute();
                            $stmt4->bind_result($id,$dp,$active,$level);
                            $stmt4->fetch();
                            if($id!=NULL || $id!=""){
                                $_SESSION["created"]="ok";
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;
                                $_SESSION["active"] = $active;
                                $_SESSION["level"] = $level;
                                $_SESSION["dp"]=$dp;
                                echo "done";
                            }
                            else{
                                $_SESSION["login"]="no";
                                echo "incorect";
                            }
                            $stmt4->close();
                        }
                    else{
                        echo "Error while checking";
                    }
                }
                else{
                    echo "Email filter error";
                }
            }
            else{
                header("Location: ../index.php");
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
