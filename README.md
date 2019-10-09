# php-enum
Gives the ability to emulate and create enumeration objects in PHP.

## Install

```bash
composer install extalion/php-enum
```

## How to use

```php
/**
 * @method static RequestMethod get()
 * @method static RequestMethod post()
 * @method static RequestMethod delete()
 * @method static RequestMethod put()
 * @method static RequestMethod patch()
 */
class RequestMethod extends \Enum
{
    const VALUES = [
        'get' => 1,
        'post' => 2,
        'delete' => 3,
        'put' => 4,
        'patch' => 4
    ];
}

function request(string $url, RequestMethod $method, array $data = [])
{
    // ...

    if ($method === RequestMethod::post()) {
        \curl_setopt($ch, \CURLOPT_POST, 1);
        \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);
    }

    // ...
}
```

## Tests

```php
php -d zend.assertions=1 test.php
```
