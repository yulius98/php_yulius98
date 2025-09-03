<?php

$jml = $_GET['jml'] ;
echo "<table border=1 >\n";
for ($a = $jml; $a > 0; $a--) {
  $total = $a * ($a + 1) / 2;
  echo "<tr><td colspan='$jml' font-weight:bold; text-align:left;'>TOTAL: $total</td></tr>\n<tr>";
  
  for ($b = $a; $b > 0; $b--) {
    echo "<td style='width:50px; text-align:center;'>$b</td>";
  }
  
  echo "</tr>\n";
}

echo "</table>";

?>