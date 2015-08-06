# Api Exploration

HalExplorer is able to traverse links that exist on a PSR-7 Response. HalExplorer
expects the response body to be correctly formatted according to the
[HAL draft][0]. Malformed response bodies will not be traversed correctly.

To use the exploration feature of the library we need to think about our
responses and their included `_links`, and `_embedded` as objects and their
relationships.

Fetching, Creating, Updating, and Deleting are the primary actions to perform on
a related object. HalExplorer exposes this functionality with the
`getRelation`, `createRelation`, `updateRelation`, and `deleteRelation` methods.

As expected, these methods map to `GET`, `POST`, `PUT`, and `DELETE` HTTP
methods.

For example, given the following response:

```json
{
    "id": 123,
    "data": "information",
    "_links": {
        "curies": [
            {
                "name": "doc",
                "href": "/docs/{rel}",
                "templated": true
            }
        ],
        "relation": {
            "title": "A relationship link",
            "href": "/relationship/456"
        },
        "doc:association": {
            "title": "another relationship",
            "href": "/association/789"
        },
        "self": {
            "title": "A Self Referencing Link",
            "href": "/self/123"
        },
        "templated": {
            "title": "A templated Link",
            "href": "/place/{name}"
        },
        "old-link": {
            "title": "A old and deprecated Link",
            "href": "/oldplace",
            "deprecation": "http://more-information.com/oldplace"
        }
    },
    "_embedded": {
        "relation": {
            "this": "is an embedded relationship",
            "id": 456,
            "_links": {
                "self": {
                    "href": "/relationship/456"
                }
            }
        }
    }
}
```

Calling `$association = $explorer->getRelation($object, "association");` will
create a `GET` request to `{baseUrl}/association/789` and return a the PSR-7
response object than can be further traversed by HalExplorer.

Similarially calling `$relation = $explorer->createRelation($object, "relation")`
will create a `POST` request to the `{baseUrl}/relationship/456` resource.

## Request Options

You can pass any request specific HTTP Client options along using the third
paramater of all exploration methods.

```php
$post = $explorer->createRelation($object, "relation", [
    "form_params" => [
        "field1": "value1",
        "field2": "value2",
    ],
]);
```

Will create the `POST` request to `{baseUrl}/relationship/456` and send along
the post values of `field1`, and `field2`.

The passed options merge with the [default options][1] and will overwrite any
matching keys.


## Api Entrypoint.

On initialization of the HalExplorer you should set the `baseUrl` for the
api. `$explorer->setBaseUrl("http://mysite.com/api");` This url will be handled
as the entrypoint to the target api. You can retreive the entrypoint response
by calling the `$explorer->enter()` method. From the entrypoint you should be
able to access all the resources exposed by the api.

## Manual Requests

To create a manual request you can use the `makeRequest` method.

```php
$response = $explorer->makeRequest("get", "resource/endpoint", $options);
```

[0]: https://tools.ietf.org/html/draft-kelly-json-hal-06
[1]: default-request-values.md
