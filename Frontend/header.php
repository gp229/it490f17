<?php

session_start();

if(!isset($_SESSION['loginUser']))
{
	header('Location: index.php');
	exit(0);
}
?>
<html>

<head>
<title>Stocks-R-Us</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Stocks-R-Us</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="portfolio.php">Account</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#" id="username"><?php echo $_SESSION['loginUser'];?></a></li>
       <li><a href="#" id="user_balance" style="color: #5cb85c;"><span class="glyphicon glyphicon-usd"><?php echo $_SESSION['balance'];?></span></a></li>
      <li><a href="logout.php" id="username">Logout</a></li>
    </ul>
    
  </div>
</nav>




    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
