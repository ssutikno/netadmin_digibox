<?php
include('functions.php');
echo get_ssid();
$start='';
$end ='';
$netmask='';
$leasetime='';
get_dhcpinfo($start, $end, $netmask,$leasetime);
echo 'Start : '.$start.'<br>';
echo 'end : '.$end.'<br>';
echo 'netmask : '.$netmask.'<br>';
echo 'leasetime : '.$leasetime.'<br>';
?>