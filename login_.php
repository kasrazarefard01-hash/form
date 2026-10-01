<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.46.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
    <title>Document</title>
</head>
<body>
    <?php
        $error = [];

        $db = new PDO("mysql:host=localhost;dbname=user;charset=utf8mb4","root","");
        $comand = "SELECT * FROM users_data WHERE email = :email";



        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $email = $_POST["input-email"];
            $password = $_POST["input-password"];

            if(empty(trim($email))){
                $error["empty-email"] = '<span>please enter your email</span>';
            }

            if(empty(trim($password))){
                $error["empty-password"] = '<span>please enter your password</span>';
            }

            # read from databace #

            $st = $db->prepare($comand);
            $st->bindValue(":email",$email);
            $st->execute();

            $db_info = $st->fetch();
            
            if(!$db_info|| !password_verify($password,$db_info["password"])){
                $error["password-email"] = "<span>Invalid email or password</span>";
            }
            
            if(empty($error)){
                # header("location:main.html"); #
                # exit(); #    
            }

        }
    ?>

    <?php
        require 'vendor/autoload.php';
        $client = new Google_Client();
        $client->setClientId("487768610044-c5udvdok09j9eg3tn1kb2eo48lqjlf8e.apps.googleusercontent.com");
        # به دلیل مسائل امنیتی این کد به صورت جدا گانه ارسال می شود و شما برای اجرای صحیح می بایست کد را در اینجا بزارید#
        
        $client->setRedirectUri("http://localhost/myphp/login_.php");
        $client->addScope("email");
        $client->addScope("profile");

        $link_b = $client->createAuthUrl();

    ?>

    <?php

    
    
    
    
    
    ?>
    <div class="div-form">
        <header>
            <i class="ti ti-user user-ic"></i>
            <h1 class="p-1">Welcome back</h1>
            <p class="p-2">sign in to your account to continue</p>
        </header>
        <form method="post" action="login_.php">
    
            <!-- email -->
            <label>email</label>
            <div class="div-email">
                <input type="text" name="input-email" placeholder="Enter your email" id="input-eamil">
                <i class="ti ti-mail email-ic"></i>
            </div>
            <?php
                if(isset($error["empty-email"])){
                    echo $error["empty-email"];
                }
            
            
            ?>
            <!-- password -->
            <label>password</label>
            <div class="div-password">
                <input type="password" name="input-password" placeholder="Enter your password" id="input-password">
                <i class="ti ti-lock password-ic"></i>
            </div>
            <?php
                if(isset($error["empty-password"])){
                    echo $error["empty-password"];
                }
            ?>
            <!-- forgat password -->
            <a href="#">forgat password?</a>
       
       
       
            <?php
                if(isset($error["password-email"])){
                    echo $error["password-email"];
                }
            ?>

            <!-- main button -->
            <div class="div-button">
                <button type="submit" class="b">sign in</button>
            </div>


            <!-- or continue with -->
            <div class="span-line">
                <div class="line-1"></div>
                <p class="p-span">or continue with</p>
                <div class="line-2"></div>
            </div>

            <!-- Google  GitHub -->
            <div class="div-button">
                <a href="<?php echo $link_b?>">
                    <button class="b-1" type="button">Google</button>
                </a>
                <button class="b-2">GitHub</button>
            </div>
            <?php
                if(isset($_GET['code'])){
                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $idToken = $token['id_token'];

                    $payload = explode('.',$idToken)[1];
                    $payload = base64_decode(strtr($payload,'-_','/+'));
                    $info = json_decode($payload,true);

                    $email_g = $info["email"];
                    $name_g = $info["name"];

                    $st = $db->prepare($comand);
                    $st->bindValue(":email",$email_g);
                    $st->execute();

                    $db_info = $st->fetch();

                    if($db_info){
                        # header("location:main.html"); #
                        # exit(); #    
                    }
                    else{
                        $comand_2 = "INSERT INTO users_data(name,email) VALUES(:name,:email)";

                        $n_st = $db->prepare($comand_2);
                        $n_st->bindValue(":name",$name_g);
                        $n_st->bindValue(":email",$email_g);
                        $n_st->execute();

                    }

                }
            ?>

            <!--Don't have an account-->
            <div class="div-f">
                <p class="p-f">Don't have an account? <a href="home.php">Sign up</a></p>
            </div>
        </form>
    </div>
</body>
</html>