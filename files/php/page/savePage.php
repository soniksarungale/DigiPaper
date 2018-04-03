<?php
session_start();
$connection = 0;
$timereq = 0;
require_once('../../includes/conn.php');
require_once('../../includes/time.php');

function location(){
    header("Location: ../index.php");
}

function filter($str){
    $wtxt = preg_replace('/[\s\+]/', ' ', $str);
    $txt = strip_tags($wtxt,'<br>');
    $regex = "#<(/?\w+)\s+[^>]*>#is";
    $txt = preg_replace($regex, '<${1}>',$txt);
    
    return $txt;
}
function generateRand_md5uid(){
	$better_token = md5(uniqid(rand(), true));
	return $better_token;
}
if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    if(isset($_POST["text"]) && isset($_POST["public"]) && isset($_POST["dir"]) && isset($_POST["usession"]) && isset($_POST["type"]) && isset($_POST["paper_id"])){
        if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
            $type=$_POST["type"];
            $dir=$_POST["dir"];
            $text= filter($_POST["text"]);
            $pub=$_POST["public"];
            $text_check=preg_replace('/[\s\+]/', ' ', $_POST["text"]);
            if((strlen($text_check)<=11000) && ($type=="blank" || $type=="lines") && ($pub=="public" || $pub=="private")){
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
                if($pub=="private"){
                    $public=0;
                }
                else{
                    $public=1;
                }
                $stmt3 = $conn->prepare("UPDATE paper SET text=?, modified=?, dir=?, public=?, type=? WHERE user_id=? AND id=?");
                    $stmt3->bind_param("sssssss", $text, $modified, $dir, $public, $type, $user_id, $pid);
                    $user_id=$_SESSION["id"];
                    $modified=$date.", ".$year." ".$time;
                    $pid=$_POST["paper_id"];
                    if($stmt3->execute()){
                        $stmt3->close();
                        echo "Updated";
                    }
                    else{
                        echo $stmt3->error;
                        $stmt3->close();
                        echo "Error";
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