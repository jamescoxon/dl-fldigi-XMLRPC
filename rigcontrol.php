<html>
<body>
<form action="rigcontrol.php" method="post">
<p>New freq: <input type="input" name="newf" value ="" />
<input type="submit" value = "Set"/></p>

<?php
	if (isset($_POST['newf']))
	{
		$frequency = $_POST['newf'];
		print "Change Freq: $frequency";
		$command = "/usr/bin/rigctl -r /dev/ttyUSB0 -m 120 -s 4800 F " . $frequency;
		print $command;
		echo exec($command);
        }

?>
<?php
        $freq = exec('/usr/bin/rigctl -r /dev/ttyUSB0 -m 120 -s 4800 f');
        print "<p>Freq: $freq</p>";
?>

</form>
</body>
</html>
