<?php

namespace Copyleaks;

/**
 * Builds the JSON body of a request. Null properties are left out, so the server applies its default for every
 * option you did not set. The models you pass are not changed, and text is sent exactly as given.
 */
class RequestPayload
{
  /**
   * @param mixed $data A request model, an array or a scalar.
   * @return string
   * @throws \JsonException When the data cannot be encoded, e.g. text that is not valid UTF-8.
   */
  public static function encode($data): string
  {
    return json_encode(self::toPayload($data), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  }

  /**
   * Plain values for json_encode(): objects (public, initialized properties) and associative arrays become JSON
   * objects without their null values; lists stay lists.
   * @param mixed $value
   * @return mixed
   */
  public static function toPayload($value)
  {
    if ($value instanceof \BackedEnum) {
      return $value->value;
    }
    if ($value instanceof \JsonSerializable) {
      $value = $value->jsonSerialize();
    }
    if (is_object($value)) {
      return self::toObject(get_object_vars($value));
    }
    if (is_array($value)) {
      if (self::isList($value)) {
        return array_map([self::class, 'toPayload'], $value);
      }
      return self::toObject($value);
    }
    return $value;
  }

  private static function toObject(array $properties): \stdClass
  {
    $object = new \stdClass();
    foreach ($properties as $name => $value) {
      if ($value !== null) {
        $object->{$name} = self::toPayload($value);
      }
    }
    return $object;
  }

  private static function isList(array $array): bool
  {
    $expected = 0;
    foreach ($array as $key => $unused) {
      if ($key !== $expected++) {
        return false;
      }
    }
    return true;
  }
}
