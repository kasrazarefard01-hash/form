<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.46.0/tabler-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
</head>
<body>
    <?php 
        $error = []; 
        
        # conect to deta bace #
        $db = new PDO("mysql:host=localhost;dbname=user;charset=utf8mb4","root","");

        # function for check the password #
        function finder($text){
            $pu = ['!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];
            $c = 0;
            for($i=0 ; $i != strlen($text) ; $i++){
                foreach($pu as $p){
                    if($text[$i] == $p){
                    $c += 1;
                    }
                }
            } 
            if($c == 0){
                return True;
            }
            else{
                return False;
            }
        }

        # get  from form #
        if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $name = $_POST["input-name"];
                    $email = $_POST["input-email"];
                    $password = $_POST["input-password"];
                    $re_password = $_POST["input-re-password"];

                    # name #
                    if(empty(trim($name))){
                        $error["name-empty"] = '<span class="span">Please enter your name</span>';
                    }

                        $name = htmlspecialchars($name);
                        $length = strlen($name);


                        if($length >= 40){
                            $error["name-length"] = '<span class="span">Please enter your real name</span>';
                        }

                    # email #
                    if(empty(trim($email))){
                            $error["email-empty"] = '<span class="span">Please enter your email</span>';
                        }
                        
                        elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                            $error["not-valid-email"] = '<span class="span">Please enter valid email</span>';
                        }
                        
                        $email = strtolower($email);

                    # password #
                     $length_password = strlen($password);

                        if(empty(trim($password))){
                            $error["empty-password"] = '<span class="span">Please enter the password</span>';
                        }

                        elseif(finder($password) && $length_password <= 8){
                            $error["email-password"] = '<span class="span">Please use punctuation in your paasword and make your password longer</span>';
                        }
            
                        elseif((finder($password))){
                            $error["not-punctuation"] = '<span class="span">Please use punctuation in your paasword</span>';
                        }

                        elseif($length_password <= 8){
                            $error["short-password"] = '<span class="span">Please make your password longer</span>';
                        }
        
                        #re-password#
                        if(empty(trim($re_password))){
                            $error["empty-re-password"] = '<span class="span">please enter your password agin</span>';
                        }

                        elseif($re_password != $password){
                            $error["wrong-password"] = '<span class="span">password is wrong</span>';
                        }

                        #check box#
                        if(!isset($_POST["checkbox"])){
                            $error["checkbox"] = '<span class="span">Please accept the Terms of Service to continue</span>';
                        }
                        # find ip #
                        $ip = $_SERVER["REMOTE_ADDR"];

        
                        if(empty($error)){
                            $password = password_hash($password,PASSWORD_DEFAULT);
                            
                            $sql = "INSERT INTO users_data (name ,email ,password,ip) VALUES(:name,:email,:password,:ip)";
                            $st = $db->prepare($sql);
                            $st->bindValue(":name",$name);
                            $st->bindValue(":email",$email);
                            $st->bindValue(":password",$password);
                            $st->bindValue(":ip",$ip);
                            $st->execute();

                            
                            # header("location:main.html"); #
                            # exit(); #
                        }
        }
?>
   


<div id="div-1">
        <figure>
            <i class="ti ti-user my-icon"></i>
        </figure>
        <h1 class="text">Create account</h1>
        <p class="sub">Sign up to get started</p>
        <form method="post" action="home.php">
            <div class="div-form">
                <label>Full name</label>
                <div class="div-name">
                    <i class="ti ti-user my-icon-2"></i>
                    <input type="text" name="input-name" class="input" placeholder="Enter your name">
                </div>

                <?php
                    if(isset($error["name-empty"])){
                        echo $error["name-empty"];
                    }
                    if(isset($error["name-length"])){
                        echo $error["name-length"];
                    }
                ?>

                <label>Email address</label>
                <div class="div-email">
                    <i class="ti ti-mail my-icon-3"></i>
                    <input type="text" name
                    ="input-email" class="input" placeholder="Enter your email">
                </div>
                <?php      
                    if(isset($error["email-empty"])){
                        echo $error["email-empty"];
                    }
                    if(isset($error["not-valid-email"])){
                        echo $error["not-valid-email"];
                    }
                ?>
                <label>Password</label>
                <div class="div-password">
                    <i class="ti ti-lock my-icon-4"></i>
                    <input type="password" name="input-password" class="input" placeholder="Create Password">
                </div>
                <?php
                    if(isset($error["empty-password"])){
                        echo $error["empty-password"];
                    }                        
                    if(isset($error["email-password"])){
                        echo $error["email-password"];
                    }                       
                    if(isset($error["not-punctuation"])){
                        echo $error["not-punctuation"];
                    }
                    if(isset($error["short-password"])){
                        echo  $error["short-password"];
                    }
                ?>
                <label>Confirm password</label>
                <div class="div-repassword">
                    <i class="ti ti-lock my-icon-5"></i>
                    <input type="password" name="input-re-password" class="input" placeholder="Re-enter your Password">
                </div>
                <?php 
                    if(isset( $error["empty-re-password"])){
                        echo  $error["empty-re-password"];
                    }
                    if(isset($error["wrong-password"])){
                        echo  $error["wrong-password"];
                    }
                ?>
                <div class="div-text">
                    <input type="checkbox" name="checkbox">
                    <p class="p">I agree to the <a href="#" class="a">Terms of Service</a></p>
                </div>
                
                <?php
                    if(isset($error["checkbox"])){
                        echo $error["checkbox"];
                    }            
                ?>
                <div>
                    <button type="submit" class="button">Create account</button>
                </div> 
                <div>
            </div>
        </form>
    </div>
</body>
</html>