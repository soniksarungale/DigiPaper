<?php
session_start();
$connection = 0;
require_once('files/includes/conn.php');
$_SESSION["website"]="E-Paper";

$paper_id="";
if(isset($_SESSION["pid"])){
    unset($_SESSION["pid"]);
}
if(isset($_GET["paper"])){
    $paper=$_GET["paper"];
    $stmt4 = $conn->prepare("SELECT text, public, type FROM paper WHERE paper_id=?");
    $stmt4->bind_param("s", $paper);
    $stmt4->execute();
    $stmt4->bind_result($text,$public,$type);
    $stmt4->fetch();
    if($type!=NULL && $type!=""){
        $page_exist=1;
    }
    else{
        $error="Unable to find page";
    }
    $stmt4->close();
    ?>
    <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/paper.css">
       <style>
           .main-container .page-container{
               position: static;
               width: 100%;
           }
           .main-container{
               position: relative;
               width: 94%;
               margin-left: 3%;
           }
       </style>
   </head>
   <body>
               <header class="main-header">
                    <div class="header-right">
                       <div class="header-flex">
                           <div class="logo">
                               <a href="index.php"><i class="far fa-file-alt"></i> Digi<span class="logocolor">Paper</span></a>
                           </div>
                       </div>
                   </div>
           <div class="options">
               <div class="profile op">
                      <div class="menu-icon">
                        <i class="icon fas fa-bars"></i>
                        </div>
                   
                   <nav class="main-navigation vmt">
                       <ul>
                           <a href="index.php">
                               <li><i class="fas ic fa-sign-in-alt"></i> Sign up</li>
                           </a>
                           <a href="feedback.php">
                            <li><i class="fas ic fa-envelope"></i> Feedback</li>
                           </a>
                       </ul>
                   </nav>
               </div>
           </div>
       </header>
        <div class="main-container">
          <?php
           if(isset($error)){
               echo "<div style='text-align:center;margin:30px 0px;font-size:20px;width:100%;'>".$error."</div>"; 
           }
    elseif($public=="0"){
        echo "<div style='text-align:center;margin:30px 0px;font-size:20px;width:100%;'>This page is private</div>"; 
    }
        else{
           ?>
           <div class="page-container">
               <div class="paper" <?php if($type=="lines"){ echo 'id="line-paper"';}else{ echo 'id="blank-paper"';}?>>
                  <div class="lines">
                    <div class="text" id="paper-txt"><?php echo $text;?></div>
                  </div>
                  <div class="holes hole-top"></div>
                  <div class="holes hole-middle"></div>
                  <div class="holes hole-bottom"></div>
                </div>
           </div>
           <br>
           <?php
        }
            ?>
       </div>
       
    <script src="files/js/jquery.js"></script>
       <script src="files/js/main.js"></script>
   </body>
   </html>
    <?php
    
}
elseif(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"]) && isset($_GET["id"])){
    $hashed='';
$_SESSION['token'] = microtime(); 
if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
    $hashed = crypt($_SESSION['token'], $salt);
}
    $email=$_SESSION["email"];
    $id=$_SESSION["id"];
    $active=$_SESSION["active"];
    $level=$_SESSION["level"];
    $dp=$_SESSION["dp"];
    $paper_id=$_GET["id"];
    $selected = "home";
    if($level=="user" && $active==1){
        $stmt4 = $conn->prepare("SELECT text, time, date, year, paper_id, dir, public, type FROM paper
        WHERE user_id=? AND id=?");
                            $stmt4->bind_param("ss", $id,$paper_id);
                            $stmt4->execute();
                            $stmt4->bind_result($text,$time,$date,$year, $puid, $dir,$public,$type);
                            $stmt4->fetch();
                            if($dir!=NULL && $dir!=""){
                                $selected = $dir;
                                $dir_exist=1;
                            }
                            else{
                                $error="Unable to find page";
           }
           $stmt4->close();
?>
  <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper || <?php echo $email?></title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link rel="stylesheet" href="files/css/loader.css">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/paper.css">
   </head>
   <body>
       <header class="main-header withback">
                    <div class="header-right">
                       <div class="header-flex">
                           <div class="back-option">
                               <?php
                                $dir_id;
                               if(isset($dir_exist)){
                                    $stmt4 = $conn->prepare("SELECT id FROM dir WHERE name=? AND user_id=?");
                                    $stmt4->bind_param("si", $dir_name,$id);
                                    $dir_name=$dir;
                                    $stmt4->execute();
                                    $stmt4->bind_result($dir_id);
                                    $stmt4->fetch();
                                    if($dir_id!=NULL && $dir_id!=""){
                                        echo '<a href="dir.php?id='.$dir_id.'"><i class="fas fa-chevron-left"></i></a>';
                                        $dir_id_e=1;
                                    }
                                    else{
                                        echo '<a href="index.php"><i class="fas fa-chevron-left"></i></a>';
                                    }
                                    $stmt4->close();
                                   
                               }
                                else{
                                    echo '<a href="index.php"><i class="fas fa-chevron-left"></i></a>';
                                }
                               ?>
                                
                            </div>
                           <div class="logo">
                               <a href="index.php"><i class="far fa-file-alt"></i> Digi<span class="logocolor">Paper</span></a>
                           </div>
                       </div>
                   </div>
           <div class="options">
              <div class="saveop save-btn" onclick="save_text()">Save</div>
               <?php
           if(!isset($error)){
               if($public=="0"){
                    echo '<div class="shareop dsk enableshareop" onclick="enableshare()"><i class="fas fa-share-alt"></i> Enable Share</div><div class="dsk"><div class="shareop dsk showshareop" onclick="showshare()"><i class="fas fa-share-alt"></i> Share</div></div>';
               }
               else{
                   echo '<div class="shareop dsk" onclick="showshare()"><i class="fas fa-share-alt"></i> Share</div>';
               }
           }
        ?>    <div class="deleteop delete-btn" onclick="delete_paper()"><i class="fas fa-trash-alt"></i> <span class="delete-text">Delete</span></div>
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
                       <?php
           if(!isset($error)){
               ?>
              <div class="mob-options">
                   <div class="info">
                       <div class="viewdate">
                           <?php
                           echo $date.", ".$year;
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
        <?php }?>
       <div class="main-container flex">
          <?php
           if(isset($error)){
               echo "<div style='text-align:center;margin:30px 0px;font-size:20px;width:100%;'>".$error."</div>"; 
           }
        else{
           ?>
           <div class="page-container">
               <div class="paper" <?php if($type=="lines"){ echo 'id="line-paper"';}else{ echo 'id="blank-paper"';}?>>
                  <div class="lines">
                    <div class="text" id="paper-txt" contenteditable="true" spellcheck="true" data-input-length="10000"><?php echo $text;?></div>
                  </div>
                  <div class="holes hole-top"></div>
                  <div class="holes hole-middle"></div>
                  <div class="holes hole-bottom"></div>
                </div>
           </div>
           <div class="page-setting">
              <div class="mob share-opts">
                <?php
                   if(!isset($error)){
                       if($public=="0"){
                            echo '<div class="shareop mob mob-share enableshareop op" onclick="enableshare()"><i class="fas fa-share-alt"></i> Enable Share</div><div class="shareop mob showshareop mob-share op" onclick="showshare()"><i class="fas fa-share-alt"></i> Share</div>';
                       }
                       else{
                           echo '<div class="shareop op mob-share mob" onclick="showshare()"><i class="fas fa-share-alt"></i> Share</div>';
                       }
                   }
                ?>
              </div>
               <div class="info">
                   <div class="viewdate">
                       <?php
                       echo $date.", ".$year;
                       ?>
                   </div>
                    <pre> </pre><div class="viewtime"><?php
                        echo $time;   
                    ?></div>
               </div>
               <div class="type">
                   <div class="lines type-box" onclick="line_page(this)" <?php if($type=="lines"){ echo 'id="selected-option"';}?>>
                       <div class="box-holder">
                           <div class="icon"><i class="far ic fa-file-alt"></i></div>
                           <div class="type-text">Lines</div>
                       </div>
                   </div>
                   <div class="blank type-box" onclick="blank_page(this)" <?php if($type=="blank"){ echo 'id="selected-option"';}?>>
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
                   <select name="public-val" onchange="show_save()" id="public-val">
                       <option <?php
                       if($public==0){
                           echo "selected";
                       }
                       ?> value="private" class="private-option">Private</option>
                       <option <?php
                       if($public==1){
                           echo "selected";
                       }
                       ?> value="public" class="public-option">Public</option>   
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
            if($selected=="home"){
                echo '<option selected value="home">Home</option>';
            }
            else{
                echo '<option value="home">Home</option>';
            }
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
           
           <?php
           
        }
           ?>
       </div>
       <div class="blur blackblur">
            <div class="loader">
                        <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                              <?php
           if(!isset($error)){
               ?>
                    <div class="flex-holder">
                     <div class="share-link-box popup-box">
                     <div class="box-top-portion">
                         <div class="box-name">Share</div>
                         <div onclick="hideshare()" class="box-exit"><i class="fas fa-times"></i></div>
                     </div>
                     <div class="box-contain">
                         <input type="text" disabled selected id="share-link" placeholder="Paper url..." value="<?php echo str_replace('\\', '/', htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8').substr(htmlspecialchars(dirname(__FILE__), ENT_QUOTES, 'UTF-8'), strlen($_SERVER['DOCUMENT_ROOT']))).'/view.php?paper='.$puid?>">
                     </div>
                     <div class="box-options">
                         <button class="create" onclick="copylink()">Copy</button>
                     </div>
                 </div>
                 </div>
                 
                 <?php
           }
           ?>
             </div> 
             <div class="alert-holder">
                 <div class="alert-box">
                     <div class="alert">
                      <span class="closebtn" onclick="hide_alert()">&times;</span>  
                      <span class="alert-icon"></span>
                        <span class="alert-msg"></span>
                    </div>
                 </div>
             </div>
              
       <script src="files/js/jquery.js"></script>
       <script src="files/js/main.js"></script>
       <script>
            <?php
           if(!isset($error)){
                echo 'var type="'.$type.'";';
           }
        else{
            echo 'var type="lines";';
        }
           ?>

           function delete_paper(){
               Confirm('Delete', 'Are you sure you want to delete ?', 'Yes', 'Cancel', function () {
                var session = "<?php echo $hashed;?>";
                   $(".loader").fadeIn();
                        $(".blur").fadeIn();
                $.post("files/php/page/deletePaper.php" , {pid: <?php echo '"'.$paper_id.'"';?>,usession: session},
                    function(result){
                        $(".loader").fadeOut();
                        $(".blur").fadeOut();
                        if(result=="done"){
                            $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                            $(".alert-msg").text("Deleted!");
                            setTimeout(function(){
                            <?php
                                if(isset($dir_exist)){    
                                    if(isset($dir_id_e)){
                                        echo 'document.location="dir.php?id='.$dir_id.'";';
                                    }
                                    else{
                                        echo 'document.location="index.php";';
                                    }
                                }
                                else{
                                    echo 'document.location="index.php";';
                                }
                            ?>
                            }, 1000);
                            show_alert();
                        }
                        else{
                            $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                            $(".alert-msg").text("Error! refresh page and try again");
                            show_alert(); 
                        }
                });
            });
           }
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
           function blank_page(elm){
                $(".type-box").removeAttr("id","selected-option");
                $(elm).attr("id","selected-option");
                $(".paper").removeAttr("id","line-paper");
                $(".paper").attr("id","blank-paper");
               type="blank";
               show_save();
            }
            function line_page(elm){
                $(".type-box").removeAttr("id","selected-option");
                $(elm).attr("id","selected-option");
                $(".paper").removeAttr("id","blank-paper");
                $(".paper").attr("id","line-paper");
                type="lines";
                show_save();
            }
           function show_save(){
               $(".save-btn").fadeIn();
               status=1;
           }
           var status=0;
            $("#paper-txt").keyup(function(){
                var text = $("#paper-txt").html();
                var text_check=$.trim(text).replace(/[<]br[^>]*[>]/gi,"");
                if(text_check!=" " && text_check!="" && text_check!=null && text_check!="<br>"){
                   show_save();
                }
                else{
                    $(".save-btn").fadeOut();
                    status=0;
                }
            });
            function showshare(){
               $(".blur").fadeIn();
               $(".popup-box").fadeIn();
                $("#share-link").select();
           }
           function copylink() {
               var element=$("#share-link");
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).val()).select();
                document.execCommand("copy");
                $temp.remove();
                $("#copied span").text($(element).val());
               $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                    $(".alert-msg").text("Link copied");
                    show_alert();
               $("#share-link").select();
            }
           function hideshare(){
               $(".blur").fadeOut();
               $(".popup-box").fadeOut();
           }
                          <?php
           if(!isset($error)){
               if($public=="0"){
                   ?>
           

           function enableshare(){
               $(".blur").fadeIn();
               $(".loader").fadeIn();
                    var session = "<?php echo $hashed;?>";
                    $.post("files/php/page/enableShare.php" , {pid: <?php echo '"'.$paper_id.'"';?>,usession: session},
                            function(result){
                                $(".loader").fadeOut();
                                $(".blur").fadeOut();
                                if(result=="done"){
                                    $("#public-val .private-option").removeAttr("selected");
                                    $("#public-val .public-option").attr("selected",true);
                                    $(".enableshareop").css("display","none");
                                    $(".showshareop").fadeIn();
                                    
                                }
                            });
           }
           <?php   
               }
           }
        ?>
            function hide_alert(){
               $(".alert").stop().fadeOut("slow");
           }
           function show_alert(){
               $(".alert").stop().fadeIn("slow");
               setTimeout(function(){ $(".alert").stop().fadeOut(); }, 2000);
           }
           function save_text(){
                if(status==1){
                    var text = $("#paper-txt").html();
                    var dir = $("#dir-val").val();
                    var public = $("#public-val").val();
                    var session = "<?php echo $hashed;?>";
                    $(".blur").fadeIn();
                    $(".loader").fadeIn();
                    $.post("files/php/page/savePage.php" , {
                                paper_id: <?php echo '"'.$paper_id.'"';?>,
                                text: text,
                                dir: dir,
                                public: public,
                                type: type,
                                usession: session
                            },
                            function(result){
                        $(".loader").fadeOut();
                        $(".blur").fadeOut();
                                if(result=="Updated"){
                                    $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                                    $(".alert-msg").text("Changes has been saved");
                                    show_alert();
                                }
                                else{
                                    $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                                    $(".alert-msg").text("Error! refresh page and try again");
                                    show_alert();   
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