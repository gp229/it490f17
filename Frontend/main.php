<!DOCTYPE html>
<?php
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
      <li><a href="#">Account</a></li>
    </ul>
    <form class="navbar-form navbar-right">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Search For Stocks">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
</nav>


<div class="container">
  <div class="jumbotron">
    <h1>Welcome "Insert User Name Here"</h1>      
    <p>This is Stocks R Us, where you can fufill you stock market needs</p>
  </div>
  <p style="text-align: right;">You currently have $ "Insert user money here"</p>      
  <p>Happy Trading</p>      
</div>

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