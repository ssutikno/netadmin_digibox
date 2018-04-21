<?php
session_start();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="dists/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="dists/js/jquery-3.3.1.min.js"></script>
<?php
	include('functions.php');
?>
</head>
<body>
	<div class="container">
		<h2>LIBRO SIMPLE NET ADMIN</h2><br>
		<?php
		include "config.php";
		
		if(isset($_POST['dns'])){
				$file = fopen('/etc/librohosts',"w");
				$text = $_POST["dns"];
			//echo $text."...";
				fwrite($file, $text);
				fclose($file);  
			echo "SAVED";
		}

		?>
		<div class="row">
			
			<nav>
			  <div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Network Interface</a>
				<a class="nav-item nav-link" id="nav-hotspot-tab" data-toggle="tab" href="#nav-hotspot" role="tab" aria-controls="nav-hotspot" aria-selected="false">Hotspot</a> 
				<a class="nav-item nav-link" id="nav-dhcpserver-tab" data-toggle="tab" href="#nav-dhcpserver" role="tab" aria-controls="nav-dhcpserver" aria-selected="false">DHCP Server</a>
				<a class="nav-item nav-link" id="nav-dhcpleases-tab" data-toggle="tab" href="#nav-dhcpleases" role="tab" aria-controls="nav-dhcpleases" aria-selected="false">DHCP leases</a>
				<a class="nav-item nav-link" id="nav-vbox-tab" data-toggle="tab" href="#nav-vbox" role="tab" aria-controls="nav-vbox" aria-selected="false">Virtual Machines</a>  
				<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>
			  </div>
			</nav>
		</div>
	
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
				<br>
				<form action="index.php" method="post" name="netadmin" id="netadmin" >
					<div class="form-group row inline">
						<label for="inputWAN" class="col-sm-2 col-form-label">WAN IP address</label>
						<div class="col-sm-3">
							<input type="text" disabled class="form-control" id="inputWAN" placeholder="IP Address" value="<?php echo get_Net_IP('enp0s25'); ?>">
						</div>
						<div class="col-sm-2">
							<select class="form-control" readonly value="enp0s25">
								<?php 
								$ifaces = get_Net_interfaces();
								foreach($ifaces as $iface){
									if($iface!='lo'){
										echo '<option ';
										if ($iface == 'enp0s25'){ echo 'selected';}
										echo '>'.$iface.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row inline">
						<label for="inputLAN" class="col-sm-2 col-form-label">LAN ( Wifi / USB )</label>
						<div class="col-sm-3">
							<input type="text" disabled class="form-control" id="inputLAN" placeholder="IP Address" value="<?php echo get_Net_IP('br0'); ?>">
						</div>
						<div class="col-sm-2">
							<select class="form-control" readonly>
								<?php 
								$ifaces = get_Net_interfaces();
								foreach($ifaces as $iface){
									if($iface!='lo'){
										echo '<option ';
										if ($iface == 'br0'){ echo 'selected';}
										echo '>'.$iface.'</option>';
									}
								}
								?>
							</select>
						</div>						
					</div>
					<div class="form-group row inline">

						<label for="hostnames" class="col-sm-2 col-form-label">Local Host Names</label><br>
					  <textarea name="dns" class="col-sm-5" rows="8" id="hostnames" name="dnstext" form="netadmin">
<?php
						  echo file_get_contents("/etc/librohosts");
?></textarea>
					</div>
					 <br>
					<i class="alert">Restart system to apply new setting.</i>
						<br><br>
					  <input type="submit" value="Submit" class="btn btn-primary">
				</form>
			</div>
			
			<!--
				HOTSPOT TAB
			-->
			<div class="tab-pane fade" id="nav-hotspot" role="tabpanel" aria-labelledby="nav-hotspot-tab">
				<form action="hotspot.php" method="post">
					<br>
					<div class="form-group row inline">
						<label for="input_ssid" class="col-sm-2 col-form-label">SSID</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_ssid" name="input_ssid" placeholder="SSID" value="<?php echo get_ssid(); ?>">
						</div>
					</div>					
					<div class="form-group row inline">
						<label for="input_password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_password" name="input_password" placeholder="Password" value="<?php echo get_hotspotpasswd(); ?>">
						</div>
					</div>					
					 <br>
					<i class="alert">Restart system to apply new setting.</i>
						<br><br>
					  <input type="submit" value="Submit" class="btn btn-primary">
				</form>
			</div>
			<!--
				DHCP TAB
			-->
			<div class="tab-pane fade" id="nav-dhcpserver" role="tabpanel" aria-labelledby="nav-dhcpserver-tab">
				<br>
				<h2>DHCP Server</h2>
				<br>
				<?php
				$dhcp_ipstart = '';
				$dhcp_ipend = '';
				$dhcp_netmask = '';
				$dhcp_leasetime='';
				get_dhcpinfo($dhcp_ipstart, $dhcp_ipend, $dhcp_netmask, $dhcp_leasetime);
				?>
				<form action="dhcpserver.php" method="post">
					<div class="form-group row inline">
						<label for="input_ipstart" class="col-sm-2 col-form-label">Start IP</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_ipstart" name="input_ipstart" placeholder="Start" value="<?php echo $dhcp_ipstart; ?>">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_ipend" name="input_ipend" placeholder="End" value="<?php echo $dhcp_ipend; ?>">
						</div>
					</div>					
					<div class="form-group row inline">
						<label for="input_netmask" class="col-sm-2 col-form-label">Netmask</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_netmask" name="input_netmask" placeholder="Netmask" value="<?php echo $dhcp_netmask; ?>">
						</div>
					</div>					
					<div class="form-group row inline">
						<label for="input_leasetime" class="col-sm-2 col-form-label">Lease Time</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="input_leasetime" name="input_leasetime" placeholder="Lease Time" value="<?php echo $dhcp_leasetime; ?>">
						</div>
					</div>					
					 <br>
					<i class="alert">Restart system to apply new setting.</i>
						<br><br>
					  <input type="submit" value="Submit" class="btn btn-primary">					
				</form>
			</div>			
			<!--
				DHCP LEASES TAB
			-->
			<div class="tab-pane fade" id="nav-dhcpleases" role="tabpanel" aria-labelledby="nav-dhcpleases-tab">
				<br>
				<table id="ip_leases" border="1">
					<thead>
					<tr>
						<th width="40px">No</th>
						<th width="220px">Host Name</th>
						<th width="150px">IP Address</th>
						<th width="150px">Mac Address</th>
						
					</tr>
					  </thead>
					  <tbody>
				  <?php
					$handle = fopen("/var/lib/misc/dnsmasq.leases", "r");
					if ($handle) {
						$a = 0;
						$text = '';
						while (($line = fgets($handle)) !== false) {
							echo '<tr>';
							$a++;
							$cols = explode(" ", $line);
							echo '<td>'.$a.'</td>';
							echo '<td>'.$cols[3]."</td>";
							echo '<td>'.$cols[2]."</td>";
							echo '<td>'.$cols[1]."</td>";
							echo '</tr>';
						}

						fclose($handle);
					}
				  ?>
					  </tbody>
				  </table>
			</div>
			<!--
				VirtualBOX TAB
			-->			
			
			<div class="tab-pane fade" id="nav-vbox" role="tabpanel" aria-labelledby="nav-vbox-tab">
				<br>
				<h2>Virtual Machines</h2><br><br>
				<input type="button" class="btn btn-info" value="Start" onclick=" relocate_home()">
			</div>

			<!--
				Contact TAB
			-->			
			<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
				<br>
				<h2>Contact</h2>
				<p>
				Sugiyanto Sutikno<br>
					email : <a href="mailto:sugiyanto.sutikno@gail.com">sugiyanto.sutikno@gmail.com</a>
				</p>
			</div>
		</div>
	</div>

<script>
$(document).ready(function() {
    $('#ip_leases').DataTable();
} );
function relocate_home()
{
     location.href = "/vbox";
} 
	
</script>
	
<script type="text/javascript" src="dists/js/bootstrap.min.js"></script>
<script type="text/javascript" src="DataTables/datatables.min.js"></script>
</body>
</html>