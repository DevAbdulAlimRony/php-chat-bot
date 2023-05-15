<?php

session_start();

$error = '';

if(isset($_SESSION['user_data']))
{
    header('location:chatroom.php');
}

if(isset($_POST['login']))
{
    require_once('database/ChatUser.php');

    $user_object = new ChatUser;

    $user_object->setUserEmail($_POST['user_email']);

    $user_data = $user_object->get_user_data_by_email();

    if(is_array($user_data) && count($user_data) > 0)
    {
        if($user_data['user_status'] == 'Enable')
        {
            if($user_data['user_password'] == $_POST['user_password'])
            {
                $user_object->setUserId($user_data['user_id']);

                $user_object->setUserLoginStatus('Login');

                $user_token = md5(uniqid());

                $user_object->setUserToken($user_token);

                //if($user_object->update_user_login_data())
                //{
                    $_SESSION['user_data'][$user_data['user_id']] = [
                        'id'    =>  $user_data['user_id'],
                        'name'  =>  $user_data['user_name'],
                        'profile'   =>  $user_data['user_profile'],
                        'token' =>  $user_token
                    ];

                    header('location:chatroom.php');

               // }
            }
            else
            {
                $error = 'Wrong Password';
            }
        }
        else
        {
            $error = 'Please Verify Your Email Address';
        }
    }
    else
    {
        $error = 'Wrong Email Address';
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
    <title>FriendsChat: Log into Your Account</title>

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
            margin-top: -50px;
            background-image: url('images/login.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .logo{
            z-index:1000;
        }.login{
            width: 100%;
            background-color: #21ba79;
            border: 0;
        }
        .login:hover{
            background-color: #21ba79;
            opacity: 0.9;
        }
        .card{
            box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.14),0px 3px 1px -2px rgba(0,0,0,0.12),0px 1px 5px 0px rgba(0,0,0,0.2);
        }
        
    </style>
</head>

<body>

    <div class="container">
        <br />
        <br />
        <div class="text-center">
         <img src="https://cdn1.dan.com/assets/public/benefits-transfers-374cef9ae50af8a199e7054cfe5092643d1c7659965fb9480022e0487d467606.svg" alt="" width="80" height="80" class="logo">
        <h1 class="text-center">Friends Chat</h1>
        <h6>Friends like 1000 books</h6>
        </div>
        <div class="row justify-content-md-center mt-5">
            <div class="col-md-6">
                <img src="images/friends.svg" alt="" style="height: 450px; width: 450px;">
            </div>
            
            <div class="col-md-6">
               <?php
               if(isset($_SESSION['success_message']))
               {
                    echo '
                    <div class="alert alert-success">
                    '.$_SESSION["success_message"] .'
                    </div>
                    ';
                    unset($_SESSION['success_message']);
               }

               if($error != '')
               {
                    echo '
                    <div class="alert alert-danger">
                    '.$error.'
                    </div>
                    ';
               }
               ?>
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="post" id="login_form">
                            <div class="form-group">
                                <label>Enter Your Email Address</label>
                                <input type="text" name="user_email" id="user_email"  class="form-control" data-parsley-type="email" required />
                            </div>
                            <div class="form-group">
                                <label>Enter Your Password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control" required />
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                <div class="form-group text-center">
                                <input type="submit" name="login" id="login" class="btn  btn-primary login" value="Login" />
                                </div>
                              </div>
                              <div class="col-12">
                                  Didn't have any account?
                                  <a href="register.php">Join Class Room here</a>
                              </div>
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
    
    $('#login_form').parsley();
    
});

</script>