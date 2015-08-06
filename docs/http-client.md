# HTTP Client

HalExplorer does not come with a HTTP Client baked in. This gives the user
the most flexibility to utilize the most appropriate HTTP client for their
situation.

Whatever client is used must use an adapter to proxy the HTTP requests to the
client. Examine `HalExplorer/ClientAdapters/AdapterInterface` for more
information.

All HTTP Clients used *MUST* return [PSR-7][1] responses in order to work with
HalExplorer.

That being said, HalExplorer was built with `guzzlehttp/guzzle` in mind
and the default adapter works wonderfully with that package.

```php
// Using the default adapter with Guzzle 6
use GuzzleHttp\Client;
use HalExplorer\ClientAdapter\Adapter;

$client = new Client();
$adapter = new Adapter();

$adapter->setClient($client);
```

##Custom `ClientAdapter`

Feel free to create your own adapter for your favorite HTTP Client.
Adapters must implement `HalExplorer\ClientAdapters\AdapterInterface`.

All adapter methods will receive the resource endpoint and an array of options
to pass along to the HTTP client. Internally these options come formatted as a
[guzzle request options][0] array. Adapters should modify this as needed to fit
the needs of their particular HTTP Client.


[0]: http://guzzle.readthedocs.org/en/latest/request-options.html
[1]: http://www.php-fig.org/psr/psr-7
