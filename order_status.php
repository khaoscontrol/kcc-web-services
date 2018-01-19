<?php 
   /*
      This is an example file for getting the order statuses and applying them to your application
      See the documentation for Order Status Update
   */
   try
   {
      // Get the POST data from the page that has been pushed from the webservices
      $data = file_get_contents('php://input');
      if(strlen($data) == 0)
         throw new Exception("POST data is empty");
      // We have some data, let's parse it to JSON, this could be XML set in the config.xml file
      $json = json_decode($data);
      if(!isset($json->Statuses))
         throw new Exception("No statuses");
      // Now let's process our orders to be written to a TSV file
      $file_data = array();
      $output_file = "output/order_history.tsv";
      if(!file_exists($output_file)) {
         // create and add files to the output file
         $file = fopen($output_file, "w");
         array_push($file_data, "KCC_Code\tMy_Code\tStatus");
      } else {
         $file = fopen($output_file, "a");
         $file_data = array("");
      }
      // loop through the statuses of each order
      foreach($json->Statuses as $status) // create file line 
         array_push($file_data, $status->OrderCode."\t".$status->AssociatedRef."\t".$status->Status);
      // Write to file
      if(sizeOf($file_data) > 0)
         fwrite($file, implode("\r\n", $file_data));
      fclose($file);
      // let the web services know that the request was processed correctly, stopping repeated calls with the same data
      // check the PHP interface to present with the correct header
      $sapi_type = php_sapi_name();
      if (substr($sapi_type, 0, 3) == 'cgi')
         header("Status: 200 OK");
      else
         header("HTTP/1.1 200 OK");
   } catch(Exception $e) {
      // Error occured, present the webservices with a 500 error so that it can retry
      echo "Error: ".$e->getMessage();
      // check the PHP interface to present with the correct header
      $sapi_type = php_sapi_name();
      if (substr($sapi_type, 0, 3) == 'cgi')
         header("Status: 500 Internal Server error");
      else
          header("HTTP/1.1 500 Internal Server error");
   }
?>