# Carsai Mozambique | mpesa-api

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)

**[English](README.en.md) | [Português](README.md)**

API PHP para integração com M-PESA de Moçambique.

## 📦 Instalação

### Via Composer (Recomendado)
```bash
composer require carsaimz/mpesa-api
```

Instalação Manual

1. Faça download dos arquivos
2. Inclua o autoloader no seu projeto:

```php
require_once 'caminho/para/autoload.php';
```

⚙️ Configuração

Obtenha suas credenciais em https://developer.mpesa.vm.co.mz/

```php
use carsaimz\Mpesa;

$mpesa = Mpesa::init(
    $api_key,        // API Key do portal
    $public_key,     // Public Key do portal
    "development"    // "development" (sandbox) ou "production" (produção)
);
```

🚀 Operações Suportadas

1. C2B (Cliente → Negócio)

Pagamento do cliente para o negócio.

```php
$response = $mpesa->c2b([
    "value" => 10,                          // Valor da transação
    "client_number" => "258840000000",      // Número do cliente (formato: 258XXXXXXXXX)
    "agent_id" => 171717,                   // Código do agente/fornecedor
    "transaction_reference" => 1234567,     // Referência da transação (única)
    "third_party_reference" => 33333        // Referência de terceiro
]);

print_r($response);
```

2. B2C (Negócio → Cliente)

Pagamento do negócio para o cliente.

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

3. B2B (Negócio → Negócio)

Transferência entre empresas.

```php
$response = $mpesa->b2b([
    "value" => 10,
    "agent_id" => 171717,                   // Código do remetente
    "agent_receiver_id" => 979797,          // Código do destinatário
    "transaction_reference" => 1234567,
    "third_party_reference" => 33333
]);

print_r($response);
```

4. Reversão

Estorno de uma transação.

```php
$response = $mpesa->reversal([
    "value" => 10,                          // Valor a reverter
    "security_credential" => "",           // Credencial de segurança (gerada)
    "indicator_identifier" => "",          // Identificador do iniciador
    "transaction_id" => "",                // ID da transação original
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

5. Consultar Estado

Verificar status de uma transação.

```php
$response = $mpesa->status([
    "transaction_id" => "",                // ID da transação
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

6. Nome do Cliente

Consultar nome do cliente pelo número.

Nota: Requer credenciais de produção.

```php
$response = $mpesa->customer_name([
    "client_number" => "258840000000",
    "agent_id" => 171717,
    "third_party_reference" => 33333
]);

print_r($response);
```

✅ Resposta de Sucesso

```json
{
    "output_ResponseCode": "INS-0",
    "output_ResponseDesc": "Request processed successfully",
    "output_TransactionID": "AG_20240321_12345",
    "output_ConversationID": "e73b138d-fbd4-4be7-9965-2f4600f44c7d",
    "output_ThirdPartyReference": "33333"
}
```

❌ Códigos de Erro Comuns

Código Descrição Ação Recomendada
INS-0 Sucesso -
INS-1 Erro interno do sistema Tentar novamente
INS-5 Transação duplicada Usar nova referência
INS-6 Saldo insuficiente Verificar saldo
INS-9 Transação não encontrada Verificar ID da transação
INS-14 Número inválido Verificar formato (258XXXXXXXXX)
INS-2001 Credenciais inválidas Verificar API Key e Public Key

🔧 Exemplo Completo

```php
<?php

require_once 'vendor/autoload.php';

use carsaimz\Mpesa;

try {
    // Configuração
    $mpesa = Mpesa::init(
        "SUA_API_KEY_AQUI",
        "SUA_PUBLIC_KEY_AQUI",
        "development"  // Altere para "production" em produção
    );
    
    // Executar transação C2B
    $response = $mpesa->c2b([
        "value" => 100,
        "client_number" => "258840000000",
        "agent_id" => 171717,
        "transaction_reference" => time(),  // Usar timestamp como referência única
        "third_party_reference" => "ORDER_123"
    ]);
    
    // Processar resposta
    $data = json_decode($response, true);
    
    if(isset($data['output_ResponseCode']) && $data['output_ResponseCode'] === 'INS-0') {
        echo "✅ Transação realizada com sucesso!\n";
        echo "ID da Transação: " . $data['output_TransactionID'] . "\n";
        echo "ID da Conversação: " . $data['output_ConversationID'] . "\n";
    } else {
        echo "❌ Erro na transação: " . ($data['output_ResponseDesc'] ?? 'Erro desconhecido') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Exceção: " . $e->getMessage() . "\n";
}
```

📁 Estrutura do Projeto

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

🛠 Requisitos

· PHP 7.4 ou superior
· Extensão OpenSSL habilitada
· Composer (para instalação via pacote)
· Credenciais M-PESA (sandbox ou produção)

🤝 Contribuição

1. Faça um Fork do projeto
2. Crie uma Branch para sua feature (git checkout -b feature/AmazingFeature)
3. Commit suas mudanças (git commit -m 'Add some AmazingFeature')
4. Push para a Branch (git push origin feature/AmazingFeature)
5. Abra um Pull Request

📄 Licença

Distribuído sob licença GPL v3. Veja LICENSE para mais informações.

🆘 Suporte

· Reportar issues: GitHub Issues
· Documentação M-PESA: https://developer.mpesa.vm.co.mz/

---

Desenvolvido com ❤️ para a comunidade Moçambicana de desenvolvedores.