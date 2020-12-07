<?php

namespace Donut\Util;

function array_dig($arr, $keys=null) {

  // support array or variable number of arguments
  if (! is_array($keys)) {
    $keys = array_slice(func_get_args(), 1);
  }

  // stop digging
  if (empty($keys)) {
    return $arr;
  }

  // dig this key
  $key = array_shift($keys);

  // skip null keys
  if (is_null($key)) {
    return array_dig($arr, $keys);
  }

  // dig!
  if (is_array($arr) && array_key_exists($key, $arr)) {
    return array_dig($arr[$key], $keys);
  }

  // nothing found
  return null;
};
