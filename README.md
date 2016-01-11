## CSRF Handling

Cross-site request forgery (CSRF) tokens provide protection for your request submissions to help ensure they came from your application. The most common implementation in PHP uses the current session to store the token value and makes a comparison once the form is submitted. This library makes it simpler by handling this common task for you buy default. It:

- Provides effective random token generation via either OpenSSL or the PHP 7 csprng
- Handles the session storage and evaluation once the data is submitted.

Here's an example of it in use:

```php
<?php
require_once 'vendor/autoload.php';

$manager = new \Psecio\Csrf\Manager();

if (isset($_POST['sub'])) {
    $result = $manager->verify($_POST['csrf-token']);
    if ($result === false) {
        echo 'Bad token! Shame on you...';
    }
}
?>

<form action="/csrf/index.php" method="POST">
    <input type="submit" value="submit" name="sub"/>
    <input type="hidden" name="csrf-token" value="<?php echo $manager->generate(); ?>"/>
</form>
?>
```

As you can see there's three main parts:

- The `Manager` instance
- The call to the `generate` method for creating and storing the token
- The call to the `verify` method to check the submitted token against what's been stored

The way the library stores tokens, a unique hash is created and the token is then stored with this as the key. This prevents the usual problem of only being able to store one token at a time and resulting in false errors if more than one tab is open for the same application.

#### Double-Submit Tokens

There's also a concept in web application security called [double-submit cookies](https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)_Prevention_Cheat_Sheet#Double_Submit_Cookies) where the CSRF value is sent as a cookies as well as a part of the POST data. This cookie is set to `HTTP Only` meaning that no Javascript can access the cookie value, preventing theft from something like a cross-site scripting (XSS) attack.

To enable the automatic double-submit feature in this `Csrf` library, just pass `true` to the `Manager` constructor:

```php
<?php
$manager = new \Psecio\Csrf\Manager(true);
?>
```

That's it - the storage and evaluation of the cookie is include in the verification process along with the value stored in the session.

#### Extending with your own storage method

The `Manager` class also includes an optional parameter that lets you define your own storage method if for some reason the default session method doesn't work for your application. You just define a class that extends the `\Psecio\Csrf\Storage` class and pass it in as a second parameter:

```php
<?php

class DbStorage
{
    public function save($key, $code)
    {
        // Save to the database here...
    }
    public abstract function get($key)
    {
        // Read from the database here
    }
    public abstract function delete($key)
    {
        // Delete from the database here
    }
}
$dbStorage = new DbStorage();
$manager = new Manager(false, $dbStorage);

?>
```

The `Csrf` library will then use this storage method *instead* of the session method. Note: If you change the first parameter to `true` it will still use the cookie method for double-submit handling.
