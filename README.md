# hal-explorer: Hateoas Api explorer.

HalExplorer is a php client useful for exploring [HAL][1] formatted apis.
HalExplorer is able to craft requests and follow links to retreive, create,
update and delete resource relationships.

The codebase is fully covered by phpspec and extensively documented.

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

This method will craft a post request to whatever link href is contained in the
`$oject` response identified by `association`.

[0]: http://www.php-fig.org/psr/psr-7/
[1]: http://stateless.co/hal_specification.html
