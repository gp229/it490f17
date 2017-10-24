<?php
 if (mysqli_connect_errno($con)) {
        echo "Unable to get data" . mysqli_connect_error();
    }

    $sql="SELECT * FROM Stock";
    $result=mysqli_query($con, $sql);
    $i=1;
    while($row=mysqli_fetch_assoc($result))
    {
        $stock[$i] = $row['stock'];
        $amount[$i] = $row['amount'];
        $time[$i] = $row['time'];
        $i++;
    }  
    
    echo "<table>";
    echo "<tr><th>Stock</th><th>Amount</th><th>Time</th></tr>";

    
    for ($i = 1; $i <=count($stock); $i++) 
    {
        echo    
            "<tr>
            <td>$stockk[$i]</td>
            <td>$amount[$i]</td>
            <td>$time[$i]</td>
            </tr>";

    }

    echo "</table>"
 ?> 
