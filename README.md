## CSRF Handling

Example usage:

```php
<?php
require_once 'vendor/autoload.php';

echo '<pre>';

$manager = new \Psecio\Csrf\Manager(true);

if (isset($_POST['sub'])) {
    $result = $manager->verify($_POST['csrf-token']);
    var_export($result);
}

echo '</pre>';
//--------------------------
?>

<form action="/csrf/index.php" method="POST">
    <input type="submit" value="submit" name="sub"/>
    <input type="hidden" name="csrf-token" value="<?php echo $manager->generate(); ?>"/>
</form>
?>
```
