csrf
====

CSRF Token Generation Library

Example usage, generating a random token using the OpenSSL extension:


```
<?php

require_once 'vendor/autoload.php';

$token = \Psecio\Csrf\Token::generate('random', $options);
echo 'token: '.var_export($token, true)."\n";

?>
```