<!DOTYPE HTML>
<html>
<body>

<?php
$con=mysqli_connect("localhost","gynax","","gynax");
if (mysqli_connect_errno($con))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{
echo "Successfully connected to MySQL <br><br>";
}
$items=array();
$result = mysqli_query($con, "SELECT * FROM piprices");
echo '<div>';
echo '<div style="width:500px;float:left;">';
echo 'Prices are only from Amarr <br> Gynax Price is 90% Buy <br>';
echo '<table border="1">';
echo '<tr><td>Item ID</td><td>Item Name</td><td>Sell</td><td>Buy</td><td>Gynax Price</td></tr>';
while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo '<td>' . $row['typeID'] . '</td><td>' . $row['typeNAME'] . '</td><td>' . $row['min'] . '</td><td>' . $row['max'] . '</td><td>' . $row['max']*0.9 . '</td>';
echo "</tr>";
array_push($items, $row['typeNAME']);
$lastdate = $row['lastupdate'];
}
echo "</table>";
echo "Last Updated: " . $lastdate . " EST";
echo "<br>";
$timesincelast = (time()-strtotime($lastdate))/60;
echo "Last Updated: " . (int)$timesincelast . " minutes ago";
if ($timesincelast > 5)
{
echo '<form action="update.php" method="post">';
echo '<input type="submit" value="Update Now">';
echo '<input type="hidden" name="updatenow" value="mortarguy">';
echo '</form>';
}
else
{
echo '<br>Prices recently updated please wait a few minutes.';
}
echo "</div>";
echo "<div style='float:left;'>";
echo "<form action='records.php' method='post'>";
echo "Insert Code to Access Orders: <input type='password' name='password'>";
echo "<input type='submit'>";
echo "</form>";
echo "Input items that you wish to sell and press submit to generate a quote.<br>";
echo "<form action='quote.php' method='get'>";
echo "<select type='text' name = 'item1'>";
sort($items);
for ($i=0;$i<count($items);$i++)
{
echo "<option value='" . $items[$i] . "'>" . $items[$i] . '</option>';
}
echo "</select>";
echo "Quantity: <input type='text' name ='q1'>";
echo "<br>";
echo "<select name = 'item2'>";
for ($i=0;$i<count($items);$i++)
{
echo "<option value='" . $items[$i] . "'>" . $items[$i] . '</option>';
}
echo "</select>"; 
echo "Quantity: <input type='text' name ='q2'>"; 
echo "<br>";
echo "<select type='text' name = 'item3'>";
for ($i=0;$i<count($items);$i++)
{
echo "<option value='" . $items[$i] . "'>" . $items[$i] . '</option>';
}
echo "</select>";
echo "Quantity: <input type='text' name ='q3'>";
echo "<br>";
echo "<input type='submit'>";
echo "</form>";
echo "</div>";
echo "</div>";
mysqli_close($con);

?>

</body>
</html>
