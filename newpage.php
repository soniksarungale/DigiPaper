<?php
session_start();
if(isset($_SESSION["pid"])){
    header("Location: view.php?id=".$_SESSION["pid"]);
}
$timereq = 0;
$connection = 0;
require_once('files/includes/conn.php'); 
require_once('files/includes/time.php'); 
$_SESSION["website"]="E-Paper";
$hashed='';
$_SESSION['token'] = microtime(); 
if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
    $hashed = crypt($_SESSION['token'], $salt);
}

if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    $email=$_SESSION["email"];
    $id=$_SESSION["id"];
    $active=$_SESSION["active"];
    $level=$_SESSION["level"];
    $dp=$_SESSION["dp"];
    $selected = "home";
    if($level=="user" && $active==1){
        if(isset($_GET["dir"])){
            $dir_id=$_GET["dir"];
            $stmt3 = $conn->prepare("SELECT name FROM dir WHERE id=? AND user_id=?");
            $stmt3->bind_param("ss", $dir_id,$id);
            $stmt3->execute();
            $stmt3->bind_result($name);
            $stmt3->fetch();
            if($name!=NULL && $name!=""){
                $selected = $name;
                $dir_exist=1;
            }
            $stmt3->close();
        }

        
                    if($timereq){
                        $time_zone = getTimeZoneFromIpAddress();
                        date_default_timezone_set($time_zone);
                        $script_tz = date_default_timezone_get();
                        $day = date('d');
                        $monthNum = date('m');
                        $month= date('F', mktime(0, 0, 0, $monthNum, 10));
                        $year= date('Y');
                        $time = date("H:i");
                    }
                    else{
                        $time_zone = "unknown";
                        $date = "unknown";
                    }
?>
  <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper || <?php echo $email?></title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/paper.css">
   </head>
   <body>
       <header class="main-header withback">
           <div class="header-right">
                       <div class="header-flex">
                           <div class="back-option">
                                <a href="index.php"><i class="fas fa-chevron-left"></i></a>
                            </div>
                           <div class="logo">
                               <a href="index.php"><i class="far fa-file-alt"></i>  Digi<span class="logocolor">Paper</span></a>
                           </div>
                       </div>
                   </div>
           <div class="options">
              <div class="op saveop save-btn" onclick="save_text()">Save</div>
               <div class="profile op">
                   
                       <?php
                            if($dp=="0"){
                                echo '<div class="user-dp user-icon">';
                                echo '<i class="icon fas fa-user-circle"></i>';
                                echo '</div>';
                            }
                            else{
                                echo '<div class="user-dp">';
                                echo '<img src="'.$dp.'">';
                                echo '</div>';
                            }
                       ?>
                   
                   <nav class="main-navigation">
                       <ul>
                           <a href="profile.php">
                            <li><i class="fas ic fa-cog"></i> Settings</li>
                           </a>
                           <a href="feedback.php">
                            <li><i class="fas ic fa-envelope"></i> Feedback</li>
                           </a>
                           <a href="logout.php">
                               <li><i class="fas ic fa-sign-out-alt"></i> Log Out</li>
                           </a>
                       </ul>
                   </nav>
               </div>
           </div>
           
       </header>
       <div class="mob-options">
               <div class="info">
                   <div class="viewdate">
                       <?php
                       echo $day." ".$month.", ".$year;
                       ?>
                   </div>
                    <pre> </pre><div class="viewtime"><?php
                        echo $time;   
                    ?></div>
               </div>
               <div class="show-paper-opt">
                   <button class="show-opt" onclick="show_opt()"><i class="fas ic fa-cog"></i></button>
                   <button class="hide-opt" onclick="hide_opt()"><i class="fas fa-times"></i></button>
               </div>     
        </div>
       <div class="main-container flex">
           <div class="page-container">
               <div class="paper" id="line-paper">
                  <div class="lines">
                    <div class="text" id="paper-txt" contenteditable="true" spellcheck="true" data-input-length="10000"></div>
                  </div>
                  <div class="holes hole-top"></div>
                  <div class="holes hole-middle"></div>
                  <div class="holes hole-bottom"></div>
                </div>
           </div>
           <div class="page-setting">
               <div class="info">
                   <div class="viewdate">
                       <?php
                       echo $day." ".$month.", ".$year;
                       ?>
                   </div>
                    <pre> </pre><div class="viewtime"><?php
                        echo $time;   
                    ?></div>
               </div>
               <div class="type">
                   <div class="lines type-box" onclick="line_page(this)" id="selected-option">
                       <div class="box-holder">
                           <div class="icon"><i class="far ic fa-file-alt"></i></div>
                           <div class="type-text">Lines</div>
                       </div>
                   </div>
                   <div class="blank type-box" onclick="blank_page(this)">
                       <div class="box-holder">
                           <div class="icon"><i class="far ic fa-file"></i></div>
                           <div class="type-text">Blank</div>
                       </div>
                   </div>
               </div>
               <div class="spell-checker">
                   <div class="sptext">Spell Checker :</div>
                       <div onclick="spell_check('on')" class="radio">
                        <input id="radio-1" name="spr" type="radio" checked>
                        <label for="radio-1" class="radio-label">ON</label>
                      </div>
                    <div class="radio">
                        <input id="radio-2" name="spr" onclick="spell_check('off')" type="radio">
                        <label for="radio-2" class="radio-label">OFF</label>
                      </div>
               </div>
               <div class="public">
                   <select name="public-val" id="public-val">
                       <option selected value="private">Private</option>
                       <option value="public">Public</option>   
                   </select>
               </div>
               <br>
               <div class="dir">
                   <select name="dir-val" id="dir-val">
                     
                      <?php 
                   $stmt = $conn->prepare("SELECT name FROM dir WHERE user_id=? ORDER BY id DESC");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($dir_name);
        while ($stmt->fetch()) { 
            if(isset($dir_exist)){
                if($dir_name==$selected){
                    echo '<option selected value="'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'</option>';
                }
                else{
                    
                    echo '<option value="'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'</option>';
                }
            }
            else{
                echo '<option value="'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($dir_name, ENT_QUOTES, 'UTF-8').'</option>';
            }
        }
        if(isset($dir_exist)){
            echo '<option value="home">Home</option>';
        }else{
            echo '<option selected value="home">Home</option>';
        }
        $stmt->close();
                   ?>
                      
                       
                       
                   </select>
               </div>
               <div class="save">
                   <button class="save-btn saved" onclick="save_text()">Save</button>
               </div>
           </div>
       </div>
       
              
       <script src="files/js/jquery.js"></script>
       <script src="files/js/main.js"></script>
       <script>
           function show_opt(){
               $(".page-container").css("display","none");
               $(".page-setting").fadeIn();
               $(".show-opt").css("display","none");
               $(".hide-opt").fadeIn();
           }
           function hide_opt(){
               $(".page-setting").css("display","none");
               $(".page-container").fadeIn();
               $(".hide-opt").css("display","none");
               $(".show-opt").fadeIn();
           }
           
           var type="lines";
           function blank_page(elm){
                $(".type-box").removeAttr("id","selected-option");
                $(elm).attr("id","selected-option");
                $(".paper").removeAttr("id","line-paper");
                $(".paper").attr("id","blank-paper");
               type="blank";
            }
            function line_page(elm){
                $(".type-box").removeAttr("id","selected-option");
                $(elm).attr("id","selected-option");
                $(".paper").removeAttr("id","blank-paper");
                $(".paper").attr("id","line-paper");
                type="lines";
            }
           var status=0;
           
            $("#paper-txt").keyup(function(){
                var text = $("#paper-txt").html();
                var text_check=$.trim(text).replace(/[<]br[^>]*[>]/gi,"");
                if(text_check!=" " && text_check!="" && text_check!=null && text_check!="<br>"){
                   $(".save-btn").fadeIn();
                    status=1;
                }
                else{
                    $(".save-btn").fadeOut();
                    status=0;
                }
            });
           function save_text(){
                if(status==1){
                    var text = $("#paper-txt").html();
                    var dir = $("#dir-val").val();
                    var public = $("#public-val").val();
                    var session = "<?php echo $hashed;?>";
                    $.post("files/php/page/createPage.php" , {
                                text: text,
                                dir: dir,
                                public: public,
                                type: type,
                                usession: session
                            },
                            function(result){
                                if(result!=null && result!=" "){
                                    location.reload();      
                                }
                                else{
                                    alert(result);
                                }
                            });
                }
            }
                        
       </script>
   </body>
   </html>
   <?php  
    }
}
else{
    header("Location: index.php");
}
?>