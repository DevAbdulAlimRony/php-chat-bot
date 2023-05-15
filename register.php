<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error = '';

$success_message = '';

if(isset($_POST["register"]))
{
    session_start();

    if(isset($_SESSION['user_data']))
    {
        header('location:chatroom.php');
    }

    require_once('database/ChatUser.php');

    $user_object = new ChatUser;

    $user_object->setUserName($_POST['user_name']);

    $user_object->setUserEmail($_POST['user_email']);

    $user_object->setUserPassword($_POST['user_password']);

    $user_object->setUserProfile($user_object->make_avatar(strtoupper($_POST['user_name'][0])));

    $user_object->setUserStatus('Disabled');

    $user_object->setUserCreatedOn(date('Y-m-d H:i:s'));

    $user_object->setUserVerificationCode(md5(uniqid()));

    $user_data = $user_object->get_user_data_by_email();

    if(is_array($user_data) && count($user_data) > 0)
    {
        $error = 'This Email Already Register';
    }
    else
    {
        if($user_object->save_data())
        {

            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username   = 'alim15@cse.pstu.ac.bd';
            $mail->Password   = 'imuccajvkqlhstjo';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->setFrom('alim15@cse.pstu.ac.bd', 'FriendsChat');

            $mail->addAddress($user_object->getUserEmail());

            $mail->isHTML(true);

            $mail->Subject = 'FriendsChat Registration Verification';

            $mail->Body = '
                <div class="container">
                <div class="row">
                <div class="text-center">
                <h1>&#128151;</h1>
               <h1 class="text-center">Friends Chat</h1>
               <h3>Friends like 1000 books</h3>
               </div>
                </div>
                <div class="row">
                <p>Thank you for registering for Friend Chat.</p>
                <p>This is a verification email, please click the link to verify your email address.</p>
                <p><a href="http://localhost/chat-app/verify.php?code='.$user_object->getUserVerificationCode().'" style="height: 35px; width: 200px; border-radius: 5px; background-color: #21ba79; color: #fff; font-family: Poppins, font-weight: 700;">Click to Verify</a></p>
                <p>Thank you...</p>
                </div>
                </div>
            ';

            $mail->send();


            $success_message = 'Verification Email sent to ' . $user_object->getUserEmail() . ', so before login first verify your email';
        }
        else
        {
            $error = 'Something went wrong try again';
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/x-icon" href="https://cdn1.dan.com/assets/public/benefits-transfers-374cef9ae50af8a199e7054cfe5092643d1c7659965fb9480022e0487d467606.svg" size="16*16">
    <title>FriendsChat: Join with Your Friends</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
    <style>
        body{
            overflow-x: hidden;
            background-image: url('https://www.electronics.com.bd/image/cache/catalog/banner/2021%20Banners/pattern/pattern-geek25-500x500.webp');
        }
        .register{
            width: 100%;
            background-color: #21ba79;
            border: 0;
        }
        .register:hover{
            background-color: #21ba79;
            opacity: 0.9;
        }
        .card{
            box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.14),0px 3px 1px -2px rgba(0,0,0,0.12),0px 1px 5px 0px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

    <div class="containter">
        <br />
        <br />
        <h1 class="text-center">Join the Chat Room and Find Your Friends</h1>
        
        <div class="row justify-content-md-center">
            <div class="col col-md-4 mt-5">
                <?php
                if($error != '')
                {
                    echo '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                      '.$error.'
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    ';
                }

                if($success_message != '')
                {
                    echo '
                    <div class="alert alert-success">
                    '.$success_message.'
                    </div>
                    ';
                }
                ?>
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">

                        <form method="post" id="register_form">

                            <div class="form-group">
                                <label>Enter Your Name</label>
                                <label for="" class="badge badge-success">Name shoul be one word</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" data-parsley-pattern="/^[a-zA-Z\s]+$/" required />
                            </div>

                            <div class="form-group">
                                <label>Enter Your Email</label>
                                <label for="" class="badge badge-success">We will send you verification code</label>
                                <input type="text" name="user_email" id="user_email" class="form-control" data-parsley-type="email" required />
                            </div>

                            <div class="form-group">
                                <label>Enter Your Password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control" data-parsley-minlength="6" data-parsley-maxlength="8" required />
                            </div>

                            <div class="form-group text-center">
                                <input type="submit" name="register" class="btn btn-success register" value="Register" />
                            </div>

                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</body>

</html>

<script>

$(document).ready(function(){

    $('#register_form').parsley();

});

</script>