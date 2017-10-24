<?php
include("database.php");

$query = "select * from stocks";
$result = conn($query);
if (($result)||(mysql_errno == 0))
{
  echo "<table width='100%'><tr>";
  if (mysql_num_rows($result)>0)
  {
          $i = 0;
          while ($i < mysql_num_fields($result))
          {
       echo "<th>". mysql_field_name($result, $i) . "</th>";
       $i++;
    }
    echo "</tr>";

   
    while ($rows = mysql_fetch_array($result,MYSQL_ASSOC))
    {
      echo "<tr>";
      foreach ($rows as $data)
      {
        echo "<td align='center'>". $data . "</td>";
      }
    }
  }else{
    echo "<tr><td colspan='" . ($i+1) . "'>No Results found!</td></tr>";
  }
  echo "</table>";
}else{
  echo "Not able to get data". mysql_error();
}
?>

