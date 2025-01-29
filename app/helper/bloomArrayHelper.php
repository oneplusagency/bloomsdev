<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * bloomArrayHelper provides concrete implementation for [[ArrayHelper]].
 *
 * Do not use bloomArrayHelper. Use [[ArrayHelper]] instead.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class bloomArrayHelper
{
  /**
   * Option to perform case-sensitive sorts.
   *
   * @var    mixed  Boolean or array of booleans.
   * @since  11.3
   */
  protected static $sortCase;

  /**
   * Option to set the sort direction.
   *
   * @var    mixed  Integer or array of integers.
   * @since  11.3
   */
  protected static $sortDirection;

  /**
   * Option to set the object key to sort on.
   *
   * @var    string
   * @since  11.3
   */
  protected static $sortKey;

  /**
   * Option to perform a language aware sort.
   *
   * @var    mixed  Boolean or array of booleans.
   * @since  11.3
   */
  protected static $sortLocale;


  		/**
	 * A version of array_slice that takes keys into account.
	 * Thanks to pies at sputnik dot pl.
	 * This is made redundant by PHP 5.0.2's updated
	 * array_slice, but we can't assume everyone has that.
	 * FIXME: Reconcile this against the dupe in ArrayItemIterator.
	 * @see http://ca3.php.net/manual/en/function.array-slice.php
	 * @param $array Array
	 * @param $offset int
	 * @param $len int
   * @test bloomArrayHelper::array_slice_key($array, $offset, $len=-1);
	 */
	public static function array_slice_key($array, $offset, $len=-1) {
		if (!is_array($array)) return false;

		$return = array();
		$length = $len >= 0? $len: count($array);
		$keys = array_slice(array_keys($array), $offset, $length);
		foreach($keys as $key) {
			$return[$key] = $array[$key];
		}
		return $return;
	}

  /**
   * Function to convert array to integer values
   *
   * @param   array  &$array   The source array to convert
   * @param   mixed  $default  A default value (int|array) to assign if $array is not an array
   *
   * @return  void
   *
   * @since   11.1
   */
  public static function toInteger(&$array, $default = null)
  {
    if (is_array($array)) {
      foreach ($array as $i => $v) {
        $array[$i] = (int) $v;
      }
    } else {
      if ($default === null) {
        $array = array();
      } elseif (is_array($default)) {
        self::toInteger($default, null);
        $array = $default;
      } else {
        $array = array((int) $default);
      }
    }
  }

  /**
   * Utility function to map an array to a stdClass object.
   *
   * @param   array   &$array  The array to map.
   * @param   string  $class   Name of the class to create
   *
   * @return  object   The object mapped from the given array
   *
   * @since   11.1
   */
  public static function toObject(&$array, $class = 'stdClass')
  {
    $obj = null;

    if (is_array($array)) {
      $obj = new $class();

      foreach ($array as $k => $v) {
        if (is_array($v)) {
          $obj->$k = self::toObject($v, $class);
        } else {
          $obj->$k = $v;
        }
      }
    }
    return $obj;
  }

  /**
   * Utility function to map an array to a string.
   *
   * @param   array    $array         The array to map.
   * @param   string   $inner_glue    The glue (optional, defaults to '=') between the key and the value.
   * @param   string   $outer_glue    The glue (optional, defaults to ' ') between array elements.
   * @param   boolean  $keepOuterKey  True if final key should be kept.
   *
   * @return  string   The string mapped from the given array
   *
   * @since   11.1
   */
  public static function toString(
    $array = null,
    $inner_glue = '=',
    $outer_glue = ' ',
    $keepOuterKey = false
  ) {
    $output = array();

    if (is_array($array)) {
      foreach ($array as $key => $item) {
        if (is_array($item)) {
          if ($keepOuterKey) {
            $output[] = $key;
          }
          // This is value is an array, go and do it again!
          $output[] = self::toString(
            $item,
            $inner_glue,
            $outer_glue,
            $keepOuterKey
          );
        } else {
          $output[] = $key . $inner_glue . '"' . $item . '"';
        }
      }
    }

    return implode($outer_glue, $output);
  }

  /**
   * Utility function to map an object to an array
   *
   * @param   object   $p_obj    The source object
   * @param   boolean  $recurse  True to recurse through multi-level objects
   * @param   string   $regex    An optional regular expression to match on field names
   *
   * @return  array    The array mapped from the given object
   *
   * @since   11.1
   */
  public static function fromObject($p_obj, $recurse = true, $regex = null)
  {
    if (is_object($p_obj)) {
      return self::_fromObject($p_obj, $recurse, $regex);
    } else {
      return null;
    }
  }

  /**
   * Utility function to map an object or array to an array
   *
   * @param   mixed    $item     The source object or array
   * @param   boolean  $recurse  True to recurse through multi-level objects
   * @param   string   $regex    An optional regular expression to match on field names
   *
   * @return  array  The array mapped from the given object
   *
   * @since   11.1
   */
  protected static function _fromObject($item, $recurse, $regex)
  {
    if (is_object($item)) {
      $result = array();

      foreach (get_object_vars($item) as $k => $v) {
        if (!$regex || preg_match($regex, $k)) {
          if ($recurse) {
            $result[$k] = self::_fromObject($v, $recurse, $regex);
          } else {
            $result[$k] = $v;
          }
        }
      }
    } elseif (is_array($item)) {
      $result = array();

      foreach ($item as $k => $v) {
        $result[$k] = self::_fromObject($v, $recurse, $regex);
      }
    } else {
      $result = $item;
    }
    return $result;
  }

  /**
   * Extracts a column from an array of arrays or objects
   *
   * @param   array   &$array  The source array
   * @param   string  $index   The index of the column or name of object property
   *
   * @return  array  Column of values from the source array
   *
   * @since   11.1
   */
  public static function getColumnJoom(&$array, $index)
  {
    $result = array();

    if (is_array($array)) {
      foreach ($array as &$item) {
        if (is_array($item) && isset($item[$index])) {
          $result[] = $item[$index];
        } elseif (is_object($item) && isset($item->$index)) {
          $result[] = $item->$index;
        }
        // Else ignore the entry
      }
    }
    return $result;
  }

  /**
   * Utility function to return a value from a named array or a specified default
   *
   * @param   array   &$array   A named array
   * @param   string  $name     The key to search for
   * @param   mixed   $default  The default value to give if no key found
   * @param   string  $type     Return type for the variable (INT, FLOAT, STRING, WORD, BOOLEAN, ARRAY)
   *
   * @return  mixed  The value from the source array
   *
   * @since   11.1
   */
  public static function getValueJoom(
    &$array,
    $name,
    $default = null,
    $type = ''
  ) {
    $result = null;

    if (isset($array[$name])) {
      $result = $array[$name];
    }

    // Handle the default case
    if (is_null($result)) {
      $result = $default;
    }

    // Handle the type constraint
    switch (strtoupper($type)) {
      case 'INT':
      case 'INTEGER':
        // Only use the first integer value
        @preg_match('/-?[0-9]+/', $result, $matches);
        $result = @(int) $matches[0];
        break;

      case 'FLOAT':
      case 'DOUBLE':
        // Only use the first floating point value
        @preg_match('/-?[0-9]+(\.[0-9]+)?/', $result, $matches);
        $result = @(float) $matches[0];
        break;

      case 'BOOL':
      case 'BOOLEAN':
        $result = (bool) $result;
        break;

      case 'ARRAY':
        if (!is_array($result)) {
          $result = array($result);
        }
        break;

      case 'STRING':
        $result = (string) $result;
        break;

      case 'WORD':
        $result = (string) preg_replace('#\W#', '', $result);
        break;

      case 'NONE':
      default:
        // No casting necessary
        break;
    }
    return $result;
  }

  /**
   * Takes an associative array of arrays and inverts the array keys to values using the array values as keys.
   *
   * Example:
   * $input = array(
   *     'New' => array('1000', '1500', '1750'),
   *     'Used' => array('3000', '4000', '5000', '6000')
   * );
   * $output = bloomArrayHelper::invert($input);
   *
   * Output would be equal to:
   * $output = array(
   *     '1000' => 'New',
   *     '1500' => 'New',
   *     '1750' => 'New',
   *     '3000' => 'Used',
   *     '4000' => 'Used',
   *     '5000' => 'Used',
   *     '6000' => 'Used'
   * );
   *
   * @param   array  $array  The source array.
   *
   * @return  array  The inverted array.
   *
   * @since   12.3
   */
  public static function invert($array)
  {
    $return = array();

    foreach ($array as $base => $values) {
      if (!is_array($values)) {
        continue;
      }

      foreach ($values as $key) {
        // If the key isn't scalar then ignore it.
        if (is_scalar($key)) {
          $return[$key] = $base;
        }
      }
    }
    return $return;
  }

  /**
   * Method to determine if an array is an associative array.
   *
   * @param   array  $array  An array to test.
   *
   * @return  boolean  True if the array is an associative array.
   *
   * @since   11.1
   */
  public static function isAssociativeJoom($array)
  {
    if (is_array($array)) {
      foreach (array_keys($array) as $k => $v) {
        if ($k !== $v) {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Pivots an array to create a reverse lookup of an array of scalars, arrays or objects.
   *
   * @param   array   $source  The source array.
   * @param   string  $key     Where the elements of the source array are objects or arrays, the key to pivot on.
   *
   * @return  array  An array of arrays pivoted either on the value of the keys, or an individual key of an object or array.
   *
   * @since   11.3
   */
  public static function pivot($source, $key = null)
  {
    $result = array();
    $counter = array();

    foreach ($source as $index => $value) {
      // Determine the name of the pivot key, and its value.
      if (is_array($value)) {
        // If the key does not exist, ignore it.
        if (!isset($value[$key])) {
          continue;
        }

        $resultKey = $value[$key];
        $resultValue = &$source[$index];
      } elseif (is_object($value)) {
        // If the key does not exist, ignore it.
        if (!isset($value->$key)) {
          continue;
        }

        $resultKey = $value->$key;
        $resultValue = &$source[$index];
      } else {
        // Just a scalar value.
        $resultKey = $value;
        $resultValue = $index;
      }

      // The counter tracks how many times a key has been used.
      if (empty($counter[$resultKey])) {
        // The first time around we just assign the value to the key.
        $result[$resultKey] = $resultValue;
        $counter[$resultKey] = 1;
      } elseif ($counter[$resultKey] == 1) {
        // If there is a second time, we convert the value into an array.
        $result[$resultKey] = array($result[$resultKey], $resultValue);
        $counter[$resultKey]++;
      } else {
        // After the second time, no need to track any more. Just append to the existing array.
        $result[$resultKey][] = $resultValue;
      }
    }

    unset($counter);

    return $result;
  }

  /**
   * Utility function to sort an array of objects on a given field
   *
   * @param   array  &$a             An array of objects
   * @param   mixed  $k              The key (string) or a array of key to sort on
   * @param   mixed  $direction      Direction (integer) or an array of direction to sort in [1 = Ascending] [-1 = Descending]
   * @param   mixed  $caseSensitive  Boolean or array of booleans to let sort occur case sensitive or insensitive
   * @param   mixed  $locale         Boolean or array of booleans to let sort occur using the locale language or not
   *
   * @return  array  The sorted array of objects
   *
   * @since   11.1
   */
  public static function sortObjects(
    &$a,
    $k,
    $direction = 1,
    $caseSensitive = true,
    $locale = false
  ) {
    if (!is_array($locale) || !is_array($locale[0])) {
      $locale = array($locale);
    }

    self::$sortCase = (array) $caseSensitive;
    self::$sortDirection = (array) $direction;
    self::$sortKey = (array) $k;
    self::$sortLocale = $locale;

    usort($a, array(__CLASS__, '_sortObjects'));

    self::$sortCase = null;
    self::$sortDirection = null;
    self::$sortKey = null;
    self::$sortLocale = null;

    return $a;
  }

  /**
   * Callback function for sorting an array of objects on a key
   *
   * @param   array  &$a  An array of objects
   * @param   array  &$b  An array of objects
   *
   * @return  integer  Comparison status
   *
   * @see     bloomArrayHelper::sortObjects()
   * @since   11.1
   */
  protected static function _sortObjects(&$a, &$b)
  {
    $key = self::$sortKey;

    for ($i = 0, $count = count($key); $i < $count; $i++) {
      if (isset(self::$sortDirection[$i])) {
        $direction = self::$sortDirection[$i];
      }

      if (isset(self::$sortCase[$i])) {
        $caseSensitive = self::$sortCase[$i];
      }

      if (isset(self::$sortLocale[$i])) {
        $locale = self::$sortLocale[$i];
      }

      $va = $a->$key[$i];
      $vb = $b->$key[$i];

      if (
        (is_bool($va) || is_numeric($va)) &&
        (is_bool($vb) || is_numeric($vb))
      ) {
        $cmp = $va - $vb;
      } elseif ($caseSensitive) {
        $cmp = strcmp($va, $vb, $locale);
      } else {
        $cmp = strcasecmp($va, $vb, $locale);
      }

      if ($cmp > 0) {
        return $direction;
      }

      if ($cmp < 0) {
        return -$direction;
      }
    }

    return 0;
  }

  /**
   * Multidimensional array safe unique test
   *
   * @param   array  $myArray  The array to make unique.
   *
   * @return  array
   *
   * @see     http://php.net/manual/en/function.array-unique.php
   * @since   11.2
   */
  public static function arrayUnique($myArray)
  {
    if (!is_array($myArray)) {
      return $myArray;
    }

    foreach ($myArray as &$myvalue) {
      $myvalue = serialize($myvalue);
    }

    $myArray = array_unique($myArray);

    foreach ($myArray as &$myvalue) {
      $myvalue = unserialize($myvalue);
    }

    return $myArray;
  }

  /**
   * ```php
   * // working with array
   * $username = bloomArrayHelper::getValue($_POST, 'username');
   * // working with object
   * $username = bloomArrayHelper::getValue($user, 'username');
   * // working with anonymous function
   * $fullName = bloomArrayHelper::getValue($user, function ($user, $defaultValue) {
   *     return $user->firstName . ' ' . $user->lastName;
   * });
   * // using dot format to retrieve the property of embedded object
   * $street = bloomArrayHelper::getValue($users, 'address.street');
   * // using an array of keys to retrieve the value
   * $value = bloomArrayHelper::getValue($versions, ['1.0', 'date']);
   * ```
   *
   * @param array|object $array array or object to extract value from
   * @param string|\Closure|array $key key name of the array element, an array of keys or property name of the object,
   * or an anonymous function returning the value. The anonymous function signature should be:
   * `function($array, $defaultValue)`.
   * The possibility to pass an array of keys is available since version 2.0.4.
   * @param mixed $default the default value to be returned if the specified array key does not exist. Not used when
   * getting value from an object.
   * @return mixed the value of the element if found, default value otherwise
   */
  public static function getValue($array, $key, $default = null)
  {
    if ($key instanceof \Closure) {
      return $key($array, $default);
    }

    if (is_array($key)) {
      $lastKey = array_pop($key);
      foreach ($key as $keyPart) {
        $array = static::getValue($array, $keyPart);
      }
      $key = $lastKey;
    }

    if (
      is_array($array) &&
      (isset($array[$key]) || array_key_exists($key, $array))
    ) {
      return $array[$key];
    }

    if (($pos = strrpos($key, '.')) !== false) {
      $array = static::getValue($array, substr($key, 0, $pos), $default);
      $key = substr($key, $pos + 1);
    }

    if (is_object($array)) {
      // this is expected to fail if the property does not exist, or __get() is not implemented
      // it is not reliably possible to check whether a property is accessible beforehand
      return $array->$key;
    } elseif (is_array($array)) {
      return isset($array[$key]) || array_key_exists($key, $array)
        ? $array[$key]
        : $default;
    } else {
      return $default;
    }
  }

  /**
   * Removes an item from an array and returns the value. If the key does not exist in the array, the default value
   * will be returned instead.
   *
   * Usage examples,
   *
   * ```php
   * // $array = ['type' => 'A', 'options' => [1, 2]];
   * // working with array
   * $type = bloomArrayHelper::remove($array, 'type');
   * // $array content
   * // $array = ['options' => [1, 2]];
   * ```
   *
   * @param array $array the array to extract value from
   * @param string $key key name of the array element
   * @param mixed $default the default value to be returned if the specified key does not exist
   * @return mixed|null the value of the element if found, default value otherwise
   */
  public static function remove(&$array, $key, $default = null)
  {
    if (
      is_array($array) &&
      (isset($array[$key]) || array_key_exists($key, $array))
    ) {
      $value = $array[$key];
      unset($array[$key]);

      return $value;
    }

    return $default;
  }

  /**
   * Removes items with matching values from the array and returns the removed items.
   *
   * Example,
   *
   * ```php
   * $array = ['Bob' => 'Dylan', 'Michael' => 'Jackson', 'Mick' => 'Jagger', 'Janet' => 'Jackson'];
   * $removed = bloomArrayHelper::removeValue($array, 'Jackson');
   * // result:
   * // $array = ['Bob' => 'Dylan', 'Mick' => 'Jagger'];
   * // $removed = ['Michael' => 'Jackson', 'Janet' => 'Jackson'];
   * ```
   *
   * @param array $array the array where to look the value from
   * @param string $value the value to remove from the array
   * @return array the items that were removed from the array
   * @since 2.0.11
   */
  public static function removeValue(&$array, $value)
  {
    $result = [];
    if (is_array($array)) {
      foreach ($array as $key => $val) {
        if ($val === $value) {
          $result[$key] = $val;
          unset($array[$key]);
        }
      }
    }
    return $result;
  }

  /**
   * Indexes and/or groups the array according to a specified key.
   * The input should be either multidimensional array or an array of objects.
   *
   * The $key can be either a key name of the sub-array, a property name of object, or an anonymous
   * function that must return the value that will be used as a key.
   *
   * $groups is an array of keys, that will be used to group the input array into one or more sub-arrays based
   * on keys specified.
   *
   * If the `$key` is specified as `null` or a value of an element corresponding to the key is `null` in addition
   * to `$groups` not specified then the element is discarded.
   *
   * For example:
   *
   * ```php
   * $array = [
   *     ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
   *     ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
   *     ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
   * ];
   * $result = ArrayHelper::index($array, 'id');
   * ```
   *
   * The result will be an associative array, where the key is the value of `id` attribute
   *
   * ```php
   * [
   *     '123' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
   *     '345' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
   *     // The second element of an original array is overwritten by the last element because of the same id
   * ]
   * ```
   *
   * An anonymous function can be used in the grouping array as well.
   *
   * ```php
   * $result = ArrayHelper::index($array, function ($element) {
   *     return $element['id'];
   * });
   * ```
   *
   * Passing `id` as a third argument will group `$array` by `id`:
   *
   * ```php
   * $result = ArrayHelper::index($array, null, 'id');
   * ```
   *
   * The result will be a multidimensional array grouped by `id` on the first level, by `device` on the second level
   * and indexed by `data` on the third level:
   *
   * ```php
   * [
   *     '123' => [
   *         ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
   *     ],
   *     '345' => [ // all elements with this index are present in the result array
   *         ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
   *         ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
   *     ]
   * ]
   * ```
   *
   * The anonymous function can be used in the array of grouping keys as well:
   *
   * ```php
   * $result = ArrayHelper::index($array, 'data', [function ($element) {
   *     return $element['id'];
   * }, 'device']);
   * ```
   *
   * The result will be a multidimensional array grouped by `id` on the first level, by the `device` on the second one
   * and indexed by the `data` on the third level:
   *
   * ```php
   * [
   *     '123' => [
   *         'laptop' => [
   *             'abc' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
   *         ]
   *     ],
   *     '345' => [
   *         'tablet' => [
   *             'def' => ['id' => '345', 'data' => 'def', 'device' => 'tablet']
   *         ],
   *         'smartphone' => [
   *             'hgi' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
   *         ]
   *     ]
   * ]
   * ```
   *
   * @param array $array the array that needs to be indexed or grouped
   * @param string|\Closure|null $key the column name or anonymous function which result will be used to index the array
   * @param string|string[]|\Closure[]|null $groups the array of keys, that will be used to group the input array
   * by one or more keys. If the $key attribute or its value for the particular element is null and $groups is not
   * defined, the array element will be discarded. Otherwise, if $groups is specified, array element will be added
   * to the result array without any key. This parameter is available since version 2.0.8.
   * @return array the indexed and/or grouped array
   */
  public static function index($array, $key, $groups = [])
  {
    $result = [];
    $groups = (array) $groups;

    foreach ($array as $element) {
      $lastArray = &$result;

      foreach ($groups as $group) {
        $value = static::getValue($element, $group);
        if (!array_key_exists($value, $lastArray)) {
          $lastArray[$value] = [];
        }
        $lastArray = &$lastArray[$value];
      }

      if ($key === null) {
        if (!empty($groups)) {
          $lastArray[] = $element;
        }
      } else {
        $value = static::getValue($element, $key);
        if ($value !== null) {
          if (is_float($value)) {
            $value = (string) $value;
          }
          $lastArray[$value] = $element;
        }
      }
      unset($lastArray);
    }

    return $result;
  }

  /**
   * Returns the values of a specified column in an array.
   * The input array should be multidimensional or an array of objects.
   *
   * For example,
   *
   * ```php
   * $array = [
   *     ['id' => '123', 'data' => 'abc'],
   *     ['id' => '345', 'data' => 'def'],
   * ];
   * $result = ArrayHelper::getColumn($array, 'id');
   * // the result is: ['123', '345']
   *
   * // using anonymous function
   * $result = ArrayHelper::getColumn($array, function ($element) {
   *     return $element['id'];
   * });
   * ```
   *
   * @param array $array
   * @param string|\Closure $name
   * @param bool $keepKeys whether to maintain the array keys. If false, the resulting array
   * will be re-indexed with integers.
   * @return array the list of column values
   */
  public static function getColumn($array, $name, $keepKeys = true)
  {
    $result = [];
    if ($keepKeys) {
      foreach ($array as $k => $element) {
        $result[$k] = static::getValue($element, $name);
      }
    } else {
      foreach ($array as $element) {
        $result[] = static::getValue($element, $name);
      }
    }

    return $result;
  }

  /**
   * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
   * The `$from` and `$to` parameters specify the key names or property names to set up the map.
   * Optionally, one can further group the map according to a grouping field `$group`.
   *
   * For example,
   *
   * ```php
   * $array = [
   *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
   *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
   *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
   * ];
   *
   * $result = ArrayHelper::map($array, 'id', 'name');
   * // the result is:
   * // [
   * //     '123' => 'aaa',
   * //     '124' => 'bbb',
   * //     '345' => 'ccc',
   * // ]
   *
   * $result = ArrayHelper::map($array, 'id', 'name', 'class');
   * // the result is:
   * // [
   * //     'x' => [
   * //         '123' => 'aaa',
   * //         '124' => 'bbb',
   * //     ],
   * //     'y' => [
   * //         '345' => 'ccc',
   * //     ],
   * // ]
   * ```
   *
   * @param array $array
   * @param string|\Closure $from
   * @param string|\Closure $to
   * @param string|\Closure $group
   * @return array
   */
  public static function map($array, $from, $to, $group = null)
  {
    $result = [];
    foreach ($array as $element) {
      $key = static::getValue($element, $from);
      $value = static::getValue($element, $to);
      if ($group !== null) {
        $result[static::getValue($element, $group)][$key] = $value;
      } else {
        $result[$key] = $value;
      }
    }

    return $result;
  }

  /**
   * Checks if the given array contains the specified key.
   * This method enhances the `array_key_exists()` function by supporting case-insensitive
   * key comparison.
   * @param string $key the key to check
   * @param array $array the array with keys to check
   * @param bool $caseSensitive whether the key comparison should be case-sensitive
   * @return bool whether the array contains the specified key
   */
  public static function keyExists($key, $array, $caseSensitive = true)
  {
    if ($caseSensitive) {
      // Function `isset` checks key faster but skips `null`, `array_key_exists` handles this case
      // http://php.net/manual/en/function.array-key-exists.php#107786
      return isset($array[$key]) || array_key_exists($key, $array);
    } else {
      foreach (array_keys($array) as $k) {
        if (strcasecmp($key, $k) === 0) {
          return true;
        }
      }

      return false;
    }
  }

  /**
   * Sorts an array of objects or arrays (with the same structure) by one or several keys.
   * @param array $array the array to be sorted. The array will be modified after calling this method.
   * @param string|\Closure|array $key the key(s) to be sorted by. This refers to a key name of the sub-array
   * elements, a property name of the objects, or an anonymous function returning the values for comparison
   * purpose. The anonymous function signature should be: `function($item)`.
   * To sort by multiple keys, provide an array of keys here.
   * @param int|array $direction the sorting direction. It can be either `SORT_ASC` or `SORT_DESC`.
   * When sorting by multiple keys with different sorting directions, use an array of sorting directions.
   * @param int|array $sortFlag the PHP sort flag. Valid values include
   * `SORT_REGULAR`, `SORT_NUMERIC`, `SORT_STRING`, `SORT_LOCALE_STRING`, `SORT_NATURAL` and `SORT_FLAG_CASE`.
   * Please refer to [PHP manual](http://php.net/manual/en/function.sort.php)
   * for more details. When sorting by multiple keys with different sort flags, use an array of sort flags.
   * @throws InvalidParamException if the $direction or $sortFlag parameters do not have
   * correct number of elements as that of $key.
   */
  public static function multisort(
    &$array,
    $key,
    $direction = SORT_ASC,
    $sortFlag = SORT_REGULAR
  ) {
    $keys = is_array($key) ? $key : [$key];
    if (empty($keys) || empty($array)) {
      return;
    }
    $n = count($keys);
    if (is_scalar($direction)) {
      $direction = array_fill(0, $n, $direction);
    } elseif (count($direction) !== $n) {
      throw new Exception(
        'The length of $direction parameter must be the same as that of $keys.'
      );
    }
    if (is_scalar($sortFlag)) {
      $sortFlag = array_fill(0, $n, $sortFlag);
    } elseif (count($sortFlag) !== $n) {
      throw new Exception(
        'The length of $sortFlag parameter must be the same as that of $keys.'
      );
    }
    $args = [];
    foreach ($keys as $i => $key) {
      $flag = $sortFlag[$i];
      $args[] = static::getColumn($array, $key);
      $args[] = $direction[$i];
      $args[] = $flag;
    }

    // This fix is used for cases when main sorting specified by columns has equal values
    // Without it it will lead to Fatal Error: Nesting level too deep - recursive dependency?
    $args[] = range(1, count($array));
    $args[] = SORT_ASC;
    $args[] = SORT_NUMERIC;

    $args[] = &$array;
    call_user_func_array('array_multisort', $args);
  }

  /**
   * Encodes special characters in an array of strings into HTML entities.
   * Only array values will be encoded by default.
   * If a value is an array, this method will also encode it recursively.
   * Only string values will be encoded.
   * @param array $data data to be encoded
   * @param bool $valuesOnly whether to encode array values only. If false,
   * both the array keys and array values will be encoded.
   * @param string $charset the charset that the data is using. If not set,
   * [[\yii\base\Application::charset]] will be used.
   * @return array the encoded data
   * @see http://www.php.net/manual/en/function.htmlspecialchars.php
   */
  public static function htmlEncode($data, $valuesOnly = true, $charset = null)
  {
    if ($charset === null) {
      $charset = 'UTF-8';
    }
    $d = [];
    foreach ($data as $key => $value) {
      if (!$valuesOnly && is_string($key)) {
        $key = htmlspecialchars($key, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
      }
      if (is_string($value)) {
        $d[$key] = htmlspecialchars(
          $value,
          ENT_QUOTES | ENT_SUBSTITUTE,
          $charset
        );
      } elseif (is_array($value)) {
        $d[$key] = static::htmlEncode($value, $valuesOnly, $charset);
      } else {
        $d[$key] = $value;
      }
    }

    return $d;
  }

  /**
   * Decodes HTML entities into the corresponding characters in an array of strings.
   * Only array values will be decoded by default.
   * If a value is an array, this method will also decode it recursively.
   * Only string values will be decoded.
   * @param array $data data to be decoded
   * @param bool $valuesOnly whether to decode array values only. If false,
   * both the array keys and array values will be decoded.
   * @return array the decoded data
   * @see http://www.php.net/manual/en/function.htmlspecialchars-decode.php
   */
  public static function htmlDecode($data, $valuesOnly = true)
  {
    $d = [];
    foreach ($data as $key => $value) {
      if (!$valuesOnly && is_string($key)) {
        $key = htmlspecialchars_decode($key, ENT_QUOTES);
      }
      if (is_string($value)) {
        $d[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
      } elseif (is_array($value)) {
        $d[$key] = static::htmlDecode($value);
      } else {
        $d[$key] = $value;
      }
    }

    return $d;
  }

  /**
   * Returns a value indicating whether the given array is an associative array.
   *
   * An array is associative if all its keys are strings. If `$allStrings` is false,
   * then an array will be treated as associative if at least one of its keys is a string.
   *
   * Note that an empty array will NOT be considered associative.
   *
   * @param array $array the array being checked
   * @param bool $allStrings whether the array keys must be all strings in order for
   * the array to be treated as associative.
   * @return bool whether the array is associative
   */
  public static function isAssociative($array, $allStrings = true)
  {
    if (!is_array($array) || empty($array)) {
      return false;
    }

    if ($allStrings) {
      foreach ($array as $key => $value) {
        if (!is_string($key)) {
          return false;
        }
      }
      return true;
    } else {
      foreach ($array as $key => $value) {
        if (is_string($key)) {
          return true;
        }
      }
      return false;
    }
  }

  /**
   * Returns a value indicating whether the given array is an indexed array.
   *
   * An array is indexed if all its keys are integers. If `$consecutive` is true,
   * then the array keys must be a consecutive sequence starting from 0.
   *
   * Note that an empty array will be considered indexed.
   *
   * @param array $array the array being checked
   * @param bool $consecutive whether the array keys must be a consecutive sequence
   * in order for the array to be treated as indexed.
   * @return bool whether the array is associative
   */
  public static function isIndexed($array, $consecutive = false)
  {
    if (!is_array($array)) {
      return false;
    }

    if (empty($array)) {
      return true;
    }

    if ($consecutive) {
      return array_keys($array) === range(0, count($array) - 1);
    } else {
      foreach ($array as $key => $value) {
        if (!is_int($key)) {
          return false;
        }
      }
      return true;
    }
  }

  /**
   * Check whether an array or [[\Traversable]] contains an element.
   *
   * This method does the same as the PHP function [in_array()](http://php.net/manual/en/function.in-array.php)
   * but additionally works for objects that implement the [[\Traversable]] interface.
   * @param mixed $needle The value to look for.
   * @param array|\Traversable $haystack The set of values to search.
   * @param bool $strict Whether to enable strict (`===`) comparison.
   * @return bool `true` if `$needle` was found in `$haystack`, `false` otherwise.
   * @throws InvalidParamException if `$haystack` is neither traversable nor an array.
   * @see http://php.net/manual/en/function.in-array.php
   * @since 2.0.7
   */
  public static function isIn($needle, $haystack, $strict = false)
  {
    if ($haystack instanceof \Traversable) {
      foreach ($haystack as $value) {
        if ($needle == $value && (!$strict || $needle === $value)) {
          return true;
        }
      }
    } elseif (is_array($haystack)) {
      return in_array($needle, $haystack, $strict);
    } else {
      throw new Exception(
        'Argument $haystack must be an array or implement Traversable'
      );
    }

    return false;
  }

  /**
   * Checks whether a variable is an array or [[\Traversable]].
   *
   * This method does the same as the PHP function [is_array()](http://php.net/manual/en/function.is-array.php)
   * but additionally works on objects that implement the [[\Traversable]] interface.
   * @param mixed $var The variable being evaluated.
   * @return bool whether $var is array-like
   * @see http://php.net/manual/en/function.is_array.php
   * @since 2.0.8
   */
  public static function isTraversable($var)
  {
    return is_array($var) || $var instanceof \Traversable;
  }

  /**
   * Checks whether an array or [[\Traversable]] is a subset of another array or [[\Traversable]].
   *
   * This method will return `true`, if all elements of `$needles` are contained in
   * `$haystack`. If at least one element is missing, `false` will be returned.
   * @param array|\Traversable $needles The values that must **all** be in `$haystack`.
   * @param array|\Traversable $haystack The set of value to search.
   * @param bool $strict Whether to enable strict (`===`) comparison.
   * @throws InvalidParamException if `$haystack` or `$needles` is neither traversable nor an array.
   * @return bool `true` if `$needles` is a subset of `$haystack`, `false` otherwise.
   * @since 2.0.7
   */
  public static function isSubset($needles, $haystack, $strict = false)
  {
    if (is_array($needles) || $needles instanceof \Traversable) {
      foreach ($needles as $needle) {
        if (!static::isIn($needle, $haystack, $strict)) {
          return false;
        }
      }
      return true;
    } else {
      throw new Exception(
        'Argument $needles must be an array or implement Traversable'
      );
    }
  }

  /**
   * Filters array according to rules specified.
   *
   * For example:
   *
   * ```php
   * $array = [
   *     'A' => [1, 2],
   *     'B' => [
   *         'C' => 1,
   *         'D' => 2,
   *     ],
   *     'E' => 1,
   * ];
   *
   * $result = bloomArrayHelper::filter($array, ['A']);
   * // $result will be:
   * // [
   * //     'A' => [1, 2],
   * // ]
   *
   * $result = bloomArrayHelper::filter($array, ['A', 'B.C']);
   * // $result will be:
   * // [
   * //     'A' => [1, 2],
   * //     'B' => ['C' => 1],
   * // ]
   *
   * $result = bloomArrayHelper::filter($array, ['B', '!B.C']);
   * // $result will be:
   * // [
   * //     'B' => ['D' => 2],
   * // ]
   * ```
   *
   * @param array $array Source array
   * @param array $filters Rules that define array keys which should be left or removed from results.
   * Each rule is:
   * - `var` - `$array['var']` will be left in result.
   * - `var.key` = only `$array['var']['key'] will be left in result.
   * - `!var.key` = `$array['var']['key'] will be removed from result.
   * @return array Filtered array
   * @since 2.0.9
   */
  public static function filter($array, $filters)
  {
    $result = [];
    $forbiddenVars = [];

    foreach ($filters as $var) {
      $keys = explode('.', $var);
      $globalKey = $keys[0];
      $localKey = isset($keys[1]) ? $keys[1] : null;

      if ($globalKey[0] === '!') {
        $forbiddenVars[] = [substr($globalKey, 1), $localKey];
        continue;
      }

      if (empty($array[$globalKey])) {
        continue;
      }
      if ($localKey === null) {
        $result[$globalKey] = $array[$globalKey];
        continue;
      }
      if (!isset($array[$globalKey][$localKey])) {
        continue;
      }
      if (!array_key_exists($globalKey, $result)) {
        $result[$globalKey] = [];
      }
      $result[$globalKey][$localKey] = $array[$globalKey][$localKey];
    }

    foreach ($forbiddenVars as $var) {
      list($globalKey, $localKey) = $var;
      if (array_key_exists($globalKey, $result)) {
        unset($result[$globalKey][$localKey]);
      }
    }

    return $result;
  }
}
