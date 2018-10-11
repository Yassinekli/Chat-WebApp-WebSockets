<?php
    $msgError = "";
    $displayMode = "";
    if(isset($_GET['error']) && $_GET['error'] == "1"){
        $msgError = "Please let your friends know you by typing your username";
        $displayMode = "display-mode";
    }elseif(isset($_GET['logout']) && $_GET['logout'] == "1"){
        session_start();
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="username-ctr">
        <div class="container">
            <h2 class="username">Login</h2>
        </div>
    </div>
    <div class="error-ctr">
        <div class="container">
            <span class="error-msg <?php echo $displayMode;?>">
                <?php 
                    if($msgError !== "")
                        echo $msgError;
                ?>
            </span>
        </div>
    </div>
    <div class="container">
        <form action="chat.php" method="post">
            <label for="user-name">Username</label>
            <input class="username-txt" type="text" name="user-name">
            <input type="submit" name="submit" value="Connect">
        </form>
    </div>
</body>
</html>