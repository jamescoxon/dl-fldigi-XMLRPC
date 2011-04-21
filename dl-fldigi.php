<html>
<head>
<title>DL-FLDIGI XML-RPC PHP Demo</title>
<meta http-equiv="refresh" content="5">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
</head>
<body>
<h1>DL-FLDIGI</h1>


<?php
include("xmlrpc.inc");

function server_result($result) {
	// Process the response.
	if (!$result) {
		print "<p>Could not connect to HTTP server.</p>";
	} elseif ($result->faultCode()) {
		print "<p>XML-RPC Fault #" . $result->faultCode() . ": " .
		$result->faultString();
		print "</p>";
	} else {
		$decoded_response = php_xmlrpc_decode($result->value());
		//print "<p>$decoded_response</p>";
		return $decoded_response;
		
	}
}
		

$server = new xmlrpc_client("/RPC2", "localhost" , 7236);

// Send a message to the server.

//$message = new xmlrpcmsg('fldigi.name');
$message = new xmlrpcmsg('modem.get_carrier');
$result = $server->send($message);
$frequency = server_result($result);

echo '<img src="scale.jpg"'.'<br/>';
?>


<form action="dl-fldigi.php" method="post">

<INPUT TYPE="image" SRC="dl-fldigi-waterfall.png" name="image"/>
<P>
Carrier = <input type="submit" name="direction" value ="up" />
<input type="submit" name="direction" value ="down" />
&nbsp; Reverse = <input type="submit" name="reverse" value ="on/off" />
&nbsp; AFC = <input type="submit" name="afc" value ="on/off" />
&nbsp; Squelch = <input type="submit" name="squelch" value ="on/off" />
</p>
<p><select name="Protocol" size="1">
<option>Select Modem</option>
<option>RTTY</option>
<option>CW</option>
<option>DominoEX</option>
<option>BPSK31</option>
</select> &nbsp; 
<input type="submit" value="Submit">
<p><select name="Payload" size="1">
<option>Select Payload</option>
<option>atlas</option>
<option>wb8elk</option>
<option>icarus</option>
<option>hadie</option>
<option>xaben</option>
</select> &nbsp; 
<input type="submit" value="Submit">
</form>
<p>
<p>Rx Output:</p>
<?php
	$message = new xmlrpcmsg('text.get_rx_length');
	
	$result = $server->send($message);
	
	$rx_length = server_result($result);
	//print "<p>$decoded_response</p>";
	
	if( $rx_length > 500){
		$rx_start = $rx_length - 500;
		$data_length = 500;
	}
	else {
		$rx_start = 0;
		$data_length = $rx_length;
	}
	
	$message = new xmlrpcmsg('text.get_rx');
	
	$message->addParam(new xmlrpcval($rx_start,'int'));
	$message->addParam(new xmlrpcval($data_length,'int'));
	$result = $server->send($message);
	
	$decoded_response = server_result($result);
	print nl2br("<p>$decoded_response</p>");
	
	?>
