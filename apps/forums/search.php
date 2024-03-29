<?php session_start(); ?>

<html>
	<head>
    	<link rel="stylesheet" type="text/css" href="../../style/dark.css"/>

    	<meta name='viewport' content='width=device-width, initial-scale=1'>

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

	include("../../../password.php");
  
	$conn = new mysqli($servername, $server_user, $serverpassword, "forums");
  	$conn2 = new mysqli($servername, $server_user, $serverpassword, "users");

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	if(isset($_POST['search_term']))
	{
		$result = $conn->query("SELECT * FROM posts WHERE title LIKE '%$searchTerm%' AND visibility='public' LIMIT 20");

		$numResults = $result ? $result->num_rows : 0;
	}

	if(isset($_SESSION['username']))
	{
		$conn2->query("INSERT INTO browsing_history(username, search_term) VALUES ('$username', '$searchTerm')");
	}

	echo "<div class='results'><h3><u><b>Number of results: ".$numResults."</b></u></h3><br/><br/>";

	echo "<div class='browse_results'>";

	if($numResults != 0)
	{
		while($row = $result->fetch_assoc())
		{
			echo "<form method='POST' action='show_post.php'><input type='hidden' value='".$row["title"]."' name='title'/><input type='submit' value='".$row["title"]."'/></form><br/>";
		}
	}

	echo "</div>";

	echo "</div>";
?>