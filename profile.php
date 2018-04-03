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
        $stmt3 = $conn->prepare("SELECT created_on, time_zone, modified_on FROM users WHERE user_id=?");
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $stmt3->bind_result($created_on, $time_zone, $modified_on);
        $stmt3->fetch();
        if($created_on==NULL || $created_on==""){
            $error = "Error While finding your profile";
        }
        else{
            
?>
<!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <title>DigiPaper Dir || <?php echo $email;?></title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
       <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
              <link rel="stylesheet" href="files/css/loader.css">
       <link rel="stylesheet" href="files/css/main.css">
       <link rel="stylesheet" href="files/css/extrapage.css">
   </head>
   <body>
        <div class="body profilebody">
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
           <div class="holder">
              <div class="setting-body">
                  <div class="box-holder">
                      <div class="box">
                          <div class="box-padding">
                              <h2>My Profile</h2>
                          <div class="box-flex profile-box">
                              <div class="user-img lhs">
                                  <?php
                            if($dp=="0"){
                                echo '<div class="user-dp user-icon">';
                                echo '<i class="fas fa-user"></i>';
                                echo '</div>';
                                echo '<a href="auth/google/photo.php">
                                    <button class="upload-photo">
                                        Upload Photo From Google
                                    </button>
                                </a>';
                            }
                            else{
                                echo '<div class="user-dp">';
                                echo '<img src="'.$dp.'">';
                                echo '</div>';
                            }
                       ?>
                                
                              </div>
                              <div class="user-state rhs">
                                  <div class="state-holder">
                                        <div class="input-box">
                                            <label for="usermail">Email</label>
                                      <input type="email" disabled id="usermail" value="<?php echo $email;?>">
                                        </div>
                                      <div class="input-box">
                                          <label for="createdon">Created On</label>
                                      <input type="text" disabled id="createdon" value="<?php echo $created_on;?>">
                                      </div>
                                      <div class="input-box">
                                          <label for="timezone">Time Zone</label>
                                      <input type="text" disabled id="timezone" value="<?php echo $time_zone;?>">
                                      </div>
                                  </div>
                              </div>
                          </div>
                          </div>
                          <hr>
                      </div>
                      <div class="box otherbox changeemail">
                          <div class="box-padding">
                              <div class="box-flex">
                                <div class="lhs blhs">
                                    <h3>Change Email</h3>
                                    <p>
                                        You can change to your email if it is not been use by other user
                                    </p>
                                </div>
                                <div class="rhs">
                                    <div class="input-box">
                                            <label for="usermail">Current Email</label>
                                      <input type="email" id="usermailadd" value="<?php echo $email;?>">
                                       <button onclick="changeemail()" class="pbutton">Change Email</button>
                                        </div>
                                </div>
                              </div>
                          </div>
                          <hr>
                      </div>
                      <div class="box otherbox changepass">
                          <div class="box-padding">
                              <div class="box-flex">
                                <div class="lhs blhs">
                                    <h3>Change Password</h3>
                                    <p>
                                        Use this option to reset your password.
                                    </p>
                                </div>
                                <div class="rhs">
                                    <div class="checkpass">
                                        <div class="input-box">
                                            <label for="currentpass">Current Password</label>
                                      <input type="password" id="currentpass" placeholder="Enter your current password...">
                                       <button class="pbutton checkpassbtn" onclick="checkpass()">Check Password</button>
                                        </div>
                                    </div>
                                    <div class="newpass passbox">
                                        <div class="input-box">
                                            <label for="newpass">New Password</label>
                                      <input type="password" id="newpass" placeholder="Enter new password...">
                                       <button class="pbutton newpassbtn" onclick="storepass()">Next</button>
                                        </div>
                                    </div>
                                    <div class="repeatpass passbox">
                                        <div class="input-box">
                                            <label for="repass">Re-enter Password</label>
                                      <input type="password" id="repass" placeholder="Re-enter your new password...">
                                       <button class="pbutton repassbtn" onclick="changepass()">Change Password</button>
                                        </div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <hr>
                      </div>
                      <div class="box otherbox colseac">
                          <div class="box-padding">
                              <div class="box-flex">
                                <div class="lhs blhs">
                                    <h3>Close Account</h3>
                                    <p>
                                        Closing your account is irreversible & all data will be lost
                                    </p>
                                </div>
                                <div class="rhs">
                                    <div class="input-box">
                                       <button class="pbutton closeacbtn" onclick="closeac()">Close this account</button>
                                        </div>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
           </div>
       </div>    
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
           var currentemail="<?php echo $email;?>";
           $("#usermailadd").on('input',function(){
               var newemail = $.trim(this.value);
               if(newemail!=currentemail){
                   $(".changeemail .pbutton").fadeIn();
               }
               else{
                   $(".changeemail .pbutton").fadeOut();
               }
           });
            $("#currentpass").on('input',function(){
               var curpass = this.value;
               if(curpass.length>=1){
                   $(".checkpassbtn").fadeIn();
               }
               else{
                   $(".checkpassbtn").fadeOut();
               }
           });
            $("#newpass").on('input',function(){
               var curpass = this.value;
               if(curpass.length>=1){
                   $(".newpassbtn").fadeIn();
               }
               else{
                   $(".newpassbtn").fadeOut();
               }
           });
            $("#repass").on('input',function(){
               var curpass = this.value;
               if(curpass.length>=1){
                   $(".repassbtn").fadeIn();
               }
               else{
                   $(".repassbtn").fadeOut();
               }
           });
           function changeemail(){
               var newemail = $.trim($("#usermailadd").val());
               var session = "<?php echo $hashed;?>";
               $(".blur").fadeIn();
                $(".loader").fadeIn();
                $.post("files/php/page/changeEmail.php" , { email: newemail,usession:session},
                function(result){
                    $(".blur").fadeOut();
                    $(".loader").fadeOut(); 
                    if(result=="done"){
                        $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                        $(".alert-msg").text("Email sucessfully changed");
                        show_alert();
                        currentemail=newemail;
                        $("#usermail").val(newemail);
                        $(".changeemail .pbutton").fadeOut();
                    }
                    else if(result=="invalid"){
    
                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                        $(".alert-msg").text("Email already exist");
                        show_alert();
                    }
                    else{
                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                        $(".alert-msg").text("Error! refresh page and try again");
                        show_alert();
                    }
                    
                });
           }
           var passstate=0;
           function checkpass(){
                var cpass = $("#currentpass").val();
               var session = "<?php echo $hashed;?>";
               $(".blur").fadeIn();
                $(".loader").fadeIn();
                $.post("files/php/page/changePass.php" , { cpass: cpass,usession:session},
                function(result){
                    $(".blur").fadeOut();
                    $(".loader").fadeOut(); 
                    if(result=="correct"){
                        passstate=1;
                        $(".checkpass").css("display","none");
                        $(".newpass").fadeIn();
                    }
                    else if(result=="incorrect"){
                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                        $(".alert-msg").text("Password is incorrect");
                        show_alert();
                    }
                    else{
                        $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                        $(".alert-msg").text("Error! refresh page and try again");
                        show_alert();
                    }                 
                });
           }
           function storepass(){
               $(".newpass").css("display","none");
               $(".repeatpass").fadeIn();
           }
           function changepass(){
               var repass = $("#repass").val();
                var cpass = $("#currentpass").val();
               var newpass = $("#newpass").val();
               var session = "<?php echo $hashed;?>";
               if(repass==newpass){
                   if(passstate==1){
                  $(".blur").fadeIn();
                    $(".loader").fadeIn();
                    $.post("files/php/page/changePass.php" , { cpass: cpass,newpass: newpass,usession:session},
                    function(result){
                        $(".blur").fadeOut();
                        $(".loader").fadeOut(); 
                        if(result=="done"){
                            $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                            $(".alert-msg").text("Password sucessfully changed");
                            show_alert();
                            $(".changepass input").val("");
                            $(".repeatpass").css("display","none");
                            $(".checkpass").fadeIn();
                            
                        }
                        else if(result=="incorrect"){
                            $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                            $(".alert-msg").text("Password is incorrect");
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
               else{
                   $(".alert-icon").html('<i class="fas error fa-exclamation-circle"></i>');
                    $(".alert-msg").text("Re Enter password correctly");
                    show_alert();
               }
           }
           function closeac(){
               Confirm('Close Account', 'Closing your account is irreversible & all data will be lost', 'Close Account', 'Cancel', function () {
                    $(".blur").fadeIn();
                    $(".loader").fadeIn();
                   var session = "<?php echo $hashed;?>";
                $.post("files/php/page/closeac.php" , { close:"yes",usession:session},
                function(result){
                    $(".blur").fadeOut();
                    $(".loader").fadeOut(); 
                    if(result=="done"){
                            $(".alert-icon").html('<i class="fas success fa-check-circle"></i>');
                            $(".alert-msg").text("Account closed permanently!");
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
       </script>
   </body>
   </html>
<?php  
            
        }
    }
}
else{
    header("Location: index.php");
}
?>