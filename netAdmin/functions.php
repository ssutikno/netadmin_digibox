<?php 
function get_Net_interfaces(){
	$iface = scandir('/sys/class/net');
	return array_slice($iface, 2);
}
function get_Net_IP($iface){
	$last_line = exec('ip -f inet addr show '.$iface, $full_output);
/*	foreach($full_output as $line){
		echo $line.'<br>';
	}*/
	$i = explode(' ',$full_output[1]);
	$ip = $i[5];
	return $ip;
}

function get_hotspot_conf($search){
	$file = fopen('/etc/hostapd/hostapd.conf','r') or die('Error opening hotspot configuration..');
	//$search = 'ssid';
	while(!feof($file)){
		$line = fgets($file);
		if(substr($line,0,strlen($search))==$search){
			$l = strlen($line);
			fclose($file);
			return substr($line,strlen($search)+1);
			exit;
		}
	}
	fclose($file);
	return 'none';	
}
function get_ssid(){
	return get_hotspot_conf('ssid');
}

function get_hotspotpasswd(){
	return get_hotspot_conf('wpa_passphrase');
}

function get_dhcpinfo(&$start, &$end, &$netmask, &$leasetime){
	$file = fopen('/etc/dnsmasq.conf','r') or die('Error opening dhcp config file..');
	while(!feof($file)){
		$line=fgets($file);
		
		$values=explode('=',$line);
		if($values[0]=='dhcp-range'){
			//echo $values[1];
			$data = explode(',',$values[1]);
			$start=$data[0];
			$end = $data[1];
			$netmask = $data[2];
			$leasetime = $data[3];
		}
	}
	fclose($file);
}
?>