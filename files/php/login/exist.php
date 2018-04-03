<?php
$connection = 0;

require_once('../../includes/conn.php'); 

function location(){
    header("Location: ../../../index.php");
}

if($connection){
    
    if(isset($_POST["email"])){
        $email = $_POST["email"];
        if($email!=NULL && $email!=""){
            $stmt1 = $conn->prepare("SELECT email_address FROM users where email_address=?");
            $stmt1->bind_param('s', $email);
            $stmt1->execute();
            $result = $stmt1->get_result();
            if($result->num_rows > 0) {
                echo "invalid";
            }
            else{
                echo "valid";
            }

            $stmt1->close();
        }
        else{
            location();
        }
        
    }
    elseif(isset($_POST["username"])){
        $uname = $_POST["username"];
        if($uname!=NULL && $uname!=""){
            $stmt2 = $conn->prepare("SELECT user_name FROM users where user_name=?");
            $stmt2->bind_param('s', $uname);
            $stmt2->execute();
            $result = $stmt2->get_result();
            if($result->num_rows > 0) {
                echo "invalid";
            }
            else{
                echo "valid";
            }

            $stmt2->close();
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
