# CHANGELOG

## 1.5.0
*  Allow the hypermedia parser to be set manually, this provides support for
custom use cases where HAL API's don't completely follow the specification
but are close enough that this library would still be helpful.
Added ParserInterface to facilitate this.

## 1.4.0
*  Allow for parsing absolute URL's correctly within makeRequest.

## 1.3.0
* Add the `patchUpdateRelation` method to the explorer to support partial object
updates.
* Update `AbstractAdapter::setClient()` to return self.

## 1.2.2
* Update the explorer to handle links that are templated but do not contain the
`templated` property.

## 1.2.1
* Fix some doctype and namespace issues

## 1.2.0
* Following [type](https://tools.ietf.org/html/draft-kelly-json-hal-07#section-5.3)
  enabled links will automatically set the request Accept header.

## 1.1.0

* Following [deprecated](https://tools.ietf.org/html/draft-kelly-json-hal-07#section-5.4)
  links throws a `HalExplorer\Exceptions\DeprecatedLinkException`.

