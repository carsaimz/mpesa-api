# Carsai Mozambique | mpesa-api

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)

**[English](README.en.md) | [Português](README.md)**

PHP API for M-PESA Mozambique integration. Library officially migrated to `carsaimz` namespace.

## 📦 Installation

### Via Composer (Recommended)
```bash
composer require carsaimz/mpesa-api
```

### Manual Installation

1. Download the files
2. Include the autoloader in your project:

```php
require_once 'path/to/autoload.php';
```

## ⚙️ Configuration

Get your credentials at https://developer.mpesa.vm.co.mz/

```php
use carsaimz\Mpesa;

$mpesa = Mpesa::init(
    $api_key,        // API Key from portal
    $public_key,     // Public Key from portal
    "development"    // "development" (sandbox) or "production"
);
```

## 🚀 Supported Operations

### 1. C2B (Client → Business)

Customer to business payment.

```php
$response = $mpesa->c2b([
    "value" => 10,                          // Transaction amount
    "client_number" => "258840000000",      // Customer number (format: 258XXXXXXXXX)
    "agent_id" => 171717,                   // Agent/Service Provider code
    "transaction_reference" => 1234567,     // Transaction reference (unique)
    "third_party_reference" => 33333        // Third party reference
]);

print_r($response);
```

### 2. B2C (Business → Client)

Business to customer payment.

```php
$response = $mpesa->b2c([
    "value" => 10,
    "client_number" => "258840000000",
    "agent_id" => 171717,
    "transaction_reference" => 1234567,
    "third_party_reference" => 33333
]);

print_r($response);
```

### 3. B2B (Business → Business)

Business to business transfer.

```php
$response = $mpesa->b2b([
    "value" => 10,
    "agent_id" => 171717,                   // Sender code
    "agent_receiver_id" => 979797,          // Receiver code
    "transaction_reference" => 1234567,
    "third_party_reference" => 33333
]);

print_r($response);
```

### 4. Reversal

Transaction reversal/refund.

```php
$response = $mpesa->reversal([
    "value" => 10,                          // Amount to reverse
    "security_credential" => "",           // Security credential (generated)
    "indicator_identifier" => "",          // Initiator identifier
    "transaction_id" => "",                // Original transaction ID
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

### 5. Query Status

Check transaction status.

```php
$response = $mpesa->status([
    "transaction_id" => "",                // Transaction ID
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

### 6. Customer Name

Query customer name by number.

Note: Requires production credentials.

```php
$response = $mpesa->customer_name([
    "client_number" => "258840000000",
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

## ✅ Success Response

```json
{
    "output_ResponseCode": "INS-0",
    "output_ResponseDesc": "Request processed successfully",
    "output_TransactionID": "AG_20240321_12345",
    "output_ConversationID": "e73b138d-fbd4-4be7-9965-2f4600f44c7d",
    "output_ThirdPartyReference": "33333"
}
```

## ❌ Common Error Codes

Code Description Recommended Action
INS-0 Success -
INS-1 Internal system error Try again
INS-5 Duplicate transaction Use new reference
INS-6 Insufficient balance Check balance
INS-9 Transaction not found Verify transaction ID
INS-14 Invalid number Check format (258XXXXXXXXX)
INS-2001 Invalid credentials Verify API Key and Public Key

## 🔧 Complete Example

```php
<?php

require_once 'vendor/autoload.php';

use carsaimz\Mpesa;

try {
    // Configuration
    $mpesa = Mpesa::init(
        "YOUR_API_KEY_HERE",
        "YOUR_PUBLIC_KEY_HERE",
        "development"  // Change to "production" in production
    );
    
    // Execute C2B transaction
    $response = $mpesa->c2b([
        "value" => 100,
        "client_number" => "258840000000",
        "agent_id" => 171717,
        "transaction_reference" => time(),  // Use timestamp as unique reference
        "third_party_reference" => "ORDER_123"
    ]);
    
    // Process response
    $data = json_decode($response, true);
    
    if(isset($data['output_ResponseCode']) && $data['output_ResponseCode'] === 'INS-0') {
        echo "✅ Transaction successful!\n";
        echo "Transaction ID: " . $data['output_TransactionID'] . "\n";
        echo "Conversation ID: " . $data['output_ConversationID'] . "\n";
    } else {
        echo "❌ Transaction error: " . ($data['output_ResponseDesc'] ?? 'Unknown error') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}
```

## 📁 Project Structure

```
mpesa-api/
├── src/
│   ├── carsaimz/
│   │   ├── Mpesa.php
│   │   ├── Transaction.php
│   │   ├── Request.php
│   │   └── Cryptor.php
│   └── autoload.php
├── examples/
│   ├── c2b.php
│   ├── b2c.php
│   ├── b2b.php
│   ├── reversal.php
│   ├── status.php
│   └── customer_name.php
├── README.md
├── README.en.md
└── composer.json
```

## 🛠 Requirements

· PHP 7.4 or higher
· OpenSSL extension enabled
· Composer (for package installation)
· M-PESA credentials (sandbox or production)

## 🤝 Contributing

1. Fork the Project
2. Create your Feature Branch (git checkout -b feature/AmazingFeature)
3. Commit your Changes (git commit -m 'Add some AmazingFeature')
4. Push to the Branch (git push origin feature/AmazingFeature)
5. Open a Pull Request

## 📄 License

Distributed under GPL v3 License. See LICENSE for more information.

## 🆘 Support

· Report issues: GitHub Issues
· M-PESA Documentation: https://developer.mpesa.vm.co.mz/

---

Made with ❤️ for the Mozambican developer community.