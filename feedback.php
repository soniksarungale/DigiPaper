<?php
$message="";
	function check_input($data, $problem='')
	{
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    if ($problem && strlen($data) == 0)
	    {
	        show_error($problem);
	    }
	    return $data;
	}
if(isset($_POST['name'])){
	$myemail  = "contactdigipaper@gmail.com";
	$yourname = check_input($_POST['name'], "Enter your name");
	$subject  = $_POST['subject'];
	$email    = check_input($_POST['email']);
	$message = check_input($_POST['message'], "Write your message");

	if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
	{
	    show_error("E-mail address not valid");
	}

	$message = "Hello!

	Your contact form has been submitted by:

	Name: $yourname
	E-mail: $email

	message:
	$message

	End of message
	";

	if(mail($myemail, $subject, $message)){
		$message = "Feedback sucessfully sended";
	}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">	     
      <meta name="viewport" content="width=device-width, initial-scale=1">  
	<title>Feed back</title>
	       <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	       <link rel="stylesheet" href="files/css/bootstrap.min.css">
       <link href="https://fonts.googleapis.com/css?family=Lato|Catamaran:600" rel="stylesheet">
	<link rel="stylesheet" href="files/css/main.css">
	<style type="text/css">
		.container{
			margin-top: 100px;
		}
		a{
			text-decoration: none;
			color: inherit;
		}
		a:hover{
			text-decoration: none;
			color: inherit;
		}
 .contact-text{
   margin:45px auto;
 }

 .mail-message-area{
   width:100%;
   padding:0 15px;
 }

 .mail-message{
   width: 100%;
   background:rgba(255,255,255, 0.8) !important;
   -webkit-transition: all 0.7s;
   -moz-transition: all 0.7s;
   transition: all 0.7s;
   margin:0 auto;
   border-radius: 0;
 }

 .not-visible-message{
   height:0px;
   opacity: 0;
 }
	.center{
		text-align: center;
	}
 .visible-message{
   height:auto;
   opacity: 1;
   margin:25px auto 0;
 }

/* Input Styles */

 .form{
   width: 100%;
   padding: 15px;
   background:#f8f8f8;
   border:1px solid rgba(0, 0, 0, 0.075);
   margin-bottom:25px;
   color:#727272 !important;
   font-size:13px;
   -webkit-transition: all 0.4s;
   -moz-transition: all 0.4s;
   transition: all 0.4s;
 }

 .form:hover{
   border:1px solid #009688;
 }

 .form:focus{
   color: white;
   outline: none;
   border:1px solid #009688;
 }

 .textarea{
   height: 200px;
   max-height: 200px;
   max-width: 100%;
 }

/* Generic Button Styles */

 .button{
   padding:8px 12px;
   background:#0A5175;
   display: block;
   width:120px;
   margin:10px 0 0px 0;
   border-radius:3px;
   -webkit-transition: all 0.3s;
   -moz-transition: all 0.3s;
   transition: all 0.3s;
   text-align:center;
   font-size:0.8em;
   box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
   -moz-box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
   -webkit-box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
 }

 .button:hover{
   background:#8BC3A3;
   color:white;
 }

/* Send Button Styles */

 .form-btn{
   width:180px;
   display: block;
   height: auto;
   padding:15px;
   color:#fff;
   background:#009688;
   border:none;
   border-radius:3px;
   outline: none;
   -webkit-transition: all 0.3s;
   -moz-transition: all 0.3s;
   transition: all 0.3s;
   margin:auto;
   box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
   -moz-box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
   -webkit-box-shadow: 0px 1px 4px rgba(0,0,0, 0.10);
 }

 .form-btn:hover{
   background:#111;
   color: white;
   border:none;
 }

 .form-btn:active{
   opacity: 0.9;
 }
center{
 margin-top:330px;
}
input {
   position: relative;
   z-index: 9999;
}
.container{
  margin-bottom: 50px;
}
.credit{
  position: fixed;
  bottom: 0px;
  width: 100%;
  height: 40px;
  text-align: center;
  line-height: 40px;
  left: 0px;
  background-color: #eee;
}
.credit a{
  text-decoration: none;
  color: inherit;
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
                       </ul>
                   </nav>
               </div>
           </div>
       </header>
 <div class="container">
 <h3 class="center"><?php echo $message;?></h3>
    <div class="inner contact">
                <div class="contact-form">
                    <form id="contact-us" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="col-xs-6 wow animated slideInLeft" data-wow-delay=".5s">
                            <input type="text" name="name" id="name" required="required" class="form" placeholder="Name" />
                            <input type="email" name="email" id="mail" required="required" class="form" placeholder="Email" />
                            <input type="text" name="subject" id="subject" required="required" class="form" placeholder="Subject" />
                        </div>
                        <div class="col-xs-6 wow animated slideInRight" data-wow-delay=".5s">
                            <textarea name="message" id="message" class="form textarea"  placeholder="Message"></textarea>
                        </div>
                        <div class="relative fullwidth col-xs-12">
                            <button type="submit" id="submit" name="submit" class="form-btn semibold">Send Message</button>
                        </div>
                        <div class="clear"></div>
                    </form>

                    <div class="mail-message-area">
                        <div class="alert gray-bg mail-message not-visible-message">
                            <strong>Thank You !</strong> Your email has been delivered.
                        </div>
                    </div>

                </div>
            </div>
            <br /><br /><br />
            <div class="credit">
              Designed By Kunal Jogi & Developed By <a href="http://soniksarungale.cf">Sonik Sarungale</a>
            </div>
  </div>
    <script src="files/js/jquery.js"></script>
       <script src="files/js/main.js"></script>
</body>
</html>