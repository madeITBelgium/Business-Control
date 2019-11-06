# Business Control PHP (Laravel)
[![Build Status](https://travis-ci.org/madeITBelgium/Business-Control.svg?branch=master)](https://travis-ci.org/madeITBelgium/Business-Control)
[![Coverage Status](https://coveralls.io/repos/github/madeITBelgium/Business-Control/badge.svg?branch=master)](https://coveralls.io/github/madeITBelgium/Business-Control?branch=master)
[![Latest Stable Version](https://poser.pugx.org/madeITBelgium/Business-Control/v/stable.svg)](https://packagist.org/packages/madeITBelgium/Business-Control)
[![Latest Unstable Version](https://poser.pugx.org/madeITBelgium/Business-Control/v/unstable.svg)](https://packagist.org/packages/madeITBelgium/Business-Control)
[![Total Downloads](https://poser.pugx.org/madeITBelgium/Business-Control/d/total.svg)](https://packagist.org/packages/madeITBelgium/Business-Control)
[![License](https://poser.pugx.org/madeITBelgium/Business-Control/license.svg)](https://packagist.org/packages/madeITBelgium/Business-Control)

With this Laravel package you can interact with the BusinessControl.be CRM.

# Installation

Require this package in your `composer.json` and update composer.

```php
"madeitbelgium/business-control": "^0.1"
```

# Documentation
## Usage
```php
use MadeITBelgium\BusinessControl\Facade\BusinessControl;

$companyId = 1; //BusinessControl Company ID

//Search client
$clients = BusinessControl::client()->searchByVat('BE0653.855.818', $companyId);
 
 $clients = BusinessControl::client()->searchByEmail('info@madeit.be', $companyId);

//create client
$client = BusinessControl::client()->create([
    'name' => 'Name',
    'company_id' => $companyId,
    'team_id' => null,
    'client_group_id' => null,
    'contact_name' => $name,
    'phone' => $phone,
    'contact_phone' => null,
    'email' => $email,
    'invoice_email' => $email,
    'contact_email' => $email,
    'street_name' => $street,
    'street_number' => null,
    'postal_code' => $postcode,
    'postal_name' => $city,
    'country' => $country,
    'vat' => $vat
]);
if($client->success) {
    $clientId = $client->client->id;
}

//Create invoice
$invoiceNumber = BusinessControl::invoice()->nextInvoiceNumber(date('Y'), $companyId);
$newNumber = "";
if($invoiceNumber->prefix !== null) {
    $newNumber = $invoiceNumber->prefix;
}
$number = $invoiceNumber->next;
if($invoiceNumber->length !== null) {
    $number = str_pad($number, $invoiceNumber->length, "0", STR_PAD_LEFT);
}
$newNumber = $newNumber . $number;
            
$invoice = BusinessControl::invoice()->create([
     'company_id' => $companyId,
    'team_id' => null,
    'client_id' => $clientId,
    'title' => "Made I.T. subscription",
    'description' => "",
    'invoice_number' => $newNumber,
    'invoice_date' => Carbon::now()->format('Y-m-d'),
    'invoice_sent' =>  Carbon::now()->format('Y-m-d'),
    'invoice_expire' => Carbon::now()->addDays(14)->format('Y-m-d'),
    'invoice_approved' => Carbon::now()->format('Y-m-d'),
    'invoice_rejected' => null,
    'invoice_completed' => Carbon::now()->format('Y-m-d'),
    'invoice_lines' => [
        [
            'name' => 'Made I.T. subscription',
            'amount' => $period,
            'vat' => $vat ? 21 : 0,
            'unit_price_excl' => $vat ? ($monthPrice/1.21) : $monthPrice,
            'unit_price_vat' => $vat ? ($monthPrice - $monthPrice/1.21) : 0,
            'unit_price_incl' => $monthPrice,
            'total_price_excl' => $vat ? ($totalPrice/1.21) : $totalPrice,
            'total_price_vat' => $vat ? ($totalPrice - $totalPrice/1.21) : 0,
            'total_price_incl' => $totalPrice,
        ]
    ]
]);

if($invoice->success) {
    $invoiceId = $invoice->invoice->id;
    BusinessControl::invoice()->updateStatus($invoiceId, 5);
}
```

The complete documentation can be found at: [http://www.madeit.be/](http://www.madeit.be/)

# Support

Support github or mail: tjebbe.lievens@madeit.be

# Contributing

Please try to follow the psr-2 coding style guide. http://www.php-fig.org/psr/psr-2/
# License

This package is licensed under LGPL. You are free to use it in personal and commercial projects. The code can be forked and modified, but the original copyright author should always be included!