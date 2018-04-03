<?php
session_start();
$_SESSION["website"]="E-Paper";
$hashed='';
$_SESSION['token'] = microtime(); 

$connection = 0;
require_once('files/includes/conn.php'); 
if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
    $hashed = crypt($_SESSION['token'], $salt);
}

if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"]) && isset($_GET["id"])){
    $email=$_SESSION["email"];
    $id=$_SESSION["id"];
    $active=$_SESSION["active"];
    $level=$_SESSION["level"];
    $dp=$_SESSION["dp"];
    $dir_id=$_GET["id"];
    
    if($level=="user" && $active==1){
        $stmt3 = $conn->prepare("SELECT dir_id, name, public FROM dir WHERE id=? AND user_id=?");
        $stmt3->bind_param("ss", $dir_id,$id);
        $stmt3->execute();
        $stmt3->bind_result($dir_u_id, $name, $public);
        $stmt3->fetch();
        if($name==NULL || $name==""){
            $error = "Folder doesn't found";
        }
        $stmt3->close();
?>
<!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper Dir || <?php if(!isset($error)){ echo $name;}else{ echo $email;}?></title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
              <link rel="stylesheet" href="files/css/loader.css">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/home-page.css">
   </head>
   <body>
        <div class="body">
                   <header class="main-header withback">
                   <div class="header-right">
                       <div class="header-flex">
                           <div class="back-option">
                                <a href="index.php"><i class="fas fa-chevron-left"></i></a>
                            </div>
                           <div class="logo">
                               <a href="index.php"><i class="far fa-file-alt"></i> Digi<span class="logocolor">Paper</span></a>
                           </div>
                       </div>
                   </div>
           <div class="options">
              <?php
               if(!isset($error)){
                   echo '<div class="addfile op">
                   <a href="newpage.php?dir='.$dir_id.'"><button class="tbtn"><i class="fas fa-plus"></i></button></a>
               </div>';
               }
               ?>
               <div class="deleteop op delete-btn dir-del" ><button onclick="delete_dir()"><i class="fas fa-trash-alt"></i></button></div>
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
    if(isset($error)){
       echo "<div style='text-align:center;margin:30px 0px;font-size:20px;'>".$error."</div>"; 
    }        
    else{
    ?>
       <div class="main-container">
           <div class="contain-holder">
              
               <div class="paper-holder">
                  <?php 
                   $stmt = $conn->prepare("SELECT id, text, type FROM paper WHERE user_id=? AND dir=? ORDER BY id DESC");
        $stmt->bind_param("ss", $id,$name);
        $stmt->execute();
        $stmt->bind_result($g_id, $g_text, $g_type);
        $paper=0;
            while ($stmt->fetch()) {
                $paper=1;
                echo '<div class="paper-width">
                           <div class="paper-box">
                           <div class="paper ';  
                if($g_type=="blank"){ echo 'blank-paper';
                                   }else{echo 'line-paper';}
                echo '" data-location="'.$g_id.'">
                      <div class="lines">
                        <div class="text">'.$g_text.'</div>
                      </div>
                      <div class="holes hole-top"></div>
                      <div class="holes hole-middle"></div>
                      <div class="holes hole-bottom"></div>
                    </div>
                       </div>
                       </div>';


            }
            if($paper==0){
                echo '<a href="newpage.php?dir='.htmlspecialchars($dir_id, ENT_QUOTES, 'UTF-8').'">
                <div class="add-paper">
                    <div class="add-icon"><i class="fas fa-plus-circle"></i></div>
                </div>
            </a>';
            }
        
        $stmt->close();
                   ?>
               </div>
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
           ?>
            function delete_dir(){
               Confirm('Delete', 'Files will also be deleted from this folder !', 'Delete', 'Cancel', function () {
                var session = "<?php echo $hashed;?>";
                   $(".loader").fadeIn();
                        $(".blur").fadeIn();
                $.post("files/php/page/deleteDir.php" , {name: <?php echo '"'.$name.'"';?>,usession: session},
                    function(result){
                    
                        $(".loader").fadeOut();
                        $(".blur").fadeOut();
                        if(result=="done"){
                            $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                            $(".alert-msg").text("Deleted!");
                            setTimeout(function(){
                                document.location="index.php";
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
           <?php
            }
           ?>
           function hide_alert(){
               $(".alert").stop().fadeOut("slow");
           }
           function show_alert(){
               $(".alert").stop().fadeIn("slow");
               setTimeout(function(){ $(".alert").stop().fadeOut(); }, 2000);
           }
           function exit_box(){
               $(".popup-box").fadeOut();
               $(".blur").fadeOut();
           }
           function create_folder(){
               $(".blur").fadeIn();
               $(".popup-box").fadeIn();
           }
       $(".paper").click(function(){
          window.location="view.php?id="+$(this).data("location");
       });
           $(".folder").click(function(){
          window.location="dir.php?id="+$(this).data("location");
       });
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