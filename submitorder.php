<!DOTYPE HTML>
<html>
<body>

<?php
echo "Your order has been submitted for processing. Please do not refresh this page to avoid double submitting.<br>";
#echo $_POST["item1"] . " " . $_POST["item2"] . " " . $_POST["item3"];
$con=mysqli_connect("localhost","gynax","","gynax");
$stmt = mysqli_prepare($con, "INSERT INTO orders VALUES (?,now(),?,?,?,?,?,'',0,?,?)");
mysqli_stmt_bind_param($stmt, 'ssssssss', $orderno, $stuff, $quantity, $qvalue, $tower, $location, $notes, $charname);
$result=mysqli_query($con,"SELECT max(orderno) AS max FROM orders");
if ($row = mysqli_fetch_array($result))
{
$orderno = $row["max"]+1;
}
$stuff = $_POST["item1"];
$quantity = $_POST["q1"];
$qvalue = $_POST["v1"];
$tower = $_POST["tower"];
$location = $_POST["location"];
$notes = $_POST["notes"];
$charname = $_POST["charname"];
#echo "<br>SQL Statement: " . $orderno . " " . $orderdate . " " . $stuff . " " . $quantity . " " . $qvalue . " " . $tower . " " . $location . " " . $acceptdate . " " . $completed . " " . $notes . " " . $charname;
if ($qvalue>0)
{
if (mysqli_stmt_execute($stmt))
{
echo "<br>Successful Injection";
}
else
{
echo "<br>Failure to Inject: " . mysqli_error($con);
}
}
$stuff = $_POST["item2"];
$quantity = $_POST["q2"];
$qvalue = $_POST["v2"];
if ($qvalue>0)
{
if (mysqli_stmt_execute($stmt))
{
echo "<br>Successful Injection";
}
else
{
echo "<br>Failure to Inject: " . mysqli_error($con);
}
}
$stuff = $_POST["item3"];
$quantity = $_POST["q3"];
$qvalue = $_POST["v3"];
if ($qvalue>0)
{
if (mysqli_stmt_execute($stmt))
{
echo "<br>Successful Injection";
}
else
{
echo "<br>Failure to Inject: " . mysqli_error($con);
}
}
?>
</html>
</body>
