# Constraints

A library for testing primitive and abstract data types in PHP with type
juggling.

## Supported Primitive Types

* Integer
* String
* Array
* Double (aka float)
* Boolean
* Any defined PHP class

## Supported Abstract Types

* Bytes
* US Currency
* Date
* DateTime
* Length
* Range
* Time
* URL
* URL Path
* Year

## Extendable

The base `Constraint` class can be extended to add new abstract types.

## Example

```php
// A very simple example
$constraint = \DealNews\Constraints\Constraint::init();
$value = "1";
try {
    $value = $constraint->check($value, ["type" => "integer"]);
    // $value will now be integer 1
} catch (\DealNews\Constraints\ConstraintException $e) {
    echo $e->getMessage();
}
```
