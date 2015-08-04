# hal-explorer: Hateoas Api explorer.

[![Build Status](https://travis-ci.org/Jmeyering/hal-explorer.svg?branch=master)](https://travis-ci.org/Jmeyering/hal-explorer)


HalExplorer is a php client useful for exploring [HAL][1] formatted apis.
HalExplorer is able to craft requests and follow links to retreive, create,
update and delete resource relationships.

The codebase is fully covered by phpspec and extensively documented.

## Install
`composer require jmeyering/hal-explorer`

## Spec Tests
Phpspec is included with the composer deps so running the test suite simply
involves `vendor/bin/phpspec run`.

## Api Documentation
To generate api documentation use whatever phpdoc generation tool you want
but apigen is included with the composer deps. Just run
`vendor/bin/apigen generate` to create the documentation, then point your
browser to `public/index.html` to view.

## PSR7
The library makes exclusive use of [PSR7 messages][0]. Whatever http client is
used internally must return PSR7 Message interfaces.

## Usage
To use the exploration feature of the library we need to think about our
responses and their included `_links` as objects and relationships.

Fetching, Creating, Updating, and Deleting are the primary actions to perform on
a related object. HalExplorer exposes this functionality with the
`getRelation`, `createRelation`, `updateRelation`, and `deleteRelation` methods.

As expected, these methods map to `GET`, `POST`, `UPDATE`, and `DELETE` HTTP
methods.

```php
$explorer->createRelation($object, "association");
```

## Example
We will use the [heroku haltalk][2] api as an endpoint example and
guzzlehttp/guzzle for our HTTP Client.

```php
// This Example creates a new account with haltalk and creates a post from that
// account.
$client = new \GuzzleHttp\Client();
$explorer = new \HalExplorer\Explorer();
$adapter = new \HalExplorer\ClientAdapters\Adapter();

$adapter->setClient($client);
$explorer->setAdapter($adapter)->setBaseUrl("http://haltalk.herokuapp.com");

// The haltalk api requires both "application/hal+json" and, application/json"
// Accept headers to work. hal-explorer only adds "application/hal+json" by
// default so we need to override this default value.
$explorer->setDefaults(function($original){
    $original["headers"]["Accept"] = "application/hal+json, application/json";

    return $original;
});

$username = "myuniqueusername";

// Enter the haltalk api and return a PSR7 ResponseInterface
$entrypoint = $explorer->enter();

// Create an account with haltalk.
$accountResponse = $explorer->createRelation($entrypoint, "signup", [
    "body" => '{
        "username": "'.$username.'",
        "password": "password"
    }'
]);

// Retreive my account information using thy "me" link on the entrypoint.
// Because this is a templated link, we must pass templated data along.
$myAccount = $explorer->getRelation($entrypoint, "me", [
    "template" => [
        "name" => $username,
    ],
]);

// Create a post from my account. This resource requires basic auth.
$post = $explorer->createRelation($myAccount, "posts", [
    "body" => '{
        "content": "This is my post Content"
    }',
    "auth" => [
        $username,
        "password"
    ]
]);

// Haltalk return the post location in a response header. We can fetch that
// information using the PSR7 method, getHeaderLine()
$postLocation = $post->getHeaderLine("location");
```

[0]: http://www.php-fig.org/psr/psr-7
[1]: http://stateless.co/hal_specification.html
[2]: http://haltalk.herokuapp.com/explorer/browser.html

