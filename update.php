<!DOTYPE html>
<html>
<body
<div>
<?php
if ($_POST["updatenow"] == "mortarguy")
{
echo "Prices are now being updated. This may take a few minutes depending on how the eve-central servers are responding.";
$con=mysqli_connect("localhost","gynax","","gynax");
$stmt = mysqli_prepare($con, "UPDATE items SET doupdate=1");
mysqli_stmt_execute($stmt);
}
else
{
echo "Please do not try to force an update. Use the update button when it appears";
}
?>

</div>
</body>
</html>
