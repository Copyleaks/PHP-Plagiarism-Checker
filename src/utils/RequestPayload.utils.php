<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

/**
 * Builds the JSON body of a request from a request model.
 *
 * Null properties are left out, so the server applies its documented default for every option you
 * did not set. The models you pass are never changed, and text values are sent exactly as given.
 */
class RequestPayload
{
  /**
   * Encodes a request model as JSON.
   *
   * @param mixed $data A request model, an array or a scalar.
   * @return string The JSON text.
   * @throws \JsonException When the data cannot be encoded, for example text that is not valid UTF-8.
   */
  public static function encode($data): string
  {
    return json_encode(self::toPayload($data), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  }

  /**
   * Converts a request model to plain values that json_encode() can write.
   * - Objects become JSON objects of their public, initialized properties. Null properties are left out.
   * - Associative arrays become JSON objects. Null values are left out.
   * - Lists stay lists, item for item.
   *
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