<p>
<p>Debug Output: </p>
<?php
	
	
	if (isset($_POST['squelch']))
	{
		if ($_POST['squelch'] == "on/off")
		{
			// Send a message to the server.
			$message = new xmlrpcmsg('main.toggle_squelch');
			
			$result = $server->send($message);
			
			$decoded_response = server_result($result);
				if ($decoded_response == 1) {
					print "<p>Squelch On</p>";
				}
				else if ($decoded_response == 0) {
					print "<p>Squelch Off</p>";
				}					
				
			}
	}

	if (isset($_POST['afc']))
	{
		if ($_POST['afc'] == "on/off")
		{
			// Send a message to the server.
			$message = new xmlrpcmsg('main.toggle_afc');
			
			$result = $server->send($message);
			
			$decoded_response = server_result($result);
				if ($decoded_response == 1) {
					print "<p>AFC On</p>";
				}
				else if ($decoded_response == 0) {
					print "<p>AFC Off</p>";
				}					
		}
	}

	if (isset($_POST['reverse']))
	{
		if ($_POST['reverse'] == "on/off")
		{
			// Send a message to the server.
			$message = new xmlrpcmsg('main.toggle_reverse');
			
			$result = $server->send($message);
			
			$decoded_response = server_result($result);
				if ($decoded_response == 1) {
					print "<p>Reverse On</p>";
				}
				else if ($decoded_response == 0) {
					print "<p>Reverse Off</p>";
				}					
		}
	}
			
	if (isset($_POST['direction']))
	{
		if ($_POST['direction'] == "up")
		{
			// Send a message to the server.
			$up_freq = $frequency += 100;
			//print "<p>$up_freq</p>";
			$message = new xmlrpcmsg('modem.set_carrier');
			$message->addParam(new xmlrpcval($up_freq,'int'));

			$result = $server->send($message);
			
			$decoded_response = server_result($result);
			//print "<p>$decoded_response</p>";
			$frequency = $decoded_response;
		}
		
		if ($_POST['direction'] == "down")
		{
			// Send a message to the server.
			$down_freq = $frequency -= 100;
			//print "<p>$down_freq</p>";
			$message = new xmlrpcmsg('modem.set_carrier');
			$message->addParam(new xmlrpcval($down_freq,'int'));
			
			$result = $server->send($message);
			
			$decoded_response = server_result($result);
			//print "<p>$decoded_response</p>";
			$frequency = $decoded_response;			
		}
	}
	if (isset($_POST['Protocol']))
	{
		if ($_POST['Protocol'] == "CW")
		{
			$message = new xmlrpcmsg('modem.set_by_name');
			$message->addParam(new xmlrpcval("CW"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
		if ($_POST['Protocol'] == "DominoEX22")
		{
			$message = new xmlrpcmsg('modem.set_by_name');
			$message->addParam(new xmlrpcval("DOMEX22"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
		if ($_POST['Protocol'] == "RTTY")
		{
			$message = new xmlrpcmsg('modem.set_by_name');
			$message->addParam(new xmlrpcval("RTTY"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
                if ($_POST['Protocol'] == "BPSK31")
                {
                        $message = new xmlrpcmsg('modem.set_by_name');
                        $message->addParam(new xmlrpcval("BPSK31"));
                        
                        $result = $server->send($message);
                        $decoded_response = server_result($result);
                }

	}
	if (isset($_POST['image_x']))
	{
		echo $_POST['image_x'];
		$position_x = $_POST['image_x'];
		$click_freq = $position_x * 4;
		echo "  ";
		echo $click_freq;
		$frequency = $click_freq;
		$message = new xmlrpcmsg('modem.set_carrier');
		$message->addParam(new xmlrpcval($frequency,'int'));
		
		$result = $server->send($message);
		
		$decoded_response = server_result($result);
		//print "<p>$decoded_response</p>";
		$frequency = $decoded_response;
		
	}
	if (isset($_POST['Payload']))
	{
		if ($_POST['Payload'] == "atlas")
		{
			$message = new xmlrpcmsg('payload.select_payload');
			$message->addParam(new xmlrpcval("atlas"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
		if ($_POST['Payload'] == "wb8elk")
		{
			$message = new xmlrpcmsg('payload.select_payload');
			$message->addParam(new xmlrpcval("wb8elk"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
                if ($_POST['Payload'] == "hadie")
                {
                        $message = new xmlrpcmsg('payload.select_payload');
                        $message->addParam(new xmlrpcval("hadie"));
                        
                        $result = $server->send($message);
                        $decoded_response = server_result($result);
                }
                if ($_POST['Payload'] == "xaben")
                {
                        $message = new xmlrpcmsg('payload.select_payload');
                        $message->addParam(new xmlrpcval("xaben"));
                        
                        $result = $server->send($message);
                        $decoded_response = server_result($result);
                }

		if ($_POST['Payload'] == "icarus")
		{
			$message = new xmlrpcmsg('payload.select_payload');
			$message->addParam(new xmlrpcval("icarus"));
			
			$result = $server->send($message);
			$decoded_response = server_result($result);
		}
	}
	?>

</body></html>
