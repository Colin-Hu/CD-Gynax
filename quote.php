<!DOTYPE html>
<html>
<body onload="CCPEVE.requestTrust('http://y790.org')">

<?php
echo '<div style="width:400px;float:left;">';
$con=mysqli_connect("localhost","webbot","","planetary_interaction");
$total = 0;
echo "<table border = '1'>";
echo "<tr>";
echo "<td>Item Name</td><td>Quantity</td><td>Price</td><td>Value</td>";
echo "</tr>";
echo "<tr>";
if ($_GET["q1"] > 0)
{
$result = mysqli_query($con, "SELECT * FROM piprices WHERE typeNAME='" . $_GET["item1"] . "'");
$row = mysqli_fetch_array($result);
$value1 = $_GET["q1"]*$row['max']*0.9;
echo '<td>' . $_GET["item1"] . '</td><td>' . $_GET["q1"] . '</td><td>' . $row['max']*0.9 . '</td><td>' . $value1 . "</td>";
$total = $total + $value1;
}
echo "</tr>";
echo "<tr>";
if ($_GET["q2"] > 0)
{
$result = mysqli_query($con, "SELECT * FROM piprices WHERE typeNAME='" . $_GET["item2"] . "'");
$row = mysqli_fetch_array($result);
$value2 = $_GET["q2"]*$row['max']*0.9;
echo '<td>' . $_GET["item2"] . '</td><td>' . $_GET["q2"] . '</td><td>' . $row['max']*0.9 . '</td><td>' . $value2 . "</td>";
$total = $total + $value2;
}
echo "</tr>";
echo "<tr>";
if ($_GET["q3"] > 0)
{
$result = mysqli_query($con, "SELECT * FROM piprices WHERE typeNAME='" . $_GET["item3"] . "'");
$row = mysqli_fetch_array($result);
$value3 = $_GET["q3"]*$row['max']*0.9;
echo '<td>' . $_GET["item3"] . '</td><td>' . $_GET["q3"] . '</td><td>' . $row['max']*0.9 . '</td><td>' . $value3 . "</td>";
$total = $total + $value3;
}
echo "</tr>";
echo "<tr><td>Total</td><td></td><td></td><td>" . $total . "</td></tr>";
echo "</table>";
echo '</div>';
echo '<div style="float:left;">';
$towers =array();
$result=mysqli_query($con,"SELECT * FROM towers");
while($row = mysqli_fetch_array($result))
{
array_push($towers, $row["name"]);
#echo $row["name"];
#echo '<br>';
}
sort($towers);
if (1==1)
{
echo '<table border="0">';
echo '<form action="submitorder.php" method="post">';
echo '<tr>';
echo '<td><div title="Please let in game browser autofill this field, or type name exactly as it appears in game">Character: </div></td><td><input type="text" name="charname" value="' . $_SERVER['HTTP_EVE_CHARNAME'] . '"></td>';
echo '</tr><tr>';
echo '<td>Tower:</td><td><select type="text" name="tower">';
for ($i=0;$i<count($towers);$i++)
{
#echo $towers[$i] . '<br>';
echo '<option value="' . $towers[$i] . '">' . $towers[$i] . '</option>';
}
echo '</select></td>';
echo '<tr>';
echo '<td><div title="CHA-Other Tab, EFC-(LC-A), etc">Location:</div></td><td><input type="text" name="location"></td>';
echo '</tr><tr>';
echo '<td>Special Notes:</td><td><input type="text" name="notes"></td>';
echo '</tr><tr><td></td>';
echo '<td><input type="submit" value="Confirm Quote"></td>';
if ($_GET["q1"]>0)
{
echo '<input type="hidden" name="item1" value="' . $_GET["item1"] . '">';
echo '<input type="hidden" name="v1" value="' . $value1 . '">';
echo '<input type="hidden" name="q1" value="' . $_GET["q1"] . '">';
}
if ($_GET["q2"]>0)
{
echo '<input type="hidden" name="item2" value="' . $_GET["item2"] . '">';
echo '<input type="hidden" name="q2" value="' . $_GET["q2"] . '">';
echo '<input type="hidden" name="v2" value="' . $value2 . '">';
}
if ($_GET["q3"]>0)
{
echo '<input type="hidden" name="item3" value="' . $_GET["item3"] . '">';
echo '<input type="hidden" name="q3" value="' . $_GET["q3"] . '">';
echo '<input type="hidden" name="v3" value="' . $value3 . '">';
}
echo '</form></tr>';
echo '</table>';
}

echo '</div>';
?>

</body>
</html>
