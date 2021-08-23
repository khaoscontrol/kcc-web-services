<?php
   /*
      This shows a sample of sending returns to the web service
      See return download in the documentation
   */

  function array_to_xml( $data, &$xml_data ) {
   foreach( $data as $key => $value ) {
       if( is_numeric($key) ){
           $key = "ReturnItem"; //dealing with <0/>..<n/> issues
       }
       if( is_array($value) ) {
           $subnode = $xml_data->addChild($key);
           array_to_xml($value, $subnode);
       } else {
           $xml_data->addChild("$key",htmlspecialchars("$value"));
       }
    }
} 

   // create some sample returns, ready for import. These would normally come from your database
   $returns = array(
      "AssociatedRef" => "AREF1530" . date("mdH"),
      "CreatedDate" => date("Y-m-d\TH:i:s", time()),
      "URN" => "TEST",
      "InvoiceAddress" => arraY(
         "Line1" => "1 - 3 Priest Court",
         "Town" => "Grantham",
         "Postcode" => "NG31 7FZ",
         "Country" => array(
            "Code" => "GB"
         ),
         "Telephone" => "01234567890",
         "Organisation" => "Khaos Control Cloud Ltd"
      ),
      "InvoiceContact" => arraY(
         "Surname" => "Tester",
         "Email" => "test@testing.com"
      ),
      "DeliveryAddress" => arraY(
         "Line1" => "1 - 3 Priest Court",
         "Town" => "Grantham",
         "Postcode" => "NG31 7FZ",
         "Country" => array(
            "Code" => "GB"
         ),
         "Telephone" => "01234567890",
         "Organisation" => "Khaos Control Cloud Ltd"
      ),
      "InvoiceContact" => arraY(
         "Forename" => "Test",
         "Email" => "test@testing.com"
      ),  
      "ReturnedItems" => array(
         array(
            "SKU" => "TESTITEM001",
            "Mapping" => array(array("Mapping" => "StockCode")),
            "Quantity" => 3.0,
            "ReturnReason" => array(
               "ID" => "6"
            ),
            "ExtendedDescription" => array(

            ),
            "SourceReference" => array(
               "SOrderID" => "2075"
            )
         )
      ),
      "ExchangeItems" => array(
         array(
            "SKU" => "TESTITEM002",
            "Mapping" => array(array("Mapping" => "StockCode")),
            "Quantity" => 1.0,
            "ExtendedDescription" => array(
               
            )
         )
      )
   );
   // create the orders in JSON format, this could be XML
   header('Content-Type: application/json');
   // add tracking header, which will be sent back in the next order import pull request
   header("Sirion-Continuation", time());
 
   // wrap the orders inside a SalesOrderImport array
   $output = array(
      "Returns" => array($returns),
      "ApiVersion" => 10000,
      "Config" => array(
         "MatchAddressOn" => ["Address1", "Postcode"],
         "MatchContactOn" => ["Surname"]
      )
   );
   foreach($orders as $order) {
      array_push($output["Orders"], $order);
   }

   if (isset($_GET["xml"]) === false) {
      echo json_encode($output);
   } else {
      $xml = new SimpleXMLElement('<CustomerReturnImport/>');
      array_to_xml($output,$xml);
      echo $xml->asXML();  
   }
?>
