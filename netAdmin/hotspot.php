<?php
$lines = file('/etc/hostapd/hostapd.conf');
$output = array();
foreach($lines as $line){
	$values = explode("=",$line);
	switch($values[0]){
		case 'ssid':
			$line = 'ssid='. $_POST['input_ssid'].PHP_EOL;
			//echo $line.'<br>';
			break;
		case 'wpa_passphrase':
			$line = 'wpa_passphrase='.$_POST['input_password'].PHP_EOL;
			//echo $line.'<br>';
			break;
	}
	$output[] = $line;
}
$fileoutput = fopen('/etc/hostapd/hostapd.conf','w');
foreach($output as $line){
	fwrite($fileoutput, $line);
	//echo $line.'<br>';
}
fclose($fileoutput);
//file_put_contents('/etc/hostapd/hostapd.conf', print_r($output, true));
unset($output, $lines, $line, $values);
header('Location: index.php');
?>