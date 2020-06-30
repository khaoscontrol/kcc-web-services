<?php
   $valid_passwords = array("user" => "p");
   $valid_users = array_keys($valid_passwords);

   $user = (isset($_SERVER["PHP_AUTH_USER"]) ? $_SERVER["PHP_AUTH_USER"] : "");
   $pass = (isset($_SERVER["PHP_AUTH_PW"]) ? $_SERVER["PHP_AUTH_PW"] : "");

   $validated = (in_array($user, $valid_users) && $pass == $valid_passwords[$user]);

   if (!isset($_SERVER["PHP_AUTH_USER"]) || !$validated) {
      header('WWW-Authenticate: Basic realm="KCC Web Services Demo"');
      header("HTTP/1.0 401 Unauthorized");
      die(json_encode(array("error" => "Basic auth failed. Invalid credentials were provided.")));
   }
   /*
      This shows a sample of sending orders to the web service
      See order download in the documentation
   */

   // create some sample orders, ready for import. These would normally come from your database
   $orders = array(
      array(
         "Customer" => array(
            "CreateNew" => "IfNoMatch", // create new customer
            "CompanyName" => "Mr Terry Orange",
            "CompanyClass" => array(
               "Name" => "Playground"
            ),
            "Currency" => array(
               "Name" => "Pounds Sterling",
               "Code" => "GBP"
            ),
            "URN" => "AA_Test_1"
         ),

         "InvoiceAddress" => array(
            "Line1" => "Addr1",
            "Town" => "Nottingham",
            "Postcode" => "NG31 7AA",
            "Country" => array(
               "Name" => "Great Britain",
               "Code" => "GB"
            )
         ),
         "InvoiceContact" => array(
            "Forename" => "Terry",
            "Surname" => "Orange", 
            "Email" => "terry@sample.com"
         ),
         "Header" => array(
            "AssociatedRef" => "AREF1630" . date("mdH"),
            "OrderDate" => date("Y-m-d\TH:i:s", time()),
            "Site" => array(
               "ID" => "1",
               "Name" => "Main Site"
            ),
            "DiscountCodes" => array(array("Code" => "sp-y8h-nwj-13")),
            "SalesSource" => array(
               "ID" => "5"
            )
         ),
         "Items" => array(
            array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => 1.00,
               "ExtendedDescription" => array(""),
               "StockDescription" => array(
                  "Source" => "Explicit",
                  "Parameter" => "This is a description"
               )
            )
         ),
         "Payments" => array(
            array(
               "Amount" => 2.00,
               "Card" => array(
                  "CardType" => "Visa",
                  "IsPreauth" => false,
                  "AuthCode" => "VISA",
                  "TransactionID" => "payment_".time()
               )
            )
         )
      ), array(
         "Customer" => array(
            "CreateNew" => "IfNoMatch", // create new customer
            "CompanyName" => "Mr Megan Red", 
            "CompanyClass" => array(
               "Name" => "Playground"
            ),
            "Currency" => array(
               "Name" => "Pounds Sterling",
               "Code" => "GBP"
            )
         ),
         "InvoiceAddress" => array(
            "Line1" => "1-3 The Tower",
            "Town" => "Lincoln",
            "Postcode" => "LN127YU",
            "Country" => array(
               "Name" => "Great Britain",
               "Code" => "GB"
            )
         ),
         "InvoiceContact" => array(
            "Forename" => "Tony",
            "Surname" => "Red", 
            "Email" => "megan@sample.com"
         ),
         "Header" => array(
            "AssociatedRef" => "AREF1531" . date("mdH"),
            "OrderDate" => date("Y-m-d\TH:i:s", time()),
            "Site" => array(
               "ID" => "1",
               "Name" => "Main Site"
            ),
            "DiscountCodes" => array(),
            "SalesSource" => array(
               "ID" => "5"
            )
         ),
         "Items" => array(
            array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => 2.0,
               "ExtendedDescription" => array(""),
               "StockDescription" => array(
                  "Source" => "Explicit",
                  "Parameter" => "This is a description"
               )
            ),
            array(
               "SKU" => "MUG1",
               "Mapping" => "StockCode",
               "Quantity" => 1.00,
               "ExtendedDescription" => array("Cloud needs to be in green"),
               "StockDescription" => array(
                  "Source" => "Explicit",
                  "Parameter" => "This is a description"
               )
            )
         ),
         "Payments" => array(
            array(
               "Amount" => 6.00,
               "Card" => array(
                  "CardType" => "Visa",
                  "IsPreauth" => false,
                  "AuthCode" => "VISA",
                  "TransactionID" => "payment_".(time()+2)
               )
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
      "Orders" => array(),
      "ApiVersion" => 10000,
      "Config" => array(
         "MatchCompanyOn" => ["Address1", "CompanyCode"],
         "MatchAddressOn" => ["Address1", "Postcode"],
         "MatchContactOn" => ["Surname"]
      )
   );
   foreach($orders as $order) {
      array_push($output["Orders"], $order);
   }
   echo json_encode($output);
?>
