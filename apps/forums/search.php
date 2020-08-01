<?php session_start(); ?>

<html>
	<head>
    <link rel="stylesheet" type="text/css" href="../../style/dark.css"/>

    <div class="user_bar">
      <a href='index.php'>Back</a>
    </div>
  </head>
  
  <body>
    <h1>Results</h1>
	</body>
</html>

<?php
  if(isset($_SESSION['username']))
  {
    $username = $_SESSION['username'];
  }

	$numResults = 0;

	$searchTerm = $_POST['search_term'];

	if($searchTerm == "")
	{
		$searchTerm = "_";
	}

	$servername = "localhost";
  $server_user = "root";

  $conn = new mysqli($servername, $server_user, "", "users");

  if ($conn->connect_error) 
  {
      die("Connection failed: " . $conn->connect_error);
  }

  if(isset($_SESSION['username']))
  {
    $result = $conn->query("SELECT * FROM user_info WHERE username='$username'");
    $row = $result->fetch_assoc();

    $previousSearches = $row['previous_searches'];
    
    if(strpos($previousSearches, $searchTerm) === false)
    {
      if($previousSearches != "")
      {
        $previousSearches = $searchTerm."⎖".$previousSearches;
      }
      else
      {
        $previousSearches = $searchTerm.$previousSearches;
      }
    }
    
    $result = $conn->query("UPDATE user_info SET previous_searches='$previousSearches'");
    
    $result = $conn->query("SELECT * FROM posts WHERE title LIKE '%$searchTerm%' LIMIT 20");

    $numResults = $result->num_rows;
  }
  else
  {
    $result = $conn->query("SELECT * FROM posts WHERE title LIKE '%$searchTerm%' LIMIT 10");

    $numResults = $result->num_rows;
  }

  echo "<div class='results'><u><b style='font-size:2.5vw;'>Number of results: ".$numResults."</b></u><br/><br/>";

  echo "<form method='POST' action='show_post.php'>";

  if($numResults != 0)
  {
    while($row = $result->fetch_assoc())
    {
      echo "<input type='hidden' value='".$row["title"]."' name='title'/><input type='submit' value='".$row["title"]."'/>";
    }
  }
  
   echo "</form>";

	echo "</div>";
?>