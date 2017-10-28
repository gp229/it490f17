<!DOCTYPE html>
<?php
//include('getUserStocks.php');
session_start();
include('header.php');
require_once('path.inc');
require_once('requestClient.php.inc');
require_once('loggerClient.php.inc');
try{

	$request['type'] = "list";
	$myClient = new rabbitClient("testRabbitMQ.ini","stockServer");
	$response = $myClient->make_request($request);
}
catch(Error $e)
{
        $mylogger = new loggerClient();
        $mylogger->sendLog("userauth.log",2,"Error with user authentication: ".$e." in ".__FILE__." on line ".__LINE__);
        $response = "Sorry, something went wrong.";
}




?>
<link rel="stylesheet" href="css/main.css">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="js/dropdown.js"></script>
<div class="container">
  <div class="jumbotron">
    <h1>Welcome!</h1>      
    <p>This is Stocks R Us, where you can fulfill your stock market needs</p>
  </div>

  <form class="navbar-form navbar" style="margin-top: -20px; margin-left: -10px; width: 100%;">
    <button type="button" class="btn btn-default" onclick="submitBuy()">Buy</button>
    <button type="button" class="btn btn-default" onclick="submitSell()">Sell</button>
    <input type="quantity" id="inputNum" class="form-control" placeholder="Amount">
	<div class="dropdown">            
<a class="btn btn-default btn-select btn-select-light">
                <input type="hidden" class="btn-select-input" id="dropdownStock" name="" value="" />
                <span class="btn-select-value">Select an Item</span>
                <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
                <ul>
			<?php
			foreach($response as $data)
			{
				echo '<li>'.$data['symbol'].'</li>';
			} 
			?>
		</ul>
            </a>
        </div>   
	</form>   
	<h1>Stock timestamp: <?php echo $response['0']['timestamp']; ?> </h1> 
    <div id="table_div"></div>

    <div id="output">status<p></div>    
</div>    
		<script src="js/ie10-viewport-bug-workaround.js"></script>

<script>

//This is the code that stores the usernames from the session
<?php echo "var user = '" .$_SESSION['loginUser']. "';"; ?>

google.charts.load('current', {'packages':['table']});
      google.charts.setOnLoadCallback(drawTable);

      function drawTable() {
        var data = new google.visualization.DataTable();	
        data.addColumn('string', 'symbol');
        data.addColumn('number', 'open');
        data.addColumn('number', 'close');
        data.addColumn('number', 'high');
        data.addColumn('number', 'low');
        data.addColumn('number', 'volume');
        <?php
	
	foreach($response as $key => $value)
	{	
		echo 'data.addRow(["'.$value["symbol"].'",'.$value["open"].','.$value["close"].','.$value["high"].','.$value["low"].','.$value["volume"].']);';
	}
	?>
	var formatter = new google.visualization.NumberFormat({fractionDigits: 4});
	formatter.format(data,1);
	formatter.format(data,2);
	formatter.format(data,3);
	formatter.format(data,4);
        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '100%', legend:'left'});
      }





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
  var num = document.getElementById("inputNum").value;
  var symbol = document.getElementById("dropdownStock").value;
  document.getElementById("output").innerHTML = "Buying stock: " + symbol + "<p>amount: " + num + "<p>";
 // sendBuyRequest(symbol,num);
  return 0;
}
function sendBuyRequest(symbol,num)
{
  var request = new XMLHttpRequest();
  request.open("POST","stock.php",true);
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
  //var symbol = document.getElementById("inputSymbol").value;
  var num = document.getElementById("inputNum").value;
  //document.getElementById("output").innerHTML = "Selling<p>stock: " + symbol + "<p>amount: " + num + "<p>";
  //sendSellRequest(symbol,num);
  return 0;
}
function sendSellRequest(symbol,num)
{
  var request = new XMLHttpRequest();
  request.open("POST","stock.php",true);
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
