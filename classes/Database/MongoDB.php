<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Mongo database connection.
 *
 * @package    ODM/Database
 * @category   Drivers
 */
class Database_MongoDB extends ODM_Database {
	
	/**
	 * Connect to the database
	 * 
	 * @return void
	 */
	public function connect()
	{
		if ($this->connection)
			return;

		// Extract the connection parameters, adding required variabels
		extract($this->_config['connection'] + array(
			'database'   => '',
			'username'   => '',
			'password'   => ''
		));

		// Prevent this information from showing up in traces
		unset($this->_config['connection']['username'], $this->_config['connection']['password']);

		// Connect to the database
		if ( ! $this->connection)
		{
			$this->connection = new MongoClient();
			$this->_select_db($database);
		}
	}

	/**
	 * Select the database
	 *
	 * @param  string $database Database
	 * @return void
	 */
	protected function _select_db($database)
	{
		$this->connection = $this->connection->$database;
	}

}
