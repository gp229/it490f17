<?php
//include('getUserStocks.php');

include('header.php');
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
      <li><a href="#" id="username"></a></li>
       <li><a href="#" id="user_balance" style="color: #5cb85c;"><span class="glyphicon glyphicon-usd"></span></a></li>
    </ul>
    
  </div>
</nav>


<div class="container">
  <div class="jumbotron">
    <h1>Welcome!</h1>      
    <p>This is Stocks R Us, where you can fufill you stock market needs</p>
  </div>

  <form class="navbar-form navbar" style="margin-top: -20px; margin-left: -10px; width: 100%;">
    <button type="buy" class="btn btn-default">Buy</button>
    <button type="sell" class="btn btn-default">Sell</button>
    <input type="num" class="form-control" placeholder="Amount">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Search For Stocks">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>    

<script>
function HandleSearchResponse(response)
{
	var text = JSON.parse(response);
	document.getElementById("output").innerHTML = "response: "+text+"<p>";
	if(text === "SearchSuccessful")
	{
		location.href = "main.php";
	}
}
function sendSearchRequest(text)
{
	var request = new XMLHttpRequest();
	request.open("POST","search.php",true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange= function ()
	{
		if ((this.readyState == 4)&&(this.status == 200))
		{
			HandleLoginResponse(this.responseText);
		}		
	}
	request.send("type=search&symbol="+text);
}
</script>

</body>
</html>
