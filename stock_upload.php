<?php
/*
   This shows a sample of sending stock to the web service
   See Stock Upload in the documentation
*/

   // create some sample stock items ready for upload. these generally come from your database

   // create the orders in XML format, this could be JSON
   header('Content-Type: application/json');
   // add tracking header, which will be sent back in the next order import pull request
   header("Sirion-Continuation", time());

   $stockitems = array(
      array(
         "StockID" => "LJKSFD",
         "StockCode" => "SKUSKU",
         "ShortDescription" => "Non Stock Item",
         "LongDescription" => "Somewhat defies description, nobody really knows what to say about flumphs",
         "BuyPrice" => array(
            "Gross" => 4
         ),
         "SellPrice" => array(
            "Net" => 6
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Bizarre Things"
         ),
         "StockType2" => array(
            "Name" => "Flumphs"
         ),
         "StockType3" => array(
            "Name" => "Partially Floating"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => false,
            "FreeText" => true,
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "SalesMultiple" => 10,
         "LeadTime" => 13,
         "SupplierInfo" => array(
            array(
               "URN" => "AH",
               "IsPreferred" => true,
               "SupplierRef" => "FL8"
            ),
            array(
               "URN" => "DC",
               "IsPreferred" => false,
               "SupplierRef" => "FMPHZ"
            )
         ),
         "Images" => array(
            array(
               "Name" => "Semi main",
               "Filename" => "https://orig11.deviantart.net/7efd/f/2014/149/c/8/captain_america__the_winter_soldier_by_wooshiyong-d7ka49c.jpg"
            )
         ),
         "Barcodes" => array(
            array(
               "Barcode" => "30895732948",
               "Type" => array(
                  "Name" => "GTIN"
               )
            )
         )
      ),
      array(
         "StockID" => "X5436",
         "StockCode" => "0-Z-EW",
         "ShortDescription" => "Test Pack Item",
         "BuyPrice" => array(
            "Net" => 2
         ),
         "SellPrice" => array(
            "Net" => 6
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Standard"
         ),
         "StockType2" => array(
            "Name" => "System & Misc Types"
         ),
         "StockType3" => array(
            "Name" => "System Miscellaneous"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => false,
            "FreeText" => false,
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "SalesMultiple" => 1,
         "LeadTime" => 1,
         "SupplierInfo" => array(
            array(
               "URN" => "AH",
               "IsPreferred" => false,
               "Name" => "Aroma Home"
            )
         )
      ),
      array(
         "StockID" => "X5437",
         "StockCode" => "BANANAS2",
         "ShortDescription" => "Build Parent",
         "BuyPrice" => array(
            "Net" => 0
         ),
         "SellPrice" => array(
            "Net" => 3
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Standard"
         ),
         "StockType2" => array(
            "Name" => "System & Misc Types"
         ),
         "StockType3" => array(
            "Name" => "System & Miscellaneous"
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
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "SalesMultiple" => 1,
         "LeadTime" => 1
      ),
      array(
         "StockID" => "5439",
         "StockCode" => "0-ZZ-TESTBUILDITEM",
         "ShortDescription" => "TESTING HT",
         "BuyPrice" => array(
            "Net" => 3
         ),
         "SellPrice" => array(
            "Net" => 5
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Standard"
         ),
         "StockType2" => array(
            "Name" => "System & Misc Types"
         ),
         "StockType3" => array(
            "Name" => "System & Miscellaneous"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => false,
            "FreeText" => false,
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "SalesMultiple" => 1,
         "LeadTime" => 1
      ),
      array(
         "StockID" => "2967",
         "StockCode" => "0-Z-B0714PRW7R",
         "ShortDescription" => "Apples, Oranges, Pears",
         "BuyPrice" => array(
            "Net" => 1.23
         ),
         "SellPrice" => array(
            "Net" => 6.67
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Eye Masks"
         ),
         "StockType2" => array(
            "Name" => "Sleep Masks"
         ),
         "Options" => array(
            "PublishOnWeb" => false,
            "Discontinued" => true,
            "DropShipItem" => true,
            "DiscountsDisabled" => true,
            "RunToZero" => true,
            "VatReliefQualified" => true,
            "StockControlled" => false,
            "FreeText" => true,
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "Manufacturer" => array(
            "ID" => "10",
            "Name" => "Heat Treats"
         ),
         "AverageWeight" => 79,
         "SalesMultiple" => 3,
         "LeadTime" => 4,
         "SupplierInfo" => array(
            array(
               "URN" => "AH",
               "IsPreferred" => true,
               "Name" => "Aroma Home"
            )
         ),
      ),
      array(
         "StockID" => "BANS1",
         "StockCode" => "Bananas1",
         "ShortDescription" => "1 Bananananans",
         "BuyPrice" => array(
            "Gross" => 4
         ),
         "SellPrice" => array(
            "Net" => 6
         ),
         "TaxRate" => array(
            "Name" => "Standard"
         ),
         "StockType1" => array(
            "Name" => "Groceries"
         ),
         "StockType2" => array(
            "Name" => "Fruit"
         ),
         "StockType3" => array(
            "Name" => "Bananas"
         ),
         "StockType4" => array(
            "Name" => "Banana Skins"
         ),
         "Options" => array(
            "PublishOnWeb" => true,
            "Discontinued" => false,
            "DropShipItem" => false,
            "DiscountsDisabled" => false,
            "RunToZero" => false,
            "VatReliefQualified" => false,
            "StockControlled" => false,
            "FreeText" => true,
            "CustomOptions" => array(),
            "NonPhysical" => false
         ),
         "SalesMultiple" => 10,
         "LeadTime" => 13,
         "SupplierInfo" => array(
            array(
               "URN" => "AH",
               "IsPreferred" => true,
               "SupplierRef" => "FL8"
            ),
            array(
               "URN" => "DC",
               "IsPreferred" => false,
               "SupplierRef" => "FMPHZ"
            )
         ),
         "Images" => array(
            array(
               "Name" => "Semi main",
               "Filename" => "https://orig11.deviantart.net/7efd/f/2014/149/c/8/captain_america__the_winter_soldier_by_wooshiyong-d7ka49c.jpg"
            )
         ),
         "Barcodes" => array(
            array(
               "Barcode" => "30895732948",
               "Type" => array(
                  "Name" => "GTIN"
               )
            )
         )
      ),      
   );

   $relationships = array(
      array(
         "StockID" => "X5436",
         "SCSParent" => array(
            "Headings" => array(
               "Genus",
               "Voltage"
            )
         )
      ),
      array(
         "StockID" => "X5438",
         "Linked" => array(
            array(
               "LinkedStockID" => "X5436",
               "Type" => "UpSell",
               "AutoAdd" => false,
               "SellPrice" => 10.05
            ),
         ),
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "12"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "12V"
            )
         )
      ),
      array(
         "StockID" => "X5437",
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "6"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "6V"
            )
         )
      ),
      array(
         "StockID" => "X5436",
         "SCSParent" => array(
            "Headings" => array(
               "Genus",
               "Voltage"
            )
         )
      ),
      array(
         "StockID" => "X5438",
         "Linked" => array(
            array(
               "LinkedStockID" => "X5436",
               "Type" => "UpSell",
               "AutoAdd" => false,
               "SellPrice" => 10.05
            )
         ),
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "12"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "12V"
            )
         )
      ),
      array(
         "StockID" => "X5437",
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "6"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "6V"
            )
         )
      ),
      array(
         "StockID" => "X5436",
         "SCSParent" => array(
            "Headings" => array(
               "Genus",
               "Voltage"
            )
         )
      ),
      array(
         "StockID" => "X5438",
         "Linked" => array(
            array(
               "LinkedStockID" => "X5436",
               "Type" => "UpSell",
               "AutoAdd" => false,
               "SellPrice" => 10.05
            )
         ),
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "12"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "12V"
            )
         )
      ),
      array(
         "StockID" => "X5437",
         "SCSChild" => array(
            "ParentStockID" => "X5436",
            "SCSValues" => array(
               "FL",
               "6"
            ),
            "SCSDescriptions" => array(
               "Flumph",
               "6V"
            )
         )
      )
   );

   // wrap the stock inside a StockUpload array
   $output = array(
      "StockItems" => array(
         "Items" => $stockitems
      ),
      "Relationships" => array(
         "Relationships" => $relationships
      )
   );

   echo json_encode($output);
?>
