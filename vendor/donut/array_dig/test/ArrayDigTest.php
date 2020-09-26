<?php

namespace Donut\Util\Test;

use Donut\Util as u;

class ArrayDigTest extends \PHPUnit_Framework_TestCase {

  public function test_array_dig_is_defined() {
    $actual = function_exists('\Donut\Util\array_dig');
    $this->assertTrue($actual);
  }

  public function test_return_input_if_no_keys_are_given() {
    $arr    = array(1,2,3);
    $actual = u\array_dig($arr);

    $this->assertSame($arr, $actual);
  }

  public function test_return_value_if_key_is_present() {
    $arr    = array("a" => "a");
    $actual = u\array_dig($arr, "a");

    $this->assertSame("a", $actual);
  }

  public function test_return_null_if_key_does_not_exist() {
    $arr = array();
    $actual = u\array_dig($arr, "a");

    $this->assertNull($actual);
  }

  public function test_return_null_digging_in_non_array() {
    $arr    = 5;
    $actual = u\array_dig($arr, "a");

    $this->assertNull($actual);
  }

  public function test_return_deep_value_if_multiple_keys_are_given() {
    $arr = array("a" => array("b" => "c"));
    $actual = u\array_dig($arr, "a", "b");

    $this->assertSame("c", $actual);
  }

  public function test_use_an_array_to_dig_with_multiple_keys() {
    $arr    = array("a" => array("b" => array("c" => "d")));
    $actual = u\array_dig($arr, array("a", "b", "c"));

    $this->assertSame("d", $actual);
  }

  public function test_return_input_if_key_is_null() {
    $arr    = array("a" => "b");
    $actual = u\array_dig($arr, null);

    $this->assertSame(array("a" => "b"), $actual);
  }

  public function test_keep_digging_if_any_key_is_null() {
    $arr    = array("a" => array("b" => array("c" => "d")));
    $actual = u\array_dig($arr, null, "a", null, "b", null, "c", null);

    $this->assertSame("d", $actual);
  }

}
