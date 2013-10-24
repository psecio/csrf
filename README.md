csrf
====

A CSRF Token Generation Library

#### Supports code generation via:

- OpenSSL
- Bcrypt
- SH256

Example usage, generating a random token using the OpenSSL extension:


```php
<?php
require_once 'vendor/autoload.php';

$token = \Psecio\Csrf\Token::generate('random');
echo 'token: '.var_export($token, true)."\n";
?>
```

Example using the options to set the "cost" for the bcrypt hasing:

```php
<?php
$options = array(
	'method' => 'bcrypt'
	'cost' => 32
);
require_once 'vendor/autoload.php';

$token = \Psecio\Csrf\Token::generate('random', $options);
echo 'token: '.var_export($token, true)."\n";
?>
```