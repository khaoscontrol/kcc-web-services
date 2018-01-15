<?php
   /*
      This shows a sample of sending orders to the web service
      See order download in teh documentation
   */

   // create some sample orders, ready for import. These would normally come from your database
   $orders = array(
      array(
         "CreateNew" => "IfNoMatch", // create new customer
         "CompanyName" => "Mr Terry Orange", 
         "Current Code" => "GBP",
         "MaiilingStatus" => "4",

         "InvoiceAddress" => array(
            "Line1" => "1-3 The Court",
            "Town" => "Nottingham",
            "PostCode" => "NT129AQ",
            "Country Code" => "GB"
         ),
         "InvoiceContact" => array(
            "ForeName" => "Terry",
            "LastName" => "Orange", 
            "Email" => "terry@sample.com"
         ),
         "Header" => array(
            "AssociatedRef" => "web_order_".time(),
            "OrderDate" => "2017-04-28T05:57:14", // make dynamic
            "Site ID" => "1",
            "Discounts" => array()
         ),
         "Items" => array(
            "OrderItem" => array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => "1",
               "ExtendedDescription" => ""
            )
         ),
         "Payments" => array(
            "OrderPayment" => array(
               "Amount" => "2.00",
               "Card" => array(
                  "CardType" => "Visa",
                  "IsPreauth" => "false",
                  "AuthCode" => "VISA",
                  "TransactionID" => "payment_".time()
               )
            )
         )
      ), array(
         "CreateNew" => "IfNoMatch", // create new customer
         "CompanyName" => "Mr Megan Red", 
         "Current Code" => "GBP",
         "MaiilingStatus" => "4",

         "InvoiceAddress" => array(
            "Line1" => "1-3 The Tower",
            "Town" => "Lincoln",
            "PostCode" => "LN127YU",
            "Country Code" => "GB"
         ),
         "InvoiceContact" => array(
            "ForeName" => "Megan",
            "LastName" => "Red", 
            "Email" => "megan@sample.com"
         ),
         "InvoiceAddress" => array(
            "Line1" => "1-3 The Tower",
            "Town" => "Lincoln",
            "PostCode" => "LN127YU",
            "Country Code" => "GB"
         ),
         "InvoiceContact" => array(
            "ForeName" => "Tony",
            "LastName" => "Red", 
            "Email" => "megan@sample.com"
         ),
         "Header" => array(
            "AssociatedRef" => "web_order_".(time()+2),
            "OrderDate" => "2017-04-28T05:57:14", // make dynamic
            "Site ID" => "1",
            "Discounts" => array()
         ),
         "Items" => array(
            "OrderItem" => array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => "2",
               "ExtendedDescription" => ""
            ),
            "OrderItem" => array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => "1",
               "ExtendedDescription" => "Cloud needs to be in green"
            )
         ),
         "Payments" => array(
            "OrderPayment" => array(
               "Amount" => "6.00",
               "Card" => array(
                  "CardType" => "Visa",
                  "IsPreauth" => "false",
                  "AuthCode" => "VISA",
                  "TransactionID" => "payment_".(time()+2)
               )
            )
         )
      )
   );
   // create the orders in XML format, this could be JSON 
   header('Content-Type: application/json');
   // add tracking header, which will be sent back in the next order import pull request
   header("Sirion-Continuation", time());
   // wrap the orders inside a SalesOrderImport array
   $output = array("SalesOrderImport" => array(
      "Orders" => array(),
      "ApiVersion" => "10000",
      "Config" => array(
         "MatchCompanyOn" => "Address1",
         "MatchCompanyOn" => "CompanyCode",
         "MatchAddressOn" => "Address1",
         "MatchAddressOn" => "Postcode",
         "MatchContactOn" => "Surname"
      )
   ));
   foreach($orders as $order) {
      array_push($output["SalesOrderImport"]["Orders"], array(
         "SalesOrder" => $order
      ));
   }
   echo json_encode($output);
?>