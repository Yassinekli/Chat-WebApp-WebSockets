<?php
    if(isset($_POST['submit']) && filter_has_var(INPUT_POST, "submit")){
        if(isset($_POST['user-name']) && !empty($_POST['user-name']))
        {
            session_start();
            $_SESSION['username'] = htmlspecialchars($_POST['user-name']);
        }
        else{
            header('Location: login.php?error=1');
            die();
        }
    }
    else
    {   
        session_start();
        if(!isset($_SESSION['username'])){
            header('Location: login.php?error=1');
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Chat</title>
    <script>
        var connection = new WebSocket('ws://localhost:3000');
        connection.onopen = function(e) {
            console.log("Connection established!");
        };

        connection.onmessage = function(e) {
            // Get data from WebSocket
            var data = JSON.parse(e.data);
            
            // Get and create message elements
            var conv = document.getElementById('conversations');
            var newMsg = document.createElement('div');
            var username = document.createElement('div');
            var msg = document.createElement('div');
            
            // Test if the sent message was from current user
            if(data['username'] === '<?php echo $_SESSION['username']?>')
            {
                // Add appropriate classes
                newMsg.classList.add(new Array('current-user'));
                msg.classList.add(new Array('message'));
                // Set values
                msg.innerHTML = data['msg'];
                // Appeand childs
                newMsg.appendChild(msg);
                conv.appendChild(newMsg);
            }else{
                // Add appropriate classes
                newMsg.classList.add(new Array('other-users'));
                username.classList.add(new Array('username'));
                msg.classList.add(new Array('message'));
                // Set values
                username.innerHTML = data['username'];
                msg.innerHTML = data['msg'];
                // Appeand childs
                newMsg.appendChild(username);
                newMsg.appendChild(msg);
                conv.appendChild(newMsg);
            }
            console.log(data);
        };

        connection.onclose = function(e) {
            console.log(e);
        };
    </script>
</head>
<body>
    <div class="username-ctr">
        <div class="container">
            <h2 class="username"><?php echo $_SESSION['username'];?></h2>
            <a href="login.php?logout=1" class="disconnect">Disconnect</a>
        </div>
    </div>
    <div class="container">
        <div class="chat-comp">
            <div id="conversations" class="messages">
                
            </div>
            <div class="text-area">
                <input id="msg" class="text-msg" type="text" name="sent_msg">
                <button id="send" class="send">SEND</button>
            </div>
        </div>
    </div>

    <script>
        var msg = document.getElementById('msg');
        document.getElementById('send').addEventListener('click', (e)=>{
            if(msg.value !== ""){
                connection.send("{\"username\":\"<?php echo $_SESSION['username'];?>\", \"msg\":\"" + msg.value + "\"}");
                msg.value = "";
            }
        });
        document.getElementById('msg').addEventListener('keyup', (e)=>{
            if(e.key === "Enter"){
                connection.send("{\"username\":\"<?php echo $_SESSION['username'];?>\", \"msg\":\"" + msg.value + "\"}");
                msg.value = "";
            }
        });
    </script>
</body>
</html>