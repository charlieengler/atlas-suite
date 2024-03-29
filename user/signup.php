<?php
  session_start();
?>

<html>
  <head>
    <title>Sign Up</title>
    
    <link rel="stylesheet" type="text/css" href="../style/dark.css"/>

    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <div class="user_bar">
      <a href='../index.php'>Home</a>
    </div>

    <div class="user_bar">
      <a href='signin.php'>Sign In</a>
    </div>
  </head>

  <body>
    <h1>Sign Up</h1>
    
    <form action="#" method="POST" class="signup_form" autocomplete=off>
      <input type="text" class="signup" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required title="Must be a valid email"/>
      <br/>
      <input type="text" class="signup" name="username" placeholder="Username" pattern="[a-zA-Z][a-zA-Z0-9$_#@!\^,.\?|~;:]{5,20}" required title="5 to 20 characters, must be letters, numbers, and/or these characters: $ _ # @ ! \ ^ , . \ ? | ~ ; :"/>
      <br/>
      <input type="password" class="signup" name="password" placeholder="Password" pattern="^(?=.*[^a-zA-Z]).{8,40}$" required title="8 to 40 characters, must contain a number"/>
      <br/>
      <input type="password" class="signup" name="repeat_password" placeholder="Repeat Password" pattern="^(?=.*[^a-zA-Z]).{8,40}$" required title="must be the same as the password"/>
      <br/>
      <input type="submit" class="signup_submit" value="Sign Up"/>
    </form>
    
    <h3 class="new_user_prompt">
      Returning User? <br/><a href='signin.php'>Sign In</a>
    </h3>
  </body>
</html>

<?php
  if(isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repeat_password']))
  {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    
    if($password == $repeat_password)
    {
      
	    include("../../password.php");
      
      $conn = new mysqli($servername, $server_user, $serverpassword, "users");
      $conn2 = new mysqli($servername, $server_user, $serverpassword, "notifications");
      
      $password = md5($password);
      
      $resultUsername = $conn->query("SELECT * FROM user_info WHERE username='$username'");
      
      $resultEmail = $conn->query("SELECT * FROM user_info WHERE email='$email'");
      
      if($resultUsername->num_rows != 0 && $resultEmail->num_rows != 0)
      {
        echo "<div class='notification' onclick='location.href=\"signup.php\"'>Email and username are already in use.<h4>(Click to dismiss)</h4></div>";
      }
      else if($resultUsername->num_rows != 0 && $resultEmail->num_rows == 0)
      {
        echo "<div class='notification' onclick='location.href=\"signup.php\"'>Username is already in use.<h4>(Click to dismiss)</h4></div>";
      }
      else if($resultUsername->num_rows == 0 && $resultEmail->num_rows != 0)
      {
        echo "<div class='notification' onclick='location.href=\"signup.php\"'>Email is already in use.<h4>(Click to dismiss)</h4></div>";
      }
      else
      {
        $_SESSION['username'] = $username;

        $conn->query("INSERT INTO user_info (username, email, password) VALUES ('$username','$email','$password')");

        echo "<form action='../../notifications.php' method='POST' id='notification_form'>";
        echo "<input type='hidden' name='original_url' value='index.php'>";
        echo "<input type='hidden' name='username' value='".$username."'>";
        echo "<input type='hidden' name='text' value='Thank you for signing up!'>";
        echo "<input type='hidden' name='new_notification' value='true'>";
        echo "</form>";

        echo "<script>document.forms['notification_form'].submit();</script>";
      }

      $conn->close();
    }
    else
    {
      echo "<div class='notification' onclick='location.href=\"signup.php\"'>Passwords are not the same.<h4>(Click to dismiss)</h4></div>";
    }
  }
?>