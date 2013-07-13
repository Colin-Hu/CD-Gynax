<!DOTYPE HTML>
<html>
<body>
<?php
$code = 22805;
$ordernoup="";
if ($_POST["password"]==$code OR $_GET["update"]==1)
{
$con=mysqli_connect("localhost","gynax","","gynax");
$towers=array();
$characters=array();
$result = mysqli_query($con, "SELECT DISTINCT tower FROM orders");
while($row=mysqli_fetch_array($result))
{
array_push($towers, $row['tower']);
}
$result = mysqli_query($con, "SELECT DISTINCT charname FROM orders");
while($row=mysqli_fetch_array($result))
{
array_push($characters, $row['charname']);
}
if ($_POST["doupdate"] <> "")
{
echo "Orders have been updated<br>" . $_POST["doupdate"] . "<br>" ;
for ($i=0;$i<strlen($_POST["doupdate"]);$i++)
{
#echo "updateano" . $_POST["doupdate"][$i] . "=" . $_POST["updateano" . $_POST["doupdate"][$i]] . " updatecno" . $_POST["doupdate"][$i] . "=" . $_POST["updatecno" . $_POST["doupdate"][$i]] .  "<br>";
$result = mysqli_query($con, "SELECT acceptdate FROM orders WHERE orderno=" . $_POST["doupdate"][$i]);
$row = mysqli_fetch_array($result);
#echo $row["acceptdate"];
if ($row["acceptdate"] <> "0000-00-00 00:00:00" AND $_POST["updateano" . $_POST["doupdate"][$i]] == "")
{
$sql = "UPDATE orders SET acceptdate = 0 WHERE orderno=" . $_POST["doupdate"][$i];
}
else if ($row["acceptdate"] == "0000-00-00 00:00:00" AND $_POST["updateano" . $_POST["doupdate"][$i]] == "1")
{
$sql =  "UPDATE orders SET acceptdate = NOW() WHERE orderno=" . $_POST["doupdate"][$i];
}
else
{
$sql = "";
}
mysqli_query($con, $sql);
$result = mysqli_query($con, "SELECT completed FROM orders WHERE orderno=" . $_POST["doupdate"][$i]);
$row = mysqli_fetch_array($result);
#echo $row["completed"];
if ($row["completed"] <> "0000-00-00 00:00:00" AND $_POST["updatecno" . $_POST["doupdate"][$i]] == "")
{
$sql = "UPDATE orders SET completed = 0 WHERE orderno=" . $_POST["doupdate"][$i];
}
else if ($row["completed"] == "0000-00-00 00:00:00" AND $_POST["updatecno" . $_POST["doupdate"][$i]] == "1")
{
$sql =  "UPDATE orders SET completed = NOW() WHERE orderno=" . $_POST["doupdate"][$i];
}
else
{
$sql = "";
}
mysqli_query($con, $sql);
}
}
echo "Use the follwing filters to access records<br><br>";
echo "<form action='records.php' method='get'>";
echo "Character: ";
echo "<select type='text' name='character'>";
echo "<option value='any'>Any</option>";
for ($i=0;$i<count($characters);$i++)
{
echo "<option value='" . $characters[$i] . "'>" . $characters[$i] . '</option>';
}
echo "</select>";
echo "Tower: ";
echo "<option value='any'>Any</option>";
echo "<select type='text' name='tower'>";
echo "<option value='any'>Any</option>";
for ($i=0;$i<count($towers);$i++)
{
echo "<option value='" . $towers[$i] . "'>" . $towers[$i] . '</option>';
}
echo "</select>";
echo "Accepted: ";
echo "<select type='text' name='accept'>";
echo "<option value='='>No</option>";
echo "<option value='>'>Yes</option>";
echo "<option value=''>Either</option>";
echo "</select>";
echo "Completed: ";
echo "<select type='text' name='complete'>";
echo "<option value='='>No</option>";
echo "<option value='>'>Yes</option>";
echo "<option value=''>Either</option>";
echo "</select>";
echo "<input type='submit' value='Filter'>";
echo "<input type='hidden' name='update' value='1'>";
echo "</form>";
echo "<br><br><br>";
echo "<form action='records.php' method='post'>";
echo "<table border='1'>";
echo "<tr><td>Order No</td><td>Character</td><td>Item</td><td>Quantity</td><td>Quoted Value</td><td>Total Order</td><td>Tower</td><td>Location</td><td>Notes</td><td>Accepted Date</td><td>Completed Date</td><td>Accept</td><td>Complete</td></tr><tr></tr>";
$sqlcall = "SELECT * FROM orders";
if ($_GET["character"] <> "" AND $_GET["character"] <> "any")
{
$charselect = " AND charname='" . $_GET["character"] . "'";
}
if ($_GET["tower"] <> "" AND $_GET["tower"] <> "any")
{
$towerselect = " AND tower='" . $_GET["tower"] . "'";
}
if ($_GET["accept"] <> "")
{
$acceptselect = " AND acceptdate" . $_GET["accept"] . "0";
}
if ($_GET["complete"] <> "")
{
$completeselect = " AND completed" .  $_GET["complete"] . "0";
}
$sqlcall = $sqlcall . " WHERE TRUE" . $charselect . $towerselect . $acceptselect . $completeselect;
$result = mysqli_query($con, $sqlcall);
$runningtotal = 0;
$runningno = -1;
while($row=mysqli_fetch_array($result))
{
if ($runningno == $row["orderno"] OR $runningno==-1)
{
$runningtotal = $runningtotal + $row["qvalue"];
}
else
{
$ordernoup= $ordernoup .  $runningno;
#$runningtotal = $runningtotal + $row["qvalue"];
echo "<tr><td></td><td></td><td></td><td></td><td></td><td>" . $runningtotal . "</td><td></td><td></td><td></td><td></td><td></td><td><center><input name ='updateano" . $runningno . "' type='checkbox' value = '1'";
if ($prevdate1<>"0000-00-00 00:00:00")
{
echo "checked";
}
echo "></center></td><td><center><input name='updatecno" . $runningno . "' type='checkbox' value='1'";
if ($prevdate2<>"0000-00-00 00:00:00")
{
echo "checked";
}
echo "></center></td></tr>";
$runningtotal = $row["qvalue"];
}
$runningno = $row["orderno"];
echo "<tr>";
echo "<td>" . $row["orderno"] . "</td><td>" . $row["charname"] . "</td><td>" . $row["stuff"] . "</td><td>" . $row["quantity"] . "</td><td>" . $row["qvalue"] . "</td><td>";
echo "</td><td>" . $row["tower"] . "</td><td>" . $row["location"] . "</td><td>" . $row["notes"] . "</td><td>" . $row["acceptdate"] . "</td><td>" . $row["completed"] . "</td>";
$prevdate1 = $row["acceptdate"];
$prevdate2 = $row["completed"];
echo "<td></td><td></td></tr>";
}
echo "<tr><td></td><td></td><td></td><td></td><td></td><td>" . $runningtotal . "</td><td></td><td></td><td></td><td></td><td></td><td><center><input name='updateano" . $runningno . "'type='checkbox' value = '1'";
$ordernoup = $ordernoup . $runningno;
if ($prevdate1<>"0000-00-00 00:00:00")
{
echo "checked";
}
echo "></center></td><td><center><input name='updatecno" . $runningno . "'type='checkbox' value = '1'";
if ($prevdate2<>"0000-00-00 00:00:00")
{
echo "checked";
}
echo "></center></td></tr>";
echo "</table>";
echo "<input type='submit' value='Update Orders'>";
#echo $ordernoup;
echo "<input type='hidden' name='password' value ='" . $code . "'>";
echo "<input type='hidden' name='doupdate' value='" . $ordernoup . "'>";
echo "</form>";
}
else
{
echo "Invalid code, please try again.";
}
?>
</html>
</body>
