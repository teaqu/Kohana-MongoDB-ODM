<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database connection wrapper/helper for nosql document databases.
 *
 * @package ODM
 * @category   Drivers
 */
abstract class Kohana_ODM_Database {

	/**
	 * @var  string  default instance name
	 */
	public static $default = 'default';

	/**
	 * @var  array  Database instances
	 */
	public static $instances = array();

	/**
	 * The database connection
	 * @var object
	 */
	protected $connection;

	/**
	 * The query
	 * @var array
	 */
	protected $_query;

	/**
	 * Database configuration
	 * @var array
	 */
	protected $_config;

	/**
	 * Stores the database configuration locally and name the instance.
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		// Store the config locally
		$this->_config = $config;
	}

	/**
	 * Get the correct property
	 *
	 * @param  string $prop
	 * @return mixed the property ether from this object or the database
	 */
	public function __get($prop) {
		return isset($this->$prop) ? $this->$prop : $this->connection->$prop;
    }

	/**
	 * Get a singleton Database instance. If configuration is not specified,
	 * it will be loaded from the database configuration file using the same
	 * group as the name.
	 *
	 * @param string $name
	 * @param array  $config
	 * @throws Kohana_Exception
	 * @return Database_MongoDB
	 */
	public static function instance($name = NULL, array $config = NULL)
	{
		if ($name === NULL)
		{
			// Use the default instance name
			$name = self::$default;
		}

		if ( ! isset(self::$instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the configuration for this database
				$config = Kohana::$config->load('database')->$name;
			}

			if ( ! isset($config['type']))
			{
				throw new Kohana_Exception('Database type not defined in :name configuration',
					array(':name' => $name));
			}

			// Set the driver class name
			$driver = 'Database_'.ucfirst($config['type']);

			// Create the database connection instance
			$driver = new $driver($config);

			// Store the database instance
			self::$instances[$name] = $driver;
		}

		return self::$instances[$name];
	}
}
