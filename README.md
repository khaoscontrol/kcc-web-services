# Khaos Control Cloud Web Services

Welcome to the **beta version** of the Khaos Control Cloud Web Services.

<!-- MarkdownTOC depth=4 -->

- [Getting Started](#getting-started)
- [Config File](#config-file)
   - [Structure](#structure)
   - [Example](#example)
- [Data Continuity](#data-continuity)
- [Types &amp; Objects](#types--objects)
   - [Types](#types)
      - [DataItem](#dataitem)
         - [XML](#xml)
         - [JSON](#json)
      - [DateTime](#datetime)
      - [MappingType](#mappingtype)
      - [OrderStatus](#orderstatus)
      - [ShipmentStatus](#shipmentstatus)
   - [Objects](#objects)
      - [Customer](#customer)
      - [Address](#address)
      - [Contact](#contact)
      - [SalesOrder](#salesorder)
      - [OrderHeader](#orderheader)
      - [OrderItem](#orderitem)
      - [OrderItemDescription](#orderitemdescription)
         - [XML](#xml-1)
         - [JSON](#json-1)
      - [ItemMapping](#itemmapping)
      - [OrderPayment](#orderpayment)
      - [CashPayment](#cashpayment)
      - [ChequePayment](#chequepayment)
      - [VoucherPayment](#voucherpayment)
      - [CardPayment](#cardpayment)
      - [Price](#price)
      - [OrderImportConfig](#orderimportconfig)
         - [XML](#xml-2)
         - [JSON](#json-2)
      - [SalesOrderStatus](#salesorderstatus)
      - [Shipment](#shipment)
      - [ShipmentItem](#shipmentitem)
      - [ShipmentPackage](#shipmentpackage)
      - [StockStatus](#stockstatus)
      - [StockLevels](#stocklevels)
      - [BuildPotentials](#buildpotentials)
      - [StockItem](#stockitem)
      - [StockOptions](#stockoptions)
      - [WebProperties](#webproperties)
      - [StockSupplier](#stocksupplier)
      - [StockImage](#stockimage)
      - [StockBarcode](#stockbarcode)
      - [DeletedItem](#deleteditem)
- [Receiving &amp; Responding to server calls](#receiving--responding-to-server-calls)
   - [Order Download](#order-download)
      - [XML](#xml-3)
         - [Properties](#properties)
         - [Response](#response)
      - [JSON](#json-3)
         - [Properties](#properties-1)
         - [Response](#response-1)
   - [Order Status Upload](#order-status-upload)
      - [XML](#xml-4)
         - [Properties](#properties-2)
         - [Request](#request)
      - [JSON](#json-4)
         - [Properties](#properties-3)
         - [Request](#request-1)
   - [Stock Upload](#stock-upload)
      - [XML](#xml-5)
         - [Properties](#properties-4)
         - [Request](#request-2)
      - [JSON](#json-5)
         - [Properties](#properties-5)
         - [Request](#request-3)
   - [Stock Status Upload](#stock-status-upload)
      - [XML](#xml-6)
         - [Properties](#properties-6)
         - [Request](#request-4)
      - [JSON](#json-6)
         - [Properties](#properties-7)
         - [Request](#request-5)

<!-- /MarkdownTOC -->


# Getting Started

The API is very simple and uses a Push/Pull method. Data we export to you will be ``POST``ed to your defined endpoint and the data we import from you will be read from a URL you specify. Both of these requests will either be in ``JSON`` or ``XML`` and are defined in your configuration file

When we push data to you, you must ensure that you give a valid response code in the header, these can either be:

+ 200 (OK)
+ 400 (Bad request or bad data sent)
+ 500 (Error)

Error codes will make the API try again, therefore if you do not respond with a 200(OK) you experience duplicate data.

# Config File

Your configuration file should be publicly accessible via a URL and is specified within the Khaos Control Cloud application. Once our API knows about this file it will cache it once a day, or, you can force a manual update from within Khaos Control Cloud. To do this you will need to change the ``Configuration URL`` field within Khaos Control Cloud, save the document, and then change it back to what it was originally.

## Structure

The data structure must be wrapped inside an ``EndpointConfig`` property and can consist of one or more of the following objects:

Object | Property | Description
--- | --- | ---
**[OrderDownload](#order-download)** | | The endpoint for where your orders can be imported into Khaos Control Cloud
| | URL | The URL of the endpoint, where data is POSTed for you to process
| | Frequency | How frequently (, in minutes,) the endpoint will be contacted
| | Format | The format of the file you have produced, either ``XML`` or ``JSON``
**[OrderStatusUpload](#order-status-upload)** | | The endpoint of where the status information will be POSTed to
| | URL | The URL of the endpoint, where data is POSTed for you to process
| | Format | The format of which to export the information, either ``XML`` or ``JSON``
**[StockStatusUpload](#stock-status-upload)** | | This will define the endpoint to sync Stock Statuses
| | URL | The URL of the endpoint, where data is POSTed for you to process
| | Format | The format of which to export the information, either ``XML`` or ``JSON``
**[StockUpload](#stock-upload)** | | The endpoint of where stock is exported to, so you can update this on your website. See ``StockExport`` for details on the data being exported to you
| | URL | The URL of the endpoint, where data is POSTed for you to process
| | Format | The format of which to export the information, either ``XML`` or ``JSON``

## Example

```xml
<EndpointConfig>
    <OrderDownload>
        <URL>http://siriongenerictest.azurewebsites.net/api/Orders</URL>
        <Format>XML</Format>
        <Frequency>15</Frequency>
    </OrderDownload>
    <OrderStatusUpload>
        <URL>http://siriongenerictest.azurewebsites.net/api/OrderStatus</URL>
        <Format>XML</Format>
    </OrderStatusUpload>
    <StockStatusUpload>
        <URL>http://siriongenerictest.azurewebsites.net/api/StockStatus</URL>
        <Format>XML</Format>
    </StockStatusUpload>
    <StockUpload>
        <URL>http://siriongenerictest.azurewebsites.net/api/Stock</URL>
        <Format>JSON</Format>
    </StockUpload>
</EndpointConfig>
```

# Data Continuity
You may need to keep track of what data has been processed by us, especially when importing large quantity of orders or potentially recovering from an error. To tackle this, you can pass through a a HTTP Header called ``Sirion-Continuation``, this can have any value you like and will be passed back to you, as demonstrated in the following scenario:

> You have 1200 orders to import but you find it best to import 1000 at a time,  by passing through a Sirion-Continuity HTTP header with the value of 1000, the next time we request the orders you will be able to grab the reference of “1000” and make the following 200 orders available for import.

# Types &amp; Objects

## Types

### DataItem

The ``DataItem`` type can represent one of the following:

+ ID
+ Code
+ Name

#### XML

When used as XML, the ``ID``, ``Code``, or ``Name`` are set as attributes on a node.

```xml
<CompanyClass ID="55" />
```

#### JSON

When used as JSON, the ``ID``, ``Code``, or ``Name`` are set as object properties.

```json
"CompanyClass": {
   "ID": "55"
},
```

### DateTime

The ``DateTime`` type is by represented as a ``string`` using the RFC 3339 format without the timezone offset.

``2018-01-18T12:20:48``

### MappingType

The ``MappingType`` type is represented as a one of the following:

```
StockCode
OtherRef
Barcode
WebCode
Automatic
```

### OrderStatus

The ``OrderStatus`` type is represented as one of the following:

```
Received
Shipping
PartialShip
Complete
Cancelled
```

### ShipmentStatus

The ``ShipmentStatus`` type is represented as one of the following:

```
Released
Staging
Payment
Picking
Packing
Shipping
Invoicing
Processing
Issue
AwaitingDate
AwaitingStock
ManualHold
TermsHold
Archived
```

## Objects

### Customer

The ``Customer`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**CreateNew** | String | Yes | Can either be ``IfNoMatch``, ``Always``, or ``Never``. This ensures that a new customer is created if one does not already exist, without a customer being assigned the order will not import
**CompanyClass** | [``DataItem``](#dataitem) | Yes | Which classification this customer is in. All customers **must** have a classification
**Currency** | [``DataItem``](#dataitem) | Yes | What currency this customer purchases items in. All customers **must** have a currency. This cannot be changed once a customer has transactions recorded against them
**OtherRef** | String | | A reference, which is unique for other users
**WebUser** | String | | The username of the user
**CompanyType** | [``DataItem``](#dataitem) | | The company classification, which can be set in the System Data area of Khaos Control Cloud
**CompanyName** | String | | Business/Company name for the customer. If left blank; e.g. for a residential consumer; Khaos Control will generate a company name from the contact details on the account
**WebsiteUrl** | String | | The URL of the customer's website
**SourceCode** | [``DataItem``](#dataitem) | | The sales source that should be imported with the order
**MailingStatus** | [``DataItem``](#dataitem) | | This can either be an ID or value. This goes against the Mailing Flag within Khaos Control Cloud
**TaxReference** | String | | The Tax Reference for the customer, typically a VAT number
**URN** | String | | Unique Reference Number for this customer. Leave this blank unless you know that the customer already exists in Khaos Control with that reference; or, you wish to create a new account and are sure no customer with that URN already exists
**CalculationMethod** | Integer | | Can either be ``0`` for Auto, ``1`` for Gross, or ``2`` for Net. This wil set the default calculation method for the customer for all future order

### Address

The ``Address`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Line1** | String | Yes | First line of the address; usually, although not always, contains house number and road name. Most couriers require this to be populated before a package can be shipped
**Line2** | String | | Second line of the address
**Line3** | String | | Third line of the address. Not all couriers support three address lines, so don't populate this unless needed
**Town** | String | Yes | Address town. Required by Khaos and all couriers
**County** | [``DataItem``](#dataitem) | | County; not generally required for UK addresses, although may be a required part of the address for some overseas addresses
**Postcode** | String | | Address postcode. Technically not required, although the vast majority of countries/couriers require this
**Country** | String | Yes | Country for this address. Each address is associated with a country, which can be different to the main country of the customer record
**Telephone** | String | | Telephone number associated with this address. In most cases, associating a telephone with the contact makes more sense, but this field allows associating a number with the address as a whole - e.g. a telephone number for business
**Fax** | String | | Fax number for this address. Stored by Khaos for reference purposes, although rarely used
**Organization** | String | | Company/Organization name for this address. Seperate from the company placing the order! For example, you may wish to deliver an order to a work address for a different business

### Contact

The ``Contact`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Title** | String | | Title for this contact, e.g. Mr/Mrs/Dr/...
**Forename** | String | | Forename(s) for this contact. Either forname or surname must be filled in (or both, preferably)
**Surname** | String | | Surname(s) for this contact. Either forname or surname must be filled in (or both, preferably)
**Email** | String | | Primary email address for this contact. Can be used to (e.g.) send notifications/updates on order status
**Mobile** | String | | Mobile phone number for this contact
**DateOfBirth** | [``DateTime``](#datetime) | | Date of birth for this contact, if known. May not be relevant to many businesses
**OptInNewsLetter** | Boolean | | Indicates whether this contact has explicity opted into (or out of) receiving newsletters. Leave blank if contact has no explicitily chosen an option and the system will not update any existing preferences against the contact

### SalesOrder

The ``SalesOrder`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Customer** | [``Customer``](#customer) | Yes | An object representing the customer that sales order relates to
**InvoiceAddress** | [``Address``](#address) | Yes | An object representing the invoice address for the sales order
**InvoiceContact** | [``Contact``](#contact) | Yes | An object representing the invoice contact for the sales order
**DeliveryAddress** | [``Address``](#address) | | An object representing the delivery address for the sales order
**DeliveryContact** | [``Contact``](#contact) | | An object representing the delivery contact for the sales order
**Header** | [``OrderHeader``](#orderheader) | Yes | An object representing the order header for the sales order
**Items** | Array[[``OrderItem``](#orderitem)] | Yes | An array of ``OrderItem`` objects, representing the items that are part of the sales order
**Payments** | Array[[``OrderPayment``](#orderpayment)] | | An array of ``OrderPayment`` objects, representing any payments that are part of the sales order

### OrderHeader

The ``OrderHeader`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**AssociatedRef** | String | Yes | Order reference number. This **must** be unique amongst all orders from a given website/source. When sending updates for order status, the ``AssociatedRef`` will be passed back to the source website, so it can tell which order has changed
**OrderDate** | [``DateTime``](#datetime) | Yes | The date the order was **placed**.
**Site** | [``DataItem``](#dataitem) | Yes | Which site/location the order should be fulfilled from
**Agent** | [``DataItem``](#dataitem) | | Which sales agent to attribute the sale to
**Courier** | [``DataItem``](#dataitem) | | Which courier to ship the order with. Note that rules within Khaos Control may override this selection
**CourierGroup** | [``DataItem``](#dataitem) | | Which group of courier services to ship the order with; use this if you want to restrict Khaos Control to shipping via a group/type of courier(s), but allowing it to select which specific courier service to use based on courier rules. For example, a Khaos Control system might have a courier group of "Next Day", which selects from many different next day services depending on package size, weight and destination
**Keycode** | [``DataItem``](#dataitem) | | The keycode to use with this order
**SalesSource** | [``DataItem``](#dataitem) | Yes | The sales source of the order. For example, you could use "WEB". These sources must exist in Khaos Control
**Client** | [``DataItem``](#dataitem) | | 
**Website** | [``DataItem``](#dataitem) | |
**Brand** | [``DataItem``](#dataitem) | | The brand that the order is part of
**InvoicePriority** | [``DataItem``](#dataitem) | | The priority setting for the invoice, which must exist in Khaos Control
**DiscountCode** | Array[[``DataItem``](#dataitem)] | Yes | An array of discount codes for the order. If you want to use the alias codes, you must specify the code value, in which case the name will be ignored. Name is only useful to match on a (non alias) discount code
**OrderNote** | String | | The note for the order
**InvoiceNote** | String | | The invoice note for the order
**DeliveryDate** | [``DateTime``](#datetime) | | Which date the order should be delivered on. If this field isn't locked, Khaos Control may recalculate it based on rules
**RequiredDate** | [``DateTime``](#datetime) | | The latest possible date the customer has indicated the order can arrive. Distinct from ``DeliveryDate``, this field implies the order could arrive earlier, whereas ``DeliveryDate`` implies a specific day the order must arrive on
**PONumber** | String | | Customer's PO (Purchase Order) reference. Usually relevant for business customers paying on account
**DeliveryCharge** | [``Price``](#price) | | Amount charged for delivery. If omitted, the system will calculate delivery (unlikely to be desirable for web orders.) To indicate free delivery, include this field and set either the Net or Gross values to 0.
**RemainderOnAccount** | Boolean | | 
**CalcMethod** | Integer | | Can either be ``0`` for Auto, ``1`` for Gross, or ``2`` for Net. Choose the best option based on the type of customer/order. This can potentially affect the total based on VAT rounding. Generally B2B will use Net calculation, where as B2C orders will use Gross. Note this can be defaulted by the customer's classification, and doesn't need to be set against every individual order
**ValueDiscount** | Double | | Gross discount to apply to the order
**SOrderCode** | String | | This cannot be imported, but is presented when orders are exported
**SOrderType** | [``DataItem``](#dataitem) | | This cannot be imported, but is present when orders are exported

### OrderItem

The ``OrderItem`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**SKU** | String | Yes | The code of the stock item being sold. May not actually be the stock code in Khaos Control; the ``Mapping`` controls how it locates an item in Khaos Control
**Mapping** | [``MappingType``](#mappingtype) | Yes | Controls how the SKU is used to locate a stock item in Khaos Control.
**Quantity** | Double | Yes | How many units of the item were sold. Do not use non-integer quantites unless specifically requested to do by the Khaos Control user
**StockDescription** | [``OrderItemDescription``](#orderitemdescription) | | Specify which description to place against this item; If omitted, the standard description against the stock item is used.
**ExtendedDescription** | Array[String] | Yes | Additional lines of description for the order item; for example, additional instructions/requests, or a gift message
**FreeItemReason** | [``DataItem``](#dataitem) | | If the item is free (zero price), a reason can be provided specifying why. Only set if requested to by the Khaos Control user
**ImportRef** | String | | Optional item reference from the website/source. Will be passed back in any future order updates
**WebItemRef** | String | | Second item reference from the website/source. Will be passed back in any future order updates
**Site** | [``DataItem``](#dataitem) | | The site that this item will be fulfilled from. Usually this isn't specified, and the site recorded against the entire order is used
**PackLink** | String | | 
**UnitPrice** | [``Price``](#price) | | Unit price, i.e. price for a single item. If omitted, system will calculate price; unlikely to be relevant for a website order
**PercentDiscount** | Double | | Percentage discount to apply to the line. If specified, the unit price should be the price **before** discount.
**MappingItem** | String | | If the mapping type is ``Barcode``, sets which barcode type to search in
**AlternateMapping** | Array[[``ItemMapping``](#itemmapping)] | | If the primary mapping fails to find a stock item, you can specify fall-back mappings to attempt

### OrderItemDescription

The ``OrderItemDescription`` object is similar to a [``DataItem``](#dataitem) but differs between ``XML`` and ``JSON`` outputs (see below).

Name | Type | Required | Description
--- | --- | --- | ---
**Source** | String | Yes | The source of the stock description. This can be either:<br/>**Explicit** (uses the description set against this line),<br/>**StockDesc** (this is normally the best fit),<br/>**WebCategories** (which set of categories to look up a description in)
**Parameter** | String | Yes | The description of the stock item

#### XML

```xml
<OrderItemDescription Source="Explicit">Croquet set - Luxury 4 Player - Oxford - Jaques</StockDescription>
```

#### JSON

```json
"OrderItemDescription": {
   "Source": "Explicit",
   "Parameter": "Croquet set - Luxury 4 Player - Oxford - Jaques"
},
```

### ItemMapping

The ``ItemMapping`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Mapping** | [``MappingType``](#mappingtype) | Yes | Select how to map the stock code from the website/source to a stock code in Khaos Control Cloud
**MappingItem** | String | |  If the mapping type is Barcode, sets which barcode type to search in.

### OrderPayment

The ``OrderPayment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Amount** | Double | Yes | The amount paid in this transaction
**Cash** or <br/>**Cheque** or <br/>**Card** or<br/>**Voucher** | [``CashPayment``](#cashpayment)<br/>[``ChequePayment``](#chequepayment)<br/>[``CardPayment``](#cardpayment)<br/>[``VoucherPayment``](#voucherpayment) | Yes | What type of transaction was used when making the payment
**BankAccount** | [``DataItem``](#dataitem) | | Which bank account in Khaos Control to record this payment against

### CashPayment

The ``CashPayment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Reference** | string | | Payment reference, if you have a relevant payment reference to provide

### ChequePayment

The ``ChequePayment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Reference** | string | | Payment reference, if you have a relevant payment reference to provide

### VoucherPayment

The ``VoucherPayment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Reference** | string | Yes | Voucher reference. This is required so Khaos Control Cloud can match the payment against its list of issued vouchers

### CardPayment

The ``CardPayment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**CardType** | String | Yes | The type of the credit card, e.g. VISA, AMEX. If not known, then specify the payment service to authorized the card
**IsPreAuth** | Boolean | Yes | Specify whether the card payment is a pre-authorization, or a full authorization. Pre-authorization reserves the amount against the card, whereas full authorization will take payment
**CardNumber** | String | | Credit card number. In most situations, this won't be required
**CardStart** | String | | Start date, if present. This is in the format of MMYY; e.g. 1118
**CardExpire** | String | | Expiry date, if present. This is in the format of MMYY; e.g. 1118
**CardCV2** | String | | Signature digits, 3 or 4 digits depending on card type. Should not be retained or sent if authorization has already taken place!
**CardHolder** | String | | Card holders name. For example; Mr. Joe Bloggs
**CardIssue** | String | | Card issue number
**AuthCode** | String | | Autorization code. If payment has been taken or reserved, you **must** pass a value in this field. If you don't have access to the actual authoization code, then pass whatever reference you do have. If this field is blank, Khaos Control will regard this payment as not authorized
**TransactionID** | String | | An ID for transaction reference
**AAVCV2Result** | String | | The result of the CV2 check
**SecurityToken** | String | | The security ref or token given to you by a payment provider
**Last4Digits** | String | | The last four digits of the card number, for reference
**AccountNumber** | Integer | | Which card integration account to use in Khaos Control. If omitted, system will pick default based on currency or other rules
**FraudData** | String | | Fraud data, for reference
**Timestamp** | [``DateTime``](#datetime) | | The time the payment was processed

### Price

The ``Price`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Net** | Double | | The price in **net** format
**Gross** | Double | | The price in **gross** format

### OrderImportConfig

The ``OrderImportConfig`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**MatchCompanyOn** | String | Yes | Is used for matching existing customers, they can be:<br/>-  CompanyName<br/>- Address1<br/>- Address2<br/>- Address3<br/>- Town<br/>- Postcode<br/>- Surname<br/>- Forename<br/>- Telephone<br/>- Email<br/>- CompanyCode<br/>- UseDeliveryAddress
**MatchAddressOn** | String | Yes | Is used for matching existing addresses against the customer, they can be:<br/>- CompanyName<br/>- Address1<br/>- Address2<br/>-Address3<br/>- Town<br/>- Postcode<br/>- Surname<br/>- Forename<br/>- Telephone<br/>- Email<br/>- CompanyCode<br/>- UseDeliveryAddress
**MatchContactOn** | String | Yes | Is used for matching existing contacts against the customer, they can be:<br/>- CompanyName<br/>- Address1<br/>- Address2<br/>-Address3<br/>- Town<br/>- Postcode<br/>- Surname<br/>- Forename<br/>- Telephone<br/>- Email<br/>- CompanyCode<br/>- UseDeliveryAddress
**DiscontinuedItems** | String | | Can either be:<br/>- Abort<br/>- ImportAndHold<br/>- Skip (not recommended)
**RunToZeroErrorItems** | String | | Can either be:<br/>- Abort<br/>- ImportAndHold<br/>- Skip (not recommended)
**ImportAsUnconfirmed** | Boolean | | Sets whether or not the order is imported as unconfirmed or confirmed. If unconfirmed, the order is not ready for processing

There are slight differences between the ``XML`` and ``JSON`` outputs, these are as follows:

#### XML

```xml
<Config>
    <MatchCompanyOn>Address1</MatchCompanyOn>
    <MatchCompanyOn>CompanyCode</MatchCompanyOn>
    <MatchAddressOn>Address1</MatchAddressOn>
    <MatchAddressOn>Postcode</MatchAddressOn>
    <MatchContactOn>Surname</MatchContactOn>
</Config>
```

#### JSON

```json
"config": {
   "MatchCompanyOn": ["Address1", "CompanyCode"],
   "MatchAddressOn": ["Address1", "Postcode"],
   "MatchContactOn": ["Surname"]
}
```

### SalesOrderStatus

The ``SalesOrderStatus`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**OrderCode** | String | Yes | The sales order code
**OrderID** | String | Yes | The ID of the order, which will always be unique
**OrderStatus** | [``OrderStatus``](#orderstatus) | Yes | The status of the shipment
**AssociatedRef** | String | | An associated reference to the Sales Order
**ChannelID** | String | | The channel ID of the Sales Order
**Shipments** | [``Shipment``](#shipment) | Yes | The shipment items of the order, this can either be the whole order or part

### Shipment

The ``Shipment`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**ID** | String | Yes | The ID of the shipment
**Code** | String | Yes | The code of the shipment, which may change from the user interaction
**Status** | [``ShipmentStatus``](#shipmentstatus) | Yes | The status of the shipment
**Items** | Array[[``ShipmentItem``](#shipmentitem)] | Yes | A list of ``ShipmentItem`` being shipped
**Packages** | Array[[``ShipmentPackage``](#shipmentpackage)] | Yes | A list of ``ShipmentPackage`` for the items being shipped

### ShipmentItem

The ``ShipmentItem`` object is made up of the following properties:

Name | Type | Requried | Description
--- | --- | --- | ---
**OrderItemID** | String | Yes | The item ID of the order
**ShipmentItemID** | String | Yes | The ID of the item in the shipment
**SKU** | String | Yes | The SKU of the item
**Quantity** | Double | Yes | The quantity of the item being shipped
**ImportRef** | String | | 
**WebsiteItemRef** | String | | 

### ShipmentPackage

The ``ShipmentPackage`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**ConsignmentRef** | String | | The Consignment Ref of the package
**Courier** | [``DataItem``](#dataitem) | | Either the ID, Code or Name of the courier
**ShipmentDate** | [``DateTime``](#datetime) | | The date of the shipment

### StockStatus

The ``StockStatus`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**StockCode** | String | Yes | This is the Stock Code from within Khaos Control Cloud
**StockID** | string | Yes | This is the StockID from within Khaos Control Cloud
**SiteID** | Integer | Yes | This is the SiteID from within Khaos Control Cloud that any levels refer to. If Khaos contains more than one stock control site (e.g. multiple warehouses), then each ``StockStatus`` entry refers to the stock present in one specific site
**Levels**  | [``StockLevels``](#stocklevels) | | This contains the current stock levels of the item. The levels will not be present if the item isn't stock controlled
**BuildPotential** | [``BuildPotentials``](#buildpotentials) | | If the stock item is a build item, then this specifies what quantity could theoretically be built. If the item is "out of stock", but has a non-zero build potential, you may wish to mark it as ``Available`` - since it's possible more could be constructed from other stock items that are themselves available

### StockLevels

The ``StockLevels`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**Available** | Float | Yes | This is the total in stock that is not assigned to orders already, and could potentially be ordered
**Courier** | Float | Yes | This is the total quantity of stock for which purchase orders have been placed, and not yet received

### BuildPotentials

The ``BuildPotentials`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**FromChildren** | Integer | Yes | This is the quantity that could be built from child (component) items. Note that other items might use the same child items, so this is the maximum quantity that could be built assuming no other build items or orders used the children
**FromParents** | Integer | Yes | This is the quantity of items that could be produced if all parent items containing this were broken down into their component parts

### StockItem

The ``StockItem`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**StockID** | String | Yes | The ID of the stock item, which is always unique
**StockCode** | String | Yes | The stock code, which can change
**StockDescription** | string | Yes | A brief description of the stock item, normally used as a name
**BuyPrice** | [``Price``](#price) | Yes | The general purchase price of the item, e.g. The cost of the item from the supplier
**SellPrice** | [``Price``](#price) | Yes | The general selling price of the item to a customer
**TaxRate** | [``DataItem``](#dataitem) | Yes | The tax type of the item, e.g. Zero Tax, Standard Tax, Fixed Tax, etc.
**StockType1** | [``DataItem``](#dataitem) | Yes | The overall type of the stock item, e.g. Clothing, Electronics
**StockType2** | [``DataItem``](#dataitem) | Yes | The secondary type of the stock item within Stock Type 1, e.g. Computing, Jumpers, Televisions
**StockType3** | [``DataItem``](#dataitem) |  | The third category of the stock item within Stock Type 2, e.g. Keyboards, Wool, LED
**StockType4** | [``DataItem``](#dataitem) |  | The fourth category of the stock item within the Stock Type 3, e.g. 48" screen, Ergonomic keyboard
**Options** | [``StockOptions``](#stockoptions) | Yes | Options regarding the stock item, e.g. Discontinued, Run to Zero
**OtherRef** | String | | An alternative reference to the stock item
**LongDescription** | String | | A longer, more in-depth description of the stock item
**EposDescription** | String | | An EPOS description for the stock item
**Manufacturer** | [``DataItem``](#dataitem) | | The manufacturer of the stock item
**AverageWeight** | Float | | The weight of the stock item, on average
**Height** | Float | | The height dimension of the stock item
**Width** | Float | | The width dimension of the stock item
**Depth** | Float | | The depth dimension of the stock itme
**ReorderMultiple** | Float | | The reordering multiple of the stock item, e.g. re-ordering an item with your supplier where they come in packs of 100, this value would be set to 100
**MinLevel** | Float | | The minimum level of stock before a reorder must be actioned
**SafeLevel** | Float | | The level of stock where a reorder needs to be considered
**SalesMultiple** | Float | | This will force customers to only order in multiples of this quantity
**LeadTime** | Integer | | The amount of lead time between a reorder and delivery of stock (in days)
**Availability** | String | | A free text field to indicate the availability of the item. For example, if the level was zero, this could say "Expected back in stock mid-December"
**WebProperties** | [``WebProperties``](#webproperties) | | See ``WebProperties`` object
**SupplierInfo** | [``StockSupplier``](#stocksupplier) | Yes | 
**Images** | Array[[``StockImage``](#stockimage)] | Yes | 
**Barcodes** | Array[[``StockBarcode``](#stockbarcode)] |

### StockOptions

The ``StockOptions`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**PublishOnWeb** | Boolean | Yes | If the option to "Publish to web" is enabled or not
**Discontinued** | Boolean | Yes | If the item is discontinued or not
**DropShipItem** | Boolean | Yes | If the item is dropship or not
**DiscountsDisabled** | Boolean | Yes | If the item can have discounts or not
**RunToZero** | Boolean | Yes | If the item can run to zero availability or not
**VatReliefQualified** | Boolean | Yes | If the item is VAT relief qualified or not
**StockControlled** | Boolean | Yes | If the item is stock controlled in the system or not (or if it's a non-physical item)
**FreeText** | Boolean | Yes | If the item supports free text and therefore can have an alternative stock description

### WebProperties

The ``WebProperties`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**WebsitePrice** | [``Price``](#price) | | The price for the website only
**WebTeaser** | String | | A text field that can be used for showing on the website
**MetaTitle** | String | | The Meta Title for the website stock page
**MetaDescription** | String | | The Meta Description for the website stock page
**MetaKeywords** | String | | The Meta Keywords for the website stock page
**MetaDisplayQty** | Integer | | The maximum quantity to display on the website

### StockSupplier

The ``StockSupplier`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**URN** | String | Yes | The unique reference of the supplier
**Name** | String | Yes | The company name of the supplier
**IsPreferred** | Boolean | | If this supplier is the preferred supplier for reordering this item
**SupplierRef** | String | | An optional reference that the supplier uses for that stock item

### StockImage

The ``StockImage`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
Name | String | | The name of the image
Description | String | | The description of the image
Filename | String | | The filename of the image
ImageType | [``DataItem``](#dataitem) | | The type of image

### StockBarcode

The ``StockBarcode`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ----
**Barcode** | String | Yes | The barcode value
**Type** | [``DataItem``](#dataitem) | | The type of barcode e.g. ISBN, EAN

### DeletedItem

The ``DeletedItem`` object is made up of the following properties:

Name | Type | Required | Description
--- | --- | --- | ---
**StockID** | String | Yes | The ID of the stock item

# Receiving &amp; Responding to server calls

## Order Download
> **GET** http://playground.khaoscloud.com/orders.php

This is defined as your ``OrderDownload`` object within your Configuration file. The endpoint (URL) you specify will be called upon frequently to gain orders to import. The output of this URL should be either ``XML`` or ``JSON``.

### XML

#### Properties
Node | Child Node | Type | Required | Description
--- | --- | --- | --- | ---
**SalesOrderImport** | | | Yes | The root node of the XML file
| | **Orders** | Array[[``SalesOrder``](#salesorder)] | Yes | A parent node containing all of the sales orders
| | **ApiVersion** | Integer | Yes | Must be set to **1000**
| | **Config** | [``OrderImportConfig``](#orderimportconfig) | | The config options to be used with this import

#### Response

```xml
<SalesOrderImport>
   <Orders>
       <SalesOrder>
           <Customer>
               <CreateNew>IfNoMatch</CreateNew>
               <CompanyClass ID="55" />
               <Currency Code="GBP" />
               <CompanyName>Mr Training</CompanyName>
               <MailingStatus ID="5" />
           </Customer>
           <InvoiceAddress>
               <Line1>1 3 Priest Court</Line1>
               <Line2>Caunt Road</Line2>
               <Town>Grantham</Town>
               <Postcode>NG31 7FZ</Postcode>
               <Country Code="GB" />
               <Organisation>Khaos Control Solutions Ltd</Organisation>
           </InvoiceAddress>
           <InvoiceContact>
               <Forename>Mr</Forename>
               <Surname>Training</Surname>
               <Email>p6wnc9r4b1f7mt7@marketplace.amazon.co.uk</Email>
           </InvoiceContact>
           <Header>
               <AssociatedRef>AZ202-1780416-1717150</AssociatedRef>
               <OrderDate>2017-04-28T05:57:14</OrderDate>
               <Site ID="10" />
               <Courier ID="14" />
               <SalesSource ID="3" />
               <DiscountCodes/>
               <DeliveryCharge>
                   <Gross>0</Gross>
               </DeliveryCharge>
           </Header>
           <Items>
               <OrderItem>
                   <SKU>OX</SKU>
                   <Mapping>OtherRef</Mapping>
                   <Quantity>1</Quantity>
                   <StockDescription Source="Explicit">Croquet set - Luxury 4 Player - Oxford - Jaques</StockDescription>
                   <ImportRef>00721570624763</ImportRef>
                   <UnitPrice>
                       <Gross>0.01</Gross>
                   </UnitPrice>
                   <AlternateMapping>
                       <ItemMapping>
                           <Mapping>OtherRef</Mapping>
                       </ItemMapping>
                   </AlternateMapping>
               </OrderItem>
               <OrderItem>
                   <SKU>MUGSM</SKU>
                   <Mapping>OtherRef</Mapping>
                   <Quantity>1</Quantity>
                   <StockDescription Source="Explicit">
                       Sophie Allport Fine Bone China Mug - Black Cat &amp; Bones (comes boxed) (Standard (275ml))
                   </StockDescription>
                   <ImportRef>35215487482515</ImportRef>
                   <UnitPrice>
                       <Gross>0.01</Gross>
                   </UnitPrice>
                   <AlternateMapping>
                       <ItemMapping>
                           <Mapping>OtherRef</Mapping>
                       </ItemMapping>
                   </AlternateMapping>
               </OrderItem>
           </Items>
           <Payments>
               <OrderPayment>
                   <Amount>0.02</Amount>
                   <Card>
                       <CardType>Amazon</CardType>
                       <IsPreauth>False</IsPreauth>
                       <AuthCode>AMAZON</AuthCode>
                   </Card>
               </OrderPayment>
           </Payments>
       </SalesOrder>
       <SalesOrder>
           <Customer>
               <CreateNew>IfNoMatch</CreateNew>
               <CompanyClass ID="55" />
               <Currency Code="GBP" />
               <CompanyName>Mr Training</CompanyName>
               <MailingStatus ID="5" />
           </Customer>
           <InvoiceAddress>
               <Line1>1 3 Priest Court</Line1>
               <Line2>Caunt Road</Line2>
               <Town>Grantham</Town>
               <Postcode>NG31 7FZ</Postcode>
               <Country Code="GB" />
               <Telephone>08452575111</Telephone>
               <Organisation>Khaos Control Solutions Ltd</Organisation>
           </InvoiceAddress>
           <InvoiceContact>
               <Forename>Mr</Forename>
               <Surname>Training</Surname>
               <Email>p6wnc9r4b1f7mt7@marketplace.amazon.co.uk</Email>
           </InvoiceContact>
           <Header>
               <AssociatedRef>AZ206-9902383-0316308</AssociatedRef>
               <OrderDate>2017-05-17T12:56:18</OrderDate>
               <Site ID="10" />
               <Courier ID="14" />
               <SalesSource ID="3" />
               <DiscountCodes/>
               <DeliveryCharge>
                   <Gross>0</Gross>
               </DeliveryCharge>
           </Header>
           <Items>
               <OrderItem>
                   <SKU>BOLDONBA</SKU>
                   <Mapping>OtherRef</Mapping>
                   <Quantity>1</Quantity>
                   <StockDescription Source="Explicit">
                       Devoted2Home Boldon Budget Bedroom Furniture with Narrow Chest of 5 Drawers
                   </StockDescription>
                   <ImportRef>21948977016403</ImportRef>
                   <UnitPrice>
                       <Gross>0.01</Gross>
                   </UnitPrice>
                   <AlternateMapping>
                       <ItemMapping>
                           <Mapping>OtherRef</Mapping>
                       </ItemMapping>
                   </AlternateMapping>
               </OrderItem>
           </Items>
           <Payments>
               <OrderPayment>
                   <Amount>0.01</Amount>
                   <Card>
                       <CardType>Amazon</CardType>
                       <IsPreauth>False</IsPreauth>
                       <AuthCode>AMAZON</AuthCode>
                   </Card>
               </OrderPayment>
           </Payments>
       </SalesOrder>
       <SalesOrder>
           <Customer>
               <CreateNew>IfNoMatch</CreateNew>
               <CompanyClass ID="5" />
               <Currency Code="GBP" />
           </Customer>
           <InvoiceAddress>
               <Line1>141 HORESEDGE STREET</Line1>
               <Line2/>
               <Town>OLDHAM</Town>
               <Postcode>OL1 3DU</Postcode>
               <Country Code="GB" />
               <Telephone>7487752128</Telephone>
           </InvoiceAddress>
           <InvoiceContact>
               <Forename>GEORGIA</Forename>
               <Surname>POLLITT</Surname>
               <Email>georgia13121@msn.com</Email>
           </InvoiceContact>
           <Header>
               <AssociatedRef>EB112487714881-1688638562001</AssociatedRef>
               <OrderDate>2017-07-23T13:36:55</OrderDate>
               <Site ID="1" />
               <Courier ID="2" />
               <SalesSource ID="13" />
               <Brand ID="1559" />
               <DiscountCodes/>
               <DeliveryCharge>
                   <Gross>0</Gross>
               </DeliveryCharge>
           </Header>
           <Items>
               <OrderItem>
                   <SKU>CER-SERV</SKU>
                   <Mapping>StockCode</Mapping>
                   <Quantity>1</Quantity>
                   <StockDescription Source="Explicit">
                       Ceramic serving dishes, side dishes, party dishes, tapas dishes[Heart]
                   </StockDescription>
                   <WebItemRef>112487714881-1688638562001</WebItemRef>
                   <UnitPrice>
                       <Gross>8.99</Gross>
                   </UnitPrice>
                   <AlternateMapping>
                       <ItemMapping>
                           <Mapping>Barcode</Mapping>
                           <MappingItem>4</MappingItem>
                       </ItemMapping>
                       <ItemMapping>
                           <Mapping>StockCode</Mapping>
                       </ItemMapping>
                   </AlternateMapping>
               </OrderItem>
           </Items>
           <Payments>
               <OrderPayment>
                   <Amount>8.99</Amount>
                   <Card>
                       <CardType>Card</CardType>
                       <IsPreauth>False</IsPreauth>
                       <AuthCode>EBAY</AuthCode>
                       <TransactionID>112487714881-1688638562001</TransactionID>
                   </Card>
               </OrderPayment>
           </Payments>
       </SalesOrder>
   </Orders>
   <ApiVersion>1000</ApiVersion>
   <Config>
       <MatchCompanyOn>Address1</MatchCompanyOn>
       <MatchCompanyOn>CompanyCode</MatchCompanyOn>
       <MatchAddressOn>Address1</MatchAddressOn>
       <MatchAddressOn>Postcode</MatchAddressOn>
       <MatchContactOn>Surname</MatchContactOn>
   </Config>
</SalesOrderImport>
```

### JSON

#### Properties

Object | Type | Required | Description
--- | --- | --- | ---
**Orders** | Array[[``SalesOrder``](#salesorder)] | Yes | An array containing ``SalesOrder`` objects. If you have a high volume of orders you may want to split your orders out into 1000 at a time using [``DataContinuity``](#data-continuity).
**ApiVersion** | Integer | Yes | Must be set to **1000**
**Config** | [``OrderImportConfig``](#orderimportconfig) | | The config options to be used with this import

#### Response
```json
{
   "Orders": [{
      "Customer": {
         "CreateNew": "IfNoMatch",
         "CompanyClass": {
            "ID": "5"
         },
         "Currency": {
            "Code": "GBP"
         },
         "CompanyName": "Toma",
         "MailingStatus": {
            "ID": ""
         },
         "URN": "Amazo-0118"
      },
      "InvoiceAddress": {
         "Line1": "34 Blenheim Road",
         "Town": "LONDON",
         "Postcode": "E17 6HS",
         "Country": {
            "Code": "GB"
         },
         "Telephone": "07920405926",
         "Organisation": "Toma Vaitiekute"
      },
      "InvoiceContact": {
         "Surname": "Toma",
         "Email": "3ff90pf51cg8kk4@marketplace.amazon.co.uk"
      },
      "Header": {
         "AssociatedRef": "026-0800930-3625904AZ",
         "OrderDate": "2018-01-16T13:18:04",
         "Site": {
            "ID": "1"
         },
         "Courier": {
            "ID": ""
         },
         "SalesSource": {
            "ID": ""
         },
         "DiscountCodes": [],
         "DeliveryCharge": {
            "Gross": 0.0
         }
      },
      "Items": [{
         "SKU": "B0714PRW7R",
         "Mapping": "StockCode",
         "Quantity": 1.0,
         "StockDescription": {
            "Source": "Explicit",
            "Parameter": "Fun Maze Pen Pens Novelty Puzzle Ballpoint Labyrinth Office School Party Bag Gift"
         },
         "ExtendedDescription": [],
         "ImportRef": "07758421861003",
         "UnitPrice": {
            "Gross": 2.1
         },
         "AlternateMapping": [
            {
               "Mapping": "Barcode",
               "MappingItem": "1"
            },
            {
               "Mapping": "StockCode"
            },
            {
               "Mapping": "Barcode",
               "MappingItem": "1"
            }
         ]
      }],
      "Payments": [{
         "Amount": 2.1,
         "Card": {
            "CardType": "Amazon",
            "IsPreauth": false,
            "AuthCode": "AMAZON"
         }
      }]
   }],
   "ApiVersion": "10000",
   "Config": {
      "MatchCompanyOn": ["CompanyCode"],
      "MatchAddressOn": ["Postcode"],
      "MatchContactOn": ["Surname"]
   }
}
```

## Order Status Upload

Defined as ``OrderStatusUpload`` in your ``configuration file``, the API will push information as a ``POST`` to the endpoint in your desired format. This will occur when an order has been moved through the Sales Invoice Manager in Khaos Control Cloud, and it will only send the statuses for the orders that it has downloaded from the web service.

### XML

#### Properties

Node | Child Node | Type | Always present? | Description
--- | --- | --- | --- | ---
**OrderStatuses** | | | Yes | The root node of the XML file
| | **Statuses** | Array[[``SalesOrderStatus``](#salesorderstatus)] | Yes | A parent node containing all of the sales order statuses

#### Request

```xml
<OrderStatuses>
   <Statuses>
      <SalesOrderStatus>
         <OrderCode>SO02075</OrderCode>
         <OrderID>2075</OrderID>
         <Status>Received</Status>
         <AssociatedRef>web_order_1516288492</AssociatedRef>
         <ChannelID>21</ChannelID>
         <Shipments>
            <Shipment>
               <ID>BFDPDD09FZZW7ZZ</ID>
               <Code>INV106</Code>
               <Status>Staging</Status>
               <Items>
                  <ShipmentItem>
                     <OrderItemID>BFDPDC00ZZZW7ZZ</OrderItemID>
                     <ShipmentItemID>BFDPDD09ZZZW7ZZ</ShipmentItemID>
                     <SKU>MUG1</SKU>
                     <Quantity>2.0</Quantity>
                  </ShipmentItem>
                  <ShipmentItem>
                     <OrderItemID>BFDPDC01FZZW7ZZ</OrderItemID>
                     <ShipmentItemID>BFDPDD0AFZZW7ZZ</ShipmentItemID>
                     <SKU>MUG1</SKU>
                     <Quantity>1.0</Quantity>
                  </ShipmentItem>
               </Items>
               <Packages>
                  <ShipmentPackage>
                     <Courier Code="24H" ID="76">Metapack 24Hr</Courier>
                  </ShipmentPackage>
               </Packages>
            </Shipment>
         </Shipments>
      </SalesOrderStatus>
      <SalesOrderStatus>
         <OrderCode>SO02076</OrderCode>
         <OrderID>2076</OrderID>
         <Status>Received</Status>
         <AssociatedRef>web_order_1516288490</AssociatedRef>
         <ChannelID>21</ChannelID>
         <Shipments>
            <Shipment>
               <ID>BFDPDD02ZZZW7ZZ</ID>
               <Code>INV105</Code>
               <Status>Staging</Status>
               <Items>
                  <ShipmentItem>
                     <OrderItemID>BFDPDC00FZZW7ZZ</OrderItemID>
                     <ShipmentItemID>BFDPDD03FZZW7ZZ</ShipmentItemID>
                     <SKU>MUG1</SKU>
                     <Quantity>1.0</Quantity>
                  </ShipmentItem>
               </Items>
               <Packages>
                  <ShipmentPackage>
                     <Courier Code="24H" ID="76">Metapack 24Hr</Courier>
                  </ShipmentPackage>
               </Packages>
            </Shipment>
         </Shipments>
      </SalesOrderStatus>
   </Statuses>
</OrderStatuses>
```

### JSON

#### Properties

Object | Type | Always present? | Description
--- | --- | --- | ---
**Statuses** | Array[[``SalesOrderStatus``](#salesorderstatus)] | Yes | An array containing ``SalesOrderStatus`` objects.

#### Request

```json
{
   "Statuses": [
   {
      "OrderCode": "SO02075",
      "OrderID": "2075",
      "Status": "Received",
      "AssociatedRef": "web_order_1516288492",
      "ChannelID": "21",
      "Shipments": [{
         "ID": "BFDPDD09FZZW7ZZ",
         "Code": "INV106",
         "Status": "Staging",
         "Items": [{
            "OrderItemID": "BFDPDC00ZZZW7ZZ",
            "ShipmentItemID": "BFDPDD09ZZZW7ZZ",
            "SKU": "MUG1",
            "Quantity": 2.0
         },
         {
            "OrderItemID": "BFDPDC01FZZW7ZZ",
            "ShipmentItemID": "BFDPDD0AFZZW7ZZ",
            "SKU": "MUG1",
            "Quantity": 1.0
         }],
         "Packages": [{
            "Courier": {
               "Name": "Metapack 24Hr",
               "Code": "24HR",
               "ID": "76"
            }
         }]
      }]
   },
   {
      "OrderCode": "SO02076",
      "OrderID": "2076",
      "Status": "Received",
      "AssociatedRef": "web_order_1516288490",
      "ChannelID": "21",
      "Shipments": [{
         "ID": "BFDPDD02ZZZW7ZZ",
         "Code": "INV105",
         "Status": "Staging",
         "Items": [{
            "OrderItemID": "BFDPDC00FZZW7ZZ",
            "ShipmentItemID": "BFDPDD03FZZW7ZZ",
            "SKU": "MUG1",
            "Quantity": 1.0
         }],
         "Packages": [{
            "Courier": {
               "Name": "Metapack 24Hr",
               "Code": "24HR",
               "ID": "76"
            }
         }]
      }]
   }]
}
```

## Stock Upload

*Coming soon*

## Stock Status Upload

Defined as ``StockStatusUpload`` in your ``configuration file``, the API will ``POST`` a request to your endpoint in the data format you specified. This will occur when a stock adjustment is made, or the level of stock is changed automatically. You do not need to respond to this request, and you will receive between 0 and 100 stock items per request.

### XML

#### Properties

Node | Child Node | Type | Always present? | Description
--- | --- | --- | --- | ---
**StockStatuses** | | | Yes | The root node of the XML file
| | **Statuses** | Array[[``StockStatus``](#stockstatus)] | Yes | A parent node containing all of the stock item statuses

#### Request
```xml
<?xml version="1.0"?>
<StockStatuses xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <Statuses>
    <StockStatus>
      <StockCode>3SPIOAKDAR - 5.75</StockCode>
      <StockID>39681</StockID>
      <SiteID>16</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-4PRSCOR507</StockCode>
      <StockID>39680</StockID>
      <SiteID>1</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-4PRSCOR507</StockCode>
      <StockID>39680</StockID>
      <SiteID>5</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-4PRSCOR507</StockCode>
      <StockID>39680</StockID>
      <SiteID>13</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-4PRSCOR507</StockCode>
      <StockID>39680</StockID>
      <SiteID>8</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-4PRSCOR507</StockCode>
      <StockID>39680</StockID>
      <SiteID>16</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-3PRSCOR506</StockCode>
      <StockID>39679</StockID>
      <SiteID>1</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-3PRSCOR506</StockCode>
      <StockID>39679</StockID>
      <SiteID>5</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-3PRSCOR506</StockCode>
      <StockID>39679</StockID>
      <SiteID>13</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
    <StockStatus>
      <StockCode>SAM1-3PRSCOR506</StockCode>
      <StockID>39679</StockID>
      <SiteID>8</SiteID>
      <Levels>
        <Available>0</Available>
        <OnOrder>0</OnOrder>
      </Levels>
    </StockStatus>
  </Statuses>
</StockStatuses>
```

### JSON

#### Properties

Object | Type | Always present? | Description
--- | --- | --- | ---
**Statuses** | Array[[``StockStatus``](#stockstatus)] | Yes | An array containing all of the stock item statuses

#### Request

```json
{
   "Statuses": [{
      "StockCode": "BOO",
      "StockID": "112",
      "SiteID": 1,
      "Levels": {
         "Available": 77.0,
         "OnOrder": 84.0
      }
   }, {
      "StockCode": "MUG1",
      "StockID": "505",
      "SiteID": 1,
      "Levels": {
         "Available": 110.0,
         "OnOrder": 0.0
      }
   }]
}
```
