<!DOCTYPE html>
<?php
session_start();
include('getUserStocks.php');

if(!isset($_SESSION['loginUser']))
{
	header('Location: index.php');
	exit(0);
}
include('header.php');
$username = $_SESSION['loginUser'];
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
    <input type="num" id="inputNum" class="form-control" placeholder="Amount">
      <div class="form-group">
        <input type="text" id="inputSymbol" class="form-control" placeholder="Search For Stocks">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>

    <div class="buysell_output"></div>    
<!-- 
The Chart script for implementing the actual chart
-->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <br><br>
  <div id="chart_div"></div>
   <script>
	google.charts.load('current', {'packages':['line', 'corechart']});
      	google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var button = document.getElementById('change-chart');
      var chartDiv = document.getElementById('chart_div');

      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Month');
      data.addColumn('number', "Average Temperature");
      data.addColumn('number', "Average Hours of Daylight");

      data.addRows([
        [new Date(2014, 0),  -.5,  5.7],
        [new Date(2014, 1),   .4,  8.7],
        [new Date(2014, 2),   .5,   12],
        [new Date(2014, 3),  2.9, 15.3],
        [new Date(2014, 4),  6.3, 18.6],
        [new Date(2014, 5),    9, 20.9],
        [new Date(2014, 6), 10.6, 19.8],
        [new Date(2014, 7), 10.3, 16.6],
        [new Date(2014, 8),  7.4, 13.3],
        [new Date(2014, 9),  4.4,  9.9],
        [new Date(2014, 10), 1.1,  6.6],
        [new Date(2014, 11), -.2,  4.5]
      ]);

      var materialOptions = {
        chart: {
          title: 'Average Temperatures and Daylight in Iceland Throughout the Year'
        },
        width: 900,
        height: 500,
        series: {
          // Gives each series an axis name that matches the Y-axis below.
          0: {axis: 'Temps'},
          1: {axis: 'Daylight'}
        },
        axes: {
          // Adds labels to each axis; they don't have to match the axis names.
          y: {
            Temps: {label: 'Temps (Celsius)'},
            Daylight: {label: 'Daylight'}
          }
        }
      };

      var classicOptions = {
        title: 'Average Temperatures and Daylight in Iceland Throughout the Year',
        width: 900,
        height: 500,
        // Gives each series an axis that matches the vAxes number below.
        series: {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },
        vAxes: {
          // Adds titles to each axis.
          0: {title: 'Temps (Celsius)'},
          1: {title: 'Daylight'}
        },
        hAxis: {
          ticks: [new Date(2014, 0), new Date(2014, 1), new Date(2014, 2), new Date(2014, 3),
                  new Date(2014, 4),  new Date(2014, 5), new Date(2014, 6), new Date(2014, 7),
                  new Date(2014, 8), new Date(2014, 9), new Date(2014, 10), new Date(2014, 11)
                 ]
        },
        vAxis: {
          viewWindow: {
            max: 30
          }
        }
      };

      function drawMaterialChart() {
        var materialChart = new google.charts.Line(chartDiv);
        materialChart.draw(data, materialOptions);
        button.innerText = 'Change to Classic';
        button.onclick = drawClassicChart;
      }

      function drawClassicChart() {
        var classicChart = new google.visualization.LineChart(chartDiv);
        classicChart.draw(data, classicOptions);
        button.innerText = 'Change to Material';
        button.onclick = drawMaterialChart;
      }

      drawMaterialChart();

    }
	</script>

<button id="change-chart">Change to Classic</button>


</div>

<script = type="text/javascript">
//This is the code that stores the usernames from the session
<?php echo "var user = '" .$username. "'"; ?>

function HandleResponse(response)
{
	var text = JSON.parse(response);
	document.getElementById("output").innerHTML = "response: "+text+"<p>";
	if(text === "SearchSuccessful")
	{
		location.href = "main.php";
	}
  else if (text === "BuySuccessful")
  {
    document.getElementById("buysell_output").innerHTML = "<p>Purchase Complete";
  }
  else if (text === "SellSuccessful")
  {
    document.getElementById("buysell_output").innerHTML = "<p>Sale Complete";
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
  document.getElementById("buysell_output").innerHTML = "Buying<p>stock: " + symbol + "<p>amount: " + num + "<p>";
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
  request.send("type=buy&symbol="+text+"&num="+num+"&user="+user);
}

function submitSell()
{
  var symbol = document.getElementById("inputSymbol").value;
  var num = document.getElementById("inputNum").value;
  document.getElementById("buysell_output").innerHTML = "Selling<p>stock: " + symbol + "<p>amount: " + num + "<p>";
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
  request.send("type=sell&symbol="+text+"&num="+num+"&user="+user);
}


</script>

</body>
</html>
