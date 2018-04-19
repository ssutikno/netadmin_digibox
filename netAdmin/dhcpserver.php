<?php
$filename = '/etc/dnsmasq.conf';
$lines = file($filename);
$output = array();
foreach($lines as $line){
	$values = explode("=",$line);
	switch($values[0]){
		case 'dhcp-range':
			$line = 'dhcp-range='. $_POST['input_ipstart'].','. $_POST['input_ipend'].','. $_POST['input_netmask'].','. $_POST['input_leasetime'].PHP_EOL;
			//echo $line.'<br>';
			break;
/*		case 'wpa_passphrase':
			$line = 'wpa_passphrase='.$_POST['input_password']; 
			//echo $line.'<br>';
			break;*/
	}
	$output[] = $line;
}
$fileoutput = fopen($filename, 'w');
foreach($output as $line){
	fwrite($fileoutput, $line);
}
fclose($fileoutput);
unset($output, $lines, $line, $values);
header('Location: index.php');
?>