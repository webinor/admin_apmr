<?php 
//echo dirname(__FILE__);
$command = escapeshellcmd('python3 '.dirname(__FILE__).'/laravel.py --algo=all');
$output = shell_exec($command);
echo $output;

?>
  