<?php
  if(!fileexist('/etc/librohosts')){
      $librohosts = '192.168.100.1 digibox.id';
      file_put_contents('/etc/librohosts', $librohosts);
  }
?>
