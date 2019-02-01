<?php 
   $data = file_get_contents('php://input');
   $file = fopen("output/stock_".time().".txt", "w");
   fwrite($file, $data);
   fclose($file);
?>