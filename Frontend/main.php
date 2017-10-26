<!DOCTYPE html>
<?php
//include('getUserStocks.php');
session_start();

include('header.php');
?>
<div class="container">
  <div class="jumbotron">
    <h1>Welcome!</h1>      
    <p>This is Stocks R Us, where you can fufill you stock market needs</p>
  </div>

  <form class="navbar-form navbar" style="margin-top: -20px; margin-left: -10px; width: 100%;">
    <button type="button" class="btn btn-default" onclick="submitBuy()">Buy</button>
    <button type="button" class="btn btn-default" onclick="submitSell()">Sell</button>
    <input type="quantity" id="inputNum" class="form-control" placeholder="Amount">
      <div class="form-group">
        <input type="symbol" id="inputSymbol" class="form-control" placeholder="Search For Stocks">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>    

    <div id="output">status<p></div>    
</div>    
<script src="js/ie10-viewport-bug-workaround.js"></script>

<script>
//This is the code that stores the usernames from the session
<?php echo "var user = '" .$_SESSION['loginUser']. "';"; ?>
function HandleResponse(response)
{
	var text = JSON.parse(response);
	document.getElementById("output").innerHTML = text;
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
			HandleResponse(this.responseText);
		}		
	}
	request.send("type=search&symbol="+text);
}

function submitBuy()
{
  var symbol = document.getElementById("inputSymbol").value;
  var num = document.getElementById("inputNum").value;
  document.getElementById("output").innerHTML = "Buying stock: " + symbol + "<p>amount: " + num + "<p>";
  sendBuyRequest(symbol,num);
  return 0;
}
function sendBuyRequest(symbol,num)
{
  var request = new XMLHttpRequest();
  request.open("POST","buysell.php",true);
  request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  request.onreadystatechange= function ()
  {
    if ((this.readyState == 4)&&(this.status == 200))
    {
      HandleResponse(this.responseText);
    }   
  }
  request.send("type=buy&symbol="+symbol+"&quantity="+num+"&username="+user);
}

function submitSell()
{
  var symbol = document.getElementById("inputSymbol").value;
  var num = document.getElementById("inputNum").value;
  document.getElementById("output").innerHTML = "Selling<p>stock: " + symbol + "<p>amount: " + num + "<p>";
  sendSellRequest(symbol,num);
  return 0;
}
function sendSellRequest(symbol,num)
{
  var request = new XMLHttpRequest();
  request.open("POST","buysell.php",true);
  request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  request.onreadystatechange= function ()
  {
    if ((this.readyState == 4)&&(this.status == 200))
    {
      HandleResponse(this.responseText);
    }   
  }
  request.send("type=sell&symbol="+symbol+"&quantity="+num+"&username="+user);
}


</script>

</body>
</html>
