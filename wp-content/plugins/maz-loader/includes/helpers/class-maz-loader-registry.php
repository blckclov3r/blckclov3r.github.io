<?php

/**
 * Registry
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 */

/**
 * Registry
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 * @author     Your Name <email@example.com>
 */
class MZLDR_Registry {

	protected $data;
	protected $initialized = false;
	protected static $instances = array();
	public $separator = '.';

	/**
	 * Constructor
	 *
	 * @param   mixed  $data  The data to bind to the new Registry object.
	 */
	public function __construct($data = null)
	{
		// Instantiate the internal data object.
		$this->data = new \stdClass;

		// Optionally load supplied data.
		$this->bindData($this->data, $data);
	}

	/**
	 * Method to recursively bind data to a parent object.
	 *
	 * @param   object   $parent     The parent object on which to attach the data values.
	 * @param   mixed    $data       An array or object of data to bind to the parent object.
	 * @param   boolean  $recursive  True to support recursive bindData.
	 * @param   boolean  $allowNull  True to allow null values.
	 *
	 * @return  void
	 */
	protected function bindData($parent, $data, $recursive = true, $allowNull = true)
	{
		// The data object is now initialized
		$this->initialized = true;
		// Ensure the input data is an array.
		$data = \is_object($data) ? get_object_vars($data) : (array) $data;
		foreach ($data as $k => $v)
		{
			if (!$allowNull && !(($v !== null) && ($v !== '')))
			{
				continue;
			}
			if ($recursive && ((\is_array($v) && self::isAssociative($v)) || \is_object($v)))
			{
				if (!isset($parent->$k))
				{
					$parent->$k = new \stdClass;
				}
				$this->bindData($parent->$k, $v);
				continue;
			}
			$parent->$k = $v;
		}
	}

	/**
	 * Get a registry value.
	 *
	 * @param   string  $path     Registry path (e.g. joomla.content.showauthor)
	 * @param   mixed   $default  Optional default value, returned if the internal value is null.
	 *
	 * @return  mixed  Value of entry or null
	 */
	public function get($path, $default = null)
	{
		// Return default value if path is empty
		if (empty($path))
		{
			return $default;
		}
		if (!strpos($path, $this->separator))
		{
			return (isset($this->data->$path) && $this->data->$path !== null && $this->data->$path !== '') ? $this->data->$path : $default;
		}
		// Explode the registry path into an array
		$nodes = explode($this->separator, trim($path));
		// Initialize the current node to be the registry root.
		$node  = $this->data;
		$found = false;
		// Traverse the registry to find the correct node for the result.
		foreach ($nodes as $n)
		{
			if (\is_array($node) && isset($node[$n]))
			{
				$node  = $node[$n];
				$found = true;
				continue;
			}
			if (!isset($node->$n))
			{
				return $default;
			}
			$node  = $node->$n;
			$found = true;
		}
		if (!$found || $node === null || $node === '')
		{
			return $default;
		}
		return $node;
	}

	/**
	 * Method to determine if an array is an associative array.
	 *
	 * @param   array  $array  An array to test.
	 *
	 * @return  boolean
	 */
	private static function isAssociative($array)
	{
		if (\is_array($array))
		{
			foreach (array_keys($array) as $k => $v)
			{
				if ($k !== $v)
				{
					return true;
				}
			}
		}
		return false;
	}

}
