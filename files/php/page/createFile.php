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
    if(isset($_POST["name"]) && isset($_POST["usession"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $name=substr(preg_replace('/\s+/', '-', $_POST["name"]), 0, 30);
            $user_id=$_SESSION["id"];
            if($name=="home"){
                echo "home not allowed";
            }
            else{
                if ($stmt5 = $conn->prepare("SELECT id FROM dir WHERE user_id=? AND name=?")) {
                    $stmt5->bind_param("ss", $user_id,$name);
                    $stmt5->execute();
                    $stmt5->bind_result($cid);
                    $stmt5->fetch();
                    if($cid!=NULL || $cid!=""){
                        echo "exist";
                        $stmt5->close();
                    }
                    else{
                        $stmt5->close();
                        if($timereq){
                            $time_zone = getTimeZoneFromIpAddress();
                            date_default_timezone_set($time_zone);
                            $script_tz = date_default_timezone_get();
                            $time = date('H:i');
                            $monthNum = date('m');
                            $month= date('F', mktime(0, 0, 0, $monthNum, 10));
                            $date = date('d')." ".$month;
                            $year = date('Y');
                        }
                        else{
                            $time_zone = "unknown";
                            $date = "unknown";
                        }
                        do{
                            $token = generateRand_md5uid();
                            if ($stmt4 = $conn->prepare("SELECT id FROM dir WHERE dir_id=?")) {
                                    $stmt4->bind_param("s", $token);
                                    $stmt4->execute();
                                    $stmt4->bind_result($rid);
                                    $stmt4->fetch();
                                    if($rid!=NULL || $rid!=""){
                                    $unique=0;
                                 }
                                else{
                                    $unique=1;
                                 }  

                            }$stmt4->close();
                        }while($unique==0);
                        $stmt3 = $conn->prepare("INSERT INTO dir (dir_id, user_id, name, public) VALUES (?, ?, ?, ?)");
                        $stmt3->bind_param("ssss", $dir_id, $user_id, $name, $public);
                        $user_id=$_SESSION["id"];
                        $public=0;
                        $dir_id=$token;
                        if($stmt3->execute()){
                            $stmt3->close();
                            if ($stmt6 = $conn->prepare("SELECT id FROM dir WHERE dir_id=?")) {
                                $stmt6->bind_param("s", $dir_id);
                                $stmt6->execute();
                                $stmt6->bind_result($pid);
                                $stmt6->fetch();
                                if($pid!=NULL && $pid!=""){
                                    echo $pid;
                                }
                                else{
                                    echo "Error while creating File";
                                }
                                $stmt6->close();
                            }
                        }
                        else{
                            $stmt3->close();
                            echo "Error";
                        }
                    }          
                }
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