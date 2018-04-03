<?php

session_start();
$connection = 0;
$timereq = 0;
require_once('../files/includes/conn.php'); 
require_once('../files/includes/time.php'); 

function location(){
    header("Location: ../index.php");
}

if($connection){  
    if(isset($_SESSION['token']) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $email = trim($_POST["email"]);
            $pass = trim($_POST["pass"]);
            if(!empty($email) && !empty($pass)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    if($timereq){
                        $time_zone = getTimeZoneFromIpAddress();
                        date_default_timezone_set($time_zone);
                        $script_tz = date_default_timezone_get();
                        $date = date('d-m-Y H:i:s');
                    }
                    else{
                        $time_zone = "unknown";
                        $date = "unknown";
                    }
                    $stmt3 = $conn->prepare("INSERT INTO users (email_address, password, profile_picture, active, level, created_on, time_zone) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt3->bind_param("sssssss", $iemail, $ipass, $dp, $active, $level, $createdon, $zone);
                    $iemail = $email;
                    $ipass = md5($pass);
                    $dp=0;
                    $active = 1;
                    $level = "user";
                    $createdon = $date;
                    $zone = $time_zone;
                    if($stmt3->execute()){
                        $stmt3->close();
                        if ($stmt4 = $conn->prepare("SELECT user_id FROM users WHERE email_address=?")) {
                            $stmt4->bind_param("s", $iemail);
                            $stmt4->execute();
                            $stmt4->bind_result($id);
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
                                $_SESSION["created"]="no";
                                echo "Error while creating account";
                            }
                            $stmt4->close();
                        }
                        else{
                            $_SESSION["created"]="no";
                            echo "Error while executing create account check statement";
                        }
                    }
                    else{
                        echo $stmt3->error;
                        $stmt3->close();
                        echo "Error";
                    }
                }
                else{
                    echo "Email validation fail";
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
