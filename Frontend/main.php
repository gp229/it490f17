<!DOCTYPE html>
<?php
//include('getUserStocks.php');
session_start();

include('header.php');
?>
<div class="container">
  <div class="jumbotron">
    <h1>Welcome!</h1>      
    <p>This is Stocks R Us, where you can fulfill your stock market needs</p>
  </div>

  <form class="navbar-form navbar" style="margin-top: -20px; margin-left: -10px; width: 100%;">
    <button type="button" class="btn btn-default" onclick="submitBuy()">Buy</button>
    <button type="button" class="btn btn-default" onclick="submitSell()">Sell</button>
    <input type="quantity" id="inputNum" class="form-control" placeholder="Amount">

<div class="btn-group" role="group">
  <button type="button" data-toggle="dropdown" value="1" class="btn btn-default btn-sm dropdown-toggle">
    Option 1 <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#" data-value="1">Option 1</a></li>
    <li><a href="#" data-value="2">Option 2</a></li>
    <li><a href="#" data-value="3">Option 3</a></li>
  </ul>
</div> 
   
<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownStock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Stock<span class="caret"></span></button>
  <ul class="dropdown-menu" aria-labelledby="dropdownStock">
    <?php echo '<li value="Action">Action</li>";' ?>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div> 
	</form>    

    <div id="output">status<p></div>    
</div>    
<script src="js/ie10-viewport-bug-workaround.js"></script>

<script>

function dropdownToggle() {
    // select the main dropdown button element
    var dropdown = $(this).parent().parent().prev();

    // change the CONTENT of the button based on the content of selected option
    dropdown.html($(this).html() + '&nbsp;</i><span class="caret"></span>');

    // change the VALUE of the button based on the data-value property of selected option
    dropdown.val($(this).prop('data-value'));
}
$(document).ready(function(){
    $('.dropdown-menu a').on('click', dropdownToggle);
}

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
  var stuff = document.getElementbyID("dropdownStock").value;
  document.getElementById("output").innerHTML = "Buying stock: " + symbol + "<p>amount: " + num + "<p>" + stuff;
  //sendBuyRequest(symbol,num);
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
