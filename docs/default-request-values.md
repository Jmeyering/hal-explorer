# Default Request Values

Every request has certain default values that are passed along to the HTTP Client
These values are.

#### Headers
```
Content-Type "application/json"
Accept "application/hal+json"
```

## Default Value modification

These values can by modified using the `setDefaults` method. This method accepts
a `Closure` that accepts the default values array. The `Closure` is able to make
any modifications and return the new array which will become the new defaults.

The defaults array returned must be formatted in [guzzle request options][0]
format.

The following is an example that changes the `Accept` header to something other
than `application/hal+json` and leaves the remaining options the same.

```php
$explorer->setDefaults(function($defaults){
    $defaults["headers"]["Accept"] = "application/json";

    return $defaults;
});
```


[0]: http://guzzle.readthedocs.org/en/latest/request-options.html
