<?php

/**
 * Publishing Rules
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 */

/**
 * Publishing Rules
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 * @author     Your Name <email@example.com>
 */
class MZLDR_Publishing_Rules {

	private $rules = [
		'categories',
		'cpt',
		'entiresite',
		'homepage',
		'pages',
		'posts',
		'tags'
	];

	/**
	 * The Loader
	 * 
	 * @var  object
	 */
	protected $loader;

	/**
	 * The Loader Model
	 * 
	 * @var  object
	 */
	protected $loader_model;

	/**
	 * The Publishing Rules
	 * 
	 * @var  object
	 */
	protected $publishing_rules;

	function __construct($loader) {
		$this->loader = $loader;
		$this->loader_model = new MZLDR_Loader_Model();
	}

	/**
	 * Sets whether we pass the Publishing Rules
	 * 
	 * @return  boolean
	 */
	public function passAll() {
		$pass = false;

		$parsed_data = $this->loader_model->parseLoaderData($this->loader);

		$this->publishing_rules = isset( $parsed_data->publish_settings->publishing_rules ) ? $parsed_data->publish_settings->publishing_rules : [];

		$publishing_rules = (array) $this->publishing_rules;

		if (!count($publishing_rules)) {
			return false;
		}

		$publishing_rules = array_values($publishing_rules);

		// find all Entiresite items and remove them from the array
		$entiresite_items = [];
		$pos = 0;
		foreach ($publishing_rules as $rule) {
			if ($rule->item == 'entiresite') {
				$entiresite_items[] = $rule;
				unset($publishing_rules[$pos]);
			}
			$pos++;
		}

		$publishing_rules = array_values($publishing_rules);
		
		// find all Homepage items and remove them from the array
		$homepage_items = [];
		$pos = 0;
		foreach ($publishing_rules as $rule) {
			if ($rule->item == 'homepage') {
				$homepage_items[] = $rule;
				unset($publishing_rules[$pos]);
			}
			$pos++;
		}

		// prepend Entiresite and Homepage
		$new_rules = array_merge($entiresite_items, $homepage_items);
		$new_rules = array_merge($new_rules, $publishing_rules);

		$had_true = false;
		
		foreach ($new_rules as $rule) {
			$pass = $this->canPassRule($rule);
			if (!$pass) {
				continue;
			} else {
				$had_true = $pass;
			}
		}

		return $had_true;
	}

	/**
	 * Call pass() from child to see if we can pass the publishing rule
	 * 
	 * @return  boolean
	 */
	protected function canPassRule($rule) {
		$pass = false;

		if (!in_array($rule->item, $this->rules)) {
			return false;
		}

		$class_name = 'MZLDR_' . ucfirst($rule->item) . '_Rule';
		$obj = new $class_name($rule);
		$pass = $obj->pass();
		
		return $pass;
	}

}