<?php
   /*
      This shows a sample of sending stock to the web service
      See provide stock in the documentation
   */

   // create some sample stock items, ready for import. These would normally come from your database
   $items = array(
     array(
         "StockID" => "6027",
         "StockCode" => "100",
         "ShortDescription" => "Hippo Giant Driver",
         "BuyPrice" => array(
            "Net" => 10.0
         ),
         "SellPrice" => array(
            "Net" => 140.0
         ),
         "TaxRate" => array(
            "Name" => "Standard",n
            "Code" => "1",
            "ID" => "1"
         ),
         "StockType1" => array(
            "Name" => "System & Misc Types",
            "ID" => "1"
         ),
         "StockType2" => array(
            "Name" => "System & Miscellaneous",
            "ID" => "1"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => true,
            "FreeText" => false,
            "CustomOptions" => array()
         ),
         "SalesMultiple" => 1.0,
         "LeadTime" => 1,
         "SupplierInfo" => array(),
         "Images" => array(),
         "Barcodes" => array()
       ),
       array(
         "StockID" => "6023",
         "StockCode" => "002253",
         "ShortDescription" => "Test",
         "BuyPrice" => array(
            "Net" => 10.0
         ),
         "SellPrice" => array(
            "Net" => 20.0
         ),
         "TaxRate" => array(
            "Name" => "Standard",
            "Code" => "1",
            "ID" => "1"
            ),
            "StockType1" => array(
            "Name" => "System & Misc Types",
            "ID" => "1"
         ),
         "StockType2" => array(
            "Name" => "System & Miscellaneous",
            "ID" => "1"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => true,
            "FreeText" => false,
            "CustomOptions" => array()
         ),
         "SalesMultiple" => 1.0,
         "LeadTime" => 1,
         "SupplierInfo" => array(),
         "Images" => array(),
         "Barcodes" => array(
            array(
               "Barcode" => "002253",
               "Type" => array(
                  "Name" => "Shopify",
                  "ID" => "9"
               )
            )
         )
       )
    );
   // create the stock in JSON format, this could be XML
   header('Content-Type => application/json');
   // add tracking header, which will be sent back in the next stock import request
   header("Sirion-Continuation", time());
   // wrap the stock inside a Items array
   $output = array(
      "Items" => $items,
      "Deleted" => array('123456'), // an array of stock ids to delete
      "ApiVersion" => 10000,
   );
   echo json_encode($output);
?>
