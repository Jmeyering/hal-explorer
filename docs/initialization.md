# Initialization

In order to initialize the HalExplorer, we need to establish the `ClientAdapter`
on the explorer. A simple initialization using the default adapter and the
`guzzlehttp/guzzle` HTTP client looks like this.

```php
use GuzzleHttp\Client;
use HalExplorer\Explorer;
use HalExplorer\ClientAdapters\Adapter;

$client = new Client();
$explorer = new Explorer();
$adapter = new Adapter();

$adapter->setClient($client);
$explorer->setAdapter($adapter)->setBaseUrl("http://baseurl.com/api");
```
