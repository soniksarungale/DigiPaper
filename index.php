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

if(isset($_SESSION["email"]) && isset($_SESSION["dp"]) && isset($_SESSION["id"]) && isset($_SESSION["active"]) && isset($_SESSION["level"])){
    $email=$_SESSION["email"];
    $id=$_SESSION["id"];
    $active=$_SESSION["active"];
    $level=$_SESSION["level"];
    $dp=$_SESSION["dp"];
    if($level=="user" && $active==1){
        
?>
  
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper || <?php echo $email?></title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/home-page.css">
   </head>
   <body>
        <div class="body">
           <header class="main-header">
           <div class="logo">
               <a href="index.php"><i class="far fa-file-alt"></i> Digi<span class="logocolor">Paper</span></a>
           </div>
           <div class="options">
               <div class="addfile op">
                   <a href="newpage.php"><button class="tbtn"><i class="fas fa-plus"></i></button></a>
               </div>
               <div class="addfile op">
                   <button onclick="create_folder()" type="button" class="tbtn"><i class="fas fa-folder"></i></button>
               </div> 
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
        
       <div class="main-container">
           <div class="contain-holder">
               <div class="folder-holder">
               <?php 
                   $stmt7 = $conn->prepare("SELECT id, name FROM dir WHERE user_id=? ORDER BY id DESC");
        $stmt7->bind_param("s", $id);
        $stmt7->execute();
        $stmt7->bind_result($f_id, $f_name);
        while ($stmt7->fetch()) { 
            echo '<div class="folder-width">
                        <div class="folder" data-location="'.$f_id.'">
                              <div class="folder-box">
                               <div class="folder-icon"><i class="fas ficon fa-folder"></i></div>
                               <div class="folder-name">'.$f_name.'</div>
                           </div>
                       </div>
                   </div>';
        }
        $stmt7->close();
                   ?>
               </div>
               <div class="paper-holder">
                  <?php 
                   $stmt = $conn->prepare("SELECT id, text, type FROM paper WHERE user_id=? AND dir='home' ORDER BY id DESC");
        $stmt->bind_param("s", $id);
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
        $stmt->close();
        if($paper==0){
                echo '<a href="newpage.php">
                <div class="add-paper">
                    <div class="add-icon"><i class="fas fa-plus-circle"></i></div>
                </div>
            </a>';
        }
                   ?>
               </div>
           </div>
       </div>
        </div>
             <div class="blur">
                 <div class="flex-holder">
                     <div class="add-folder-box popup-box">
                     <div class="box-top-portion">
                         <div class="box-name">Create Folder</div>
                         <div onclick="exit_box()" class="box-exit"><i class="fas fa-times"></i></div>
                     </div>
                     <div class="box-contain">
                         <input type="text" id="new-folder-name" placeholder="Enter File Name...">
                     </div>
                     <div class="box-options">
                         <button class="create" onclick="create_new_folder()">Create</button>
                     </div>
                 </div>
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
           if($(".folder-holder").html().trim()==""){
               $(".folder-holder").css("padding","0px");
           }
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
           function create_new_folder(){
               var file_name=$.trim($("#new-folder-name").val()).replace(/[<]br[^>]*[>]/gi,"").replace(/\s+/g, '-');
               var session = "<?php echo $hashed;?>";
               if(file_name.length>=1){
                   if(file_name.length<=30){
                       if(file_name=="home"){
                           $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                            $(".alert-msg").text("This folder name is not allowed");
                            show_alert();
                       }
                       else{
                                $.post("files/php/page/createFile.php" , {
                                    name: file_name,
                                    usession: session
                                },
                                function(result){
                                    if(result=="exist"){
                                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                                        $(".alert-msg").text("This file already exist");
                                        show_alert();
                                    }
                                    else if(result=="Error"){
                                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                                        $(".alert-msg").text("Error! Plese refresh page and try again");
                                        show_alert();
                                    }
                                    else if(result=="Error while creating dir"){
                                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                                        $(".alert-msg").text(result);
                                        show_alert();      
                                    }
                                    else if(result=="home not allowed"){
                                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                                        $(".alert-msg").text("This folder name is not allowed");
                                        show_alert();
                                    }
                                    else{
                                         $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                                        $(".alert-msg").text("Folder is Created!");
                                        show_alert();
                                        exit_box();
                                        var newfolder=`<div class="folder-width">
                            <div class="folder" data-location="`+result+`">
                                  <div class="folder-box">
                                   <div class="folder-icon"><i class="fas ficon fa-folder"></i></div>
                                   <div class="folder-name">`+file_name+`</div>
                               </div>
                           </div>
                       </div>`;
                                        $(".folder-holder" ).prepend(newfolder);
                                        $("#new-folder-name").val("");
                                        $(".folder").click(function(){
                                          window.location="dir.php?id="+$(this).data("location");
                                       });
                                    }
                                });
                       }

                   }
                   else{
                       $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                       $(".alert-msg").text("Folder name is too large");
                       show_alert();     
                   }
               }
               else{
                   $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                    $(".alert-msg").text("Enter Folder name");
                    show_alert();     
               }
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
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DigiPaper - Write save and share text online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
    <link rel="stylesheet" href="files/css/loader.css">
    <link rel="stylesheet" href="files/css/home.css">
</head>
<body>
    
    <div class="body">
        <div class="shade">
            <div class="login-header">
                <div class="logo">
                    <a href="index.php"><i class="fa fa-file-text-o" aria-hidden="true"></i> DigiPaper</a>
                </div>
                <div class="sub-nav">
                    <div class="nav-login"><button onclick="show_login()">Login</button></div
                    ><div class="nav-menu"><button onclick="show_menu()"><i class="fa fa-bars" aria-hidden="true"></i></button>
                    <ul class="menu-list">
                        <a href="feedback.php"><li>FeedBack</li></a>
                    </ul>
                    </div>
                    
                </div>
            </div>
            <div class="login-page">
                <div class="left-box">
                    <div class="page-info">
                        <h1>Welcome to the <b>Digital</b> world, Save tress,  <span class="break">Go paperless</span></h1>
                        <p>Here you can create, edit, save your digital paper and also can share with your friends </p>
                    </div>
                </div>
                <div class="right-box">
                    <div class="user">
                        <div class="create-ac-box">   
                            <form action="" method="post">
                                <div class="first-step-create">
                                    <div class="box-heading">Sign up for <b>Free</b></div>
                                    <div class="error"></div>
                                    <input type="email" class="input" id="cemail" name="email" placeholder="Email...">
                                    <div class="passwordinput">
                                        <input type="password" class="input" name="pass" id="cpassword" placeholder="New Password..."><button type="button" onclick="passVisibility('cpassword',this)"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="passwordinput">
                                        <input type="password" class="input" name="passrepet" id="crpassword" placeholder="Repeat Password..."><button type="button" onclick="passVisibility('crpassword',this)"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    </div>
                                    <button type="button" class="boxbtn" onclick="createAccount()">Create account</button>
                                    <br>
                                    <!--div class="google-login-btn">
                                        <br>
                                        <a href="auth/google/">
                                           <div id="gSignInWrapper">
                                                <div id="customBtn" class="customGPlusSignIn">
                                                  <span class="icon"></span>
                                                  <span class="buttonText"> SIGNUP WITH GOOGLE+ </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div-->
                                    <br>
                                    Already an member ? <button type="button" id="showLoginBtn" class="togglebtn" onclick="show_login()">Log In</button> Now.
                                    
                                </div>                                        
                            </form>
                        </div>
                        <div class="login-ac-box">
                            <form action="" method="post">
                                <div class="box-heading">Login to your <b>Account</b></div>
                                <div class="error"></div>
                                <input type="email" class="input" name="email" id="loginEmail" placeholder="Your Email...">
                                <div class="passwordinput">
                                        <input type="password" id="loginPass" class="input" name="pass" placeholder="Your Password..."><button type="button" onclick="passVisibility('loginPass',this)"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                </div>
                                <button type="button" class="boxbtn" onclick="loginUser()">Login</button>
                                <br>
                                <!--div class="google-login-btn">
                                    <br>
                                    <a href="auth/google/">
                                        <div id="gSignInWrapper">
                                            <div id="customBtn" class="customGPlusSignIn">
                                                <span class="icon"></span>
                                                <span class="buttonText"> LOGIN WITH GOOGLE+ </span>
                                            </div>
                                        </div>
                                    </a>
                                </div-->
                                <br>
                                Don't have account? <button type="button" id="showCreateBtn" class="togglebtn" onclick="show_create()">Create Account</button> Now.
                            </form>
                        </div>
                                            <div class="loader">
                        <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="files/js/jquery.js"></script>
    <script src="files/js/js1.js"></script>
    <script>
function createAccount(){
        var newpass = $.trim($("#cpassword").val());
        var repeatpass = $.trim($("#crpassword").val());
    var email = $.trim($("#cemail").val());
    if(email == "" || newpass == "" || repeatpass == ""){
       $(".user .create-ac-box .first-step-create .error").html("All fields required");
        if(email == ""){
           $("#cemail").css("border-color","red");
        }
        else{
            $("#cemail").css("border-color","rgba(0, 0, 0, 0.075)");
        }
            if(newpass == ""){
               $("#cpassword").css("border-color","red");
            }
            else{
                $("#cpassword").css("border-color","rgba(0, 0, 0, 0.075)");
            }
            if(repeatpass == ""){
               $("#crpassword").css("border-color","red");
            }
            else{
                $("#crpassword").css("border-color","rgba(0, 0, 0, 0.075)");
            }
    }
    else{
        if(newpass!=repeatpass){
           $(".user .create-ac-box .first-step-create .error").html("Both password should match");
            $("#cpassword").css("border-color","red");
            $("#crpassword").css("border-color","red");
        }
        else{
           $(".login-page .right-box .user .create-ac-box .first-step-create .input").css("border-color","rgba(0, 0, 0, 0.075)");
            if(isEmail(email)){
                $(".loader").fadeIn();
                $.post("files/php/login/exist.php" , { email: email },

                function(result){
                    if(result=="valid"){
                        $(".user .create-ac-box .first-step-create .error").html('');
                        $(".login-page .right-box .user .create-ac-box .first-step-create .input").css("border-color","rgba(0, 0, 0, 0.075)");
                        var email = $.trim($("#cemail").val());
                        var newpass = $.trim($("#cpassword").val());
                        var repeatpass = $.trim($("#crpassword").val());
                        var session = "<?php echo $hashed;?>";
                        if(newpass == repeatpass){
                            $(".loader").fadeIn();

                            $.post("login/createac.php" , {
                                email: email,
                                pass: newpass,
                                rpass: repeatpass,
                                usession: session
                            },
                            function(result){
                                if(result=="done"){
                                   location.reload();
                                }
                                else{
                                    console.log(result);
                                }
                                $(".loader").fadeOut();
                            });
                        }
                        else{
                            $(".user .create-ac-box .second-step-create .error").html("Both Password should be same");
                            $("#cpassword").css("border-color","red");
                            $("#crpassword").css("border-color","red");
                        }
                        $(".loader").fadeOut();
                    }
                    else if(result=="invalid"){
                        $(".user .create-ac-box .first-step-create .error").html('This Email already exist');
                        $("#cemail").css("border-color","red");
                        $(".loader").fadeOut();
                    }
                });   
            }
            else{
                $(".user .create-ac-box .first-step-create .error").html("Enter valid email address");
                $("#cemail").css("border-color","red");
            }  
        }
    }
    
}
function loginUser(){
    var pass = $.trim($("#loginPass").val());
    var email = $.trim($("#loginEmail").val());
    if(email == "" || pass == ""){
       $(".user .login-ac-box .error").html("All fields required");
        if(email == ""){
           $("#loginEmail").css("border-color","red");
        }
        else{
            $("#loginEmail").css("border-color","rgba(0, 0, 0, 0.075)");
        }
        if(pass == ""){
           $("#loginPass").css("border-color","red");
        }
        else{
            $("#loginPass").css("border-color","rgba(0, 0, 0, 0.075)");
        }
    }
    else{
           $(".login-page .right-box .user .login-ac-box .input").css("border-color","rgba(0, 0, 0, 0.075)");
            if(isEmail(email)){
                $(".loader").fadeIn();
                $.post("files/php/login/exist.php" , { email: email},

                function(result){
                    if(result=="invalid"){
                        $(".user .login-ac-box .error").html('');
                        $(".login-page .right-box .user .login-ac-box .input").css("border-color","rgba(0, 0, 0, 0.075)");
                        var pass = $.trim($("#loginPass").val());
                        var email = $.trim($("#loginEmail").val());
                        var session = "<?php echo $hashed;?>";
                            $(".loader").fadeIn();
                            $.post("login/login.php" , {
                                email: email,
                                pass: pass,
                                usession: session
                            },

                            function(result){
                                if(result=="done"){
                                    $(".user .login-ac-box .error").html('');
                                    $(".login-page .right-box .user .login-ac-box .input").css("border-color","rgba(0, 0, 0, 0.075)");
                                    location.reload();
                                }
                                else{
                                    $(".user .login-ac-box .error").html("Email or password is wrong");
                                    $(".login-page .right-box .user .login-ac-box .input").css("border-color","red");
                                }
                                $(".loader").fadeOut();
                            });
                        $(".loader").fadeOut();
                    }
                    else{
                        $(".user .login-ac-box .error").html('This Email does not exist');
                        $("#loginEmail").css("border-color","red");
                        $(".loader").fadeOut();
                    }
                });   
            }
            else{
                $(".user .login-ac-box .error").html("Enter valid email address");
                $("#cemail").css("border-color","red");
            }  
    }
    
}
    </script>
</body>
</html>
<?php
}
?>